<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use Validator;
class ClientController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:client', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token =  auth()->guard('client')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            "Client" =>  auth()->guard('client')->user(),
            "_token" => $token,
            ]);

    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */



    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
                'name' => 'required|string|between:2,100',
                'email' => 'required|string|email|max:100|unique:admins',
                'password' => 'required|string|min:6',
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
              
          ]);

        if($validator->fails())  {
            return response()->json($validator->errors()->toJson(), 400);
            }


   


        $client = Client::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password),
                     'photo' => $request->file('photo')->store('client')
                    ]
                ));

        return response()->json([
                'message' => 'User successfully registered',
                'user' => $client
        ], 201);
    }



    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->guard('client')->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     *
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {

        //$user=User::where('id',Auth::id())->get();
        return response()->json(auth()->guard('client')->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->guard('client')->user(),
        ]);
    }

}
