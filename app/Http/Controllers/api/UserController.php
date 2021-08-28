<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use Auth;
use App\Http\Controllers\AppHelper;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // $email = $request->get('email');

        // $user = DB::table('users')->where('email', $email)->first();

        $result = [
            "token" => $token,
            "user" => Auth::user()
        ];

        return response()->json(compact('result'));
    }

    public function register(Request $request)
    {

        $locationInfo = AppHelper::getLocationInfoByIp();

        $validator = Validator::make($request->all(), [
            'firsName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'firsName' => $request->get('firsName'),
            'lastName' => $request->get('lastName'),
            'email' => $request->get('email'),
            'countryCode' => $locationInfo['countryCode'],
            'country' => $locationInfo['countryName'],
            'region' => $locationInfo['region'],
            'password' => Hash::make($request->get('password')),
        ]);

        Mail::to($request->get('email'))->send(new EmailVerification);

        $token = JWTAuth::fromUser($user);

        $message = "Successfully send email!!";

        return response()->json(compact('user','token', 'message'),201);
    }



    

}
