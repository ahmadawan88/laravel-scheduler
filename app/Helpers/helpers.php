<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

    function sendError($msg, $data = null, $status = false, $statusCode = 400) {
        $response = [
            'status' => $status,
            'message' => $msg,
            'data' => $data
        ];

        return response($response, $statusCode);
    }

    function sendSuccess($data, $msg = "", $status = true, $statusCode = 200) {
        $response = [
            'status' => $status,
            'message' => $msg,
            'data' => $data
        ];

        return response($response, $statusCode);
    }

    function getCurrentDate() {
        return date("Y-m-d");
    }

    function findUserByEmail($email) {
        return User::where("email", $email)->first();
    }
    function findOrCreate($userData) {
        $user = findUserByEmail($userData["email"]);
        if(!$user) {
            $user = new User();
            $user->email = $userData["email"];
            $user->first_name = $userData["first_name"];
            $user->last_name = $userData["last_name"];
            $password = (isset($userData["password"]) && $userData["password"]) ? $userData["password"] : config("settings.userMockPassword");
            $user->password = Hash::make($password);
            $user->save();
        }
        return $user;
    }