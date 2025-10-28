<?php

namespace App\Http\Controllers;

use App\Http\Resources\FeedbackResourse;
use App\Http\Resources\FeedbackSimpleResourse;
use App\Models\Feedback;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::where("status", "CREATED")->get();
        $feedbacks->load(["location", "user"]);

        return response()->json(data: [
            "data" => FeedbackSimpleResourse::collection($feedbacks)
        ], status: 200);
    }

    public function update(Feedback $feedback, Request $request) {
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
