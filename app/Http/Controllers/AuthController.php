<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\AuthService;
use Tymon\JWTAuth\Facades\JWTAuth;
/**
 * Summary of AuthController
 * the AuthController Main @method are register,login,logout
 * There is an authService that simplifies the work of the controller 
 * how the service is intergrated to the controller :
 * 1.there is an property of the auth controller and an instance of authservice 
 * 2.make a constructer using the instance
 * 3.by using the instance we can reache to the methods of the service using dependency injection
 */
class AuthController extends Controller
{
    //here is the instance
    protected $authService;
    //here is the constructer
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    /**
     * Summary of register
     * @param \Illuminate\Http\Request $request 
     * the request is handeled to the register method in the authService that will return the token and the user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $user = $this->authService->register($request);
        
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user'=>$user,
        ], 201);
    }
    /**
     * Summary of login
     * @param \Illuminate\Http\Request $request
     * The request will the input of the authService which will return the token that will determine user status
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $token = $this->authService->login($request);

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }
    /**
     * Summary of logout
     * in this method the user token will be dumped at last but before that it will be valtided that it was'nt wrong or manipulated
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->authService->logout();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }
    /**
     * Summary of respondWithToken
     * In this class i had choosed protected type because i had'nt deal with it outside this class
     * here the token will pe parmetered so it will return to the login 
     * @param mixed $token
     * @return mixed|\Illuminate\Http\JsonResponse response of json containing the stuatus and message and the the token and it's time
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'status'=> 'success',
            'message'=> 'The user had succussfuly loged in',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' =>  JWTAuth::factory()->getTTL() * 60
        ], 200);
    }
}
