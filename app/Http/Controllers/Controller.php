<?php

namespace App\Http\Controllers;

abstract class Controller
{
    //
    public function errors(int $code = 422, string $message = "Validation failed", mixed $errors = null)
    {
        $response = [
            "message" => $message,
        ];
        if ($errors) {
            $response["errors"] = $errors;
        }
        return response()->json($response, $code);
    }

}
