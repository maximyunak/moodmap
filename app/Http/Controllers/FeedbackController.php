<?php

namespace App\Http\Controllers;

use App\Http\Resources\FeedbackResourse;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
//        $feedbacks = Feedback::where("status", "APPROVED")->where("location_id", $request->query("location_id"))->paginate(10);
        $feedbacks = Feedback::where("status", "CREATED")->paginate(10);

        return response()->json(data: [
            "data" => $feedbacks
        ], status: 200);
    }

    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            "location_id" => "required|exists:locations,id",
            "emotion" => "required|string|in:SAD,ANGRY,HAPPY",
            "comment" => "required"
        ]);

        if ($validator->fails()) {
            return $this->errors(errors: $validator->errors());
        }

        $data = $validator->validated();
        $data["user_id"] = auth()->id();

        $feedback = Feedback::create($data);

        $feedback->load(["location", "user"])->refresh();

        return response()->json(data: [
            "message" => "Successfully created feedback",
            "data" => FeedbackResourse::make($feedback)
        ], status: 200);
    }

    public function show(Feedback $feedback, Request $request)
    {
        $feedback->load(["location", "user"]);

        $data = [
            "user" => [
                "id" => $feedback->user->id,
                "first_name" => $feedback->user->first_name,
                "last_name" => $feedback->user->last_name,
                "patronymic" => $feedback->user->patronymic,
                "avatar" => $feedback->user->avatar,
            ],
            "location" => [
                "id" => $feedback->location->id,
                "name" => $feedback->location->name,
                "longitude" => $feedback->location->longitude,
                "latitude" => $feedback->location->latitude,
            ],
            "emotion" => $feedback->emotion,
            "comment" => $feedback->comment,
            "created_at" => $feedback->created_at,
            "updated_at" => $feedback->updated_at,
        ];

        $token = $request->bearerToken();
        $user = User::where("token", $token)->first();
        if ($feedback->user->id === $user->id) {
            $data["status"] = $feedback->status;
        }

        return response()->json(data: [
            "data" => $data
        ], status: 200);
    }

    public function update(Feedback $feedback, Request $request)
    {
        $validator = validator($request->all(), [
            "emotion" => "nullable|in:SAD,ANGRY,HAPPY",
            "comment" => "nullable|string"
        ]);

        if (auth()->id() !== $feedback->user->id || $feedback->status === "CREATED") {
            return $this->errors(code: 403, message: "No rights? :(");
        }

        if ($validator->fails()) {
            return $this->errors(errors: $validator->errors());
        }

        $data = $validator->validated();
        $data["status"] = "CREATED";

        $feedback->update($data);

        return response()->json(data: [
            "message" => "Successfully updated feedback",
            "data" =>
                FeedbackResourse::make($feedback)
        ], status: 200);
    }

    public function my()
    {
        $feedbacks = Feedback::where("user_id", auth()->id())->get();

        return response()->json(data: [
            "data"=> $feedbacks
        ], status: 200);
    }

    public function created()
    {
        $feedbacks = Feedback::where("status", "CREATED")->get();
        $feedbacks->load(["location", "user"]);

        return response()->json(data: [
            "data" => FeedbackResourse::collection($feedbacks)
        ], status: 200);
    }

    public function status(Feedback $feedback, Request $request) {
        $validator = validator($request->all(), [
            "status" => "required|string|in:APPROVED,DECLINED",
        ]);

        if ($validator->fails()) {
            return $this->errors(errors: $validator->errors());
        }

        $feedback->update($validator->validated());
        return response()->json(data: [
            "message" => "Successfully updated feedback",
            "data" => FeedbackResourse::make($feedback)
        ]);
    }
}
