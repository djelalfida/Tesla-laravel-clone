<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    function showMe() {
        return view("me");
    }

    function updateMe(Request $request) {
        $validated = $this -> performValidation($request);
        $user = $this -> saveUser($validated);

        return view("me", ["message" => "Profile successfully updated!"]);
    }

    function saveUser($data) {
        $user = User::find(Auth::user() -> id);

        $user -> name = $data["name"];
        $user -> email = $data["email"];

        $user -> save();

        return $user;
    }


    function performValidation(Request $request) {
        $rules = [
            "name" => "string|required|min:1|max:255",
            "email" => "email|required|min:1|max:255",
        ];

        return $request -> validate($rules);
    }

}