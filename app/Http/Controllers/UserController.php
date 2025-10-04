<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{


    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        $existingUser = User::where('email', $validatedData['email'])->get()->first();
        if (!$existingUser) {
            $newUser = new User();
            $newUser->name = $validatedData['name'];
            $newUser->email = $validatedData['email'];
            $newUser->password = Hash::make($validatedData['password']);
            $newUser->user_type = 2;
            $newUser->save();
            return response()->json([
                "success" => true,
                "status" => 201,
                "message" => "user Registered successfully",
                "data" => $newUser
            ]);
        } else {
            return response()->json([
                "success" => false,
                "status" => 400,
                "message" => "User already exist"
            ]);
        }
    }



    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $userData = User::where('email', $validatedData['email'])->get()->first();
        if ($userData) {
            if ($userData['status'] == 'active') {
                if (Hash::check($validatedData['password'], $userData['password'])) {
                    $token = $userData->createToken('access-token')->accessToken;

                    return response()->json([
                        "success" => true,
                        "status" => 200,
                        "message" => "Login Successfull",
                        "data" => $userData,
                        "token" => $token,


                    ]);
                } else {
                    return response()->json([
                        "success" => false,
                        "status" => 400,
                        "message" => "Your Password is incorrect "
                    ]);
                }
            } else {
                return response()->json([
                    "success" => false,
                    "status" => 400,
                    "message" => "Your Account is blocked ! "
                ]);
            }
        } else {
            return response()->json([
                "success" => false,
                "status" => 404,
                "message" => "email not found"
            ]);
        }
    }
}
