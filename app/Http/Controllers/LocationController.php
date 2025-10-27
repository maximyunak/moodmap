<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::all();
        return response()->json(data: [
            "data" => $locations
        ], status: 200);
    }

    public function create(Request $request)
    {
        $validator = validator($request->all(), [
            "name" => "required",
            "latitude" => "required",
            "longitude" => "required",
        ]);

        if ($validator->fails()) {
            return $this->errors(errors: $validator->errors());
        }

        $location = Location::create($validator->validated());

        return response()->json(data: [
            "message" => "Successfully created location",
            "data" => [
                "id" => $location->id,
                "name" => $location->name,
                "latitude" => $location->latitude,
                "longitude" => $location->longitude,
            ]
        ]);
    }

    public function update(Request $request, Location $location)
    {
        $validator = validator($request->all(), [
            "name" => "nullable",
            "latitude" => "nullable",
            "longitude" => "nullable",
        ]);

        if ($validator->fails()) {
            return $this->errors(errors: $validator->errors());
        }

        $location->update($validator->validated());
        return response()->json(data: ["message" => "Successfully updated location", "data" => [
            "id" => $location->id,
            "name" => $location->name,
            "latitude" => $location->latitude,
            "longitude" => $location->longitude,
        ]]);
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return response()->json(data: ["message" => "Successfully deleted location", "data" => [
            "id" => $location->id,
            "name" => $location->name,
            "latitude" => $location->latitude,
            "longitude" => $location->longitude,
        ]]);
    }
}
