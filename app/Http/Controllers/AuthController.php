<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function register(Request $request) {
        $validator = validator($request->all(), [
            "email" => "required",
            "password" => "required",
            "avatar" => "nullable|image|mimes:jpeg,png,jpg|max:4096",
            "first_name" => "required",
            "last_name" => "required",
            "patronymic" => "nullable",
        ]);

        if($validator->fails()) {
            return $this->errors(errors: $validator->errors());
        }

        $image = $request->file('avatar');

        $data = $validator->validated();

        if ($image) {
            $url = Str::uuid() . "." . $image->getClientOriginalExtension();
            $image->move(public_path('avatars'), $url);
            $data['avatar'] = "avatars/" . $url;
        }

        if (User::where("email", $data["email"])->exists()) {
            return $this->errors(message: "user already exists!");
        }

        $user = User::create($data);

        return response()->json([
            "message"=>"Successfully registered!",
            "data"=>[
                "id"=>$user->id,
                "first_name"=>$user->first_name,
                "last_name"=>$user->last_name,
                "patronymic"=>$user->patronymic,
                "avatar"=>url($user->avatar),
            ]
        ], status: 200);
    }

    public function login(Request $request)
    {
        $validator = validator($request->all(), [
            "email" => "required",
            "password" => "required",
        ]);

        if($validator->fails()) {
            return $this->errors(errors: $validator->errors());
        }

        if (!auth()->attempt($validator->validated())) {
            return $this->errors(message: "Login failed", code: 401);
        }
        $user = auth()->user();

        $token = Str::uuid();
        $user->update(["token"=> $token]);

        return response()->json(data:["message"=>"Successfully logged in!","data" => [
            "id"=>$user->id,
            "first_name"=>$user->first_name,
            "last_name"=>$user->last_name,
            "patronymic"=>$user->patronymic,
            "avatar"=>url($user->avatar),
        ], "credentials" => $token], status: 200);
    }

    public function logout()
    {
        auth()->user()->update(["token"=> null]);
        return response()->json(data:["message"=>"Successfully logged out!"], status: 200);
    }
}
