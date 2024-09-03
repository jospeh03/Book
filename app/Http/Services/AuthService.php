<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
/**
 * Summary of AuthService 
 * All of the input are coming from the controler and returned to it 
 * the majority of input type are request  
 */
class AuthService
{
    /**
     * Summary of register
     * @param \Illuminate\Http\Request $request
     * @return array array of the user type if it was created successfuly or not
     * and token if the proccess before done alright
     */
    public function register(Request $request)
    {
        //using the validateDate class to check the request was valid
        $validatedData = $this->validateData($request);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);
        //generating the token from the user that was created

        $token = JWTAuth::fromUser($user);

        return [
            $user,
            'token' => $token
        ];
    }
    /**
     * Summary of login
     * @param \Illuminate\Http\Request $request
     * @return null,$token return null if the users credentails was wrong
     */
    public function login(Request $request)
    {
        // here is the validator class is used again to check the request if it was valid or
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'=>'The email and the password is required'
            ]);;
        }

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'message'=>'User credentails was wrong please try again'
            ]);
        }

        return $token;
    }

    public function logout()
    {
        //making the token invalide for not to be redandent in other requests after validating it matches the login token
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    private function validateData(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
    }
}
