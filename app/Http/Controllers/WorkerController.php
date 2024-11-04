<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\WorkerService\WorkerLoginServices\WorkerLoginService;
use App\Services\WorkerService\WorkerLoginServices\WorkerRegisterService;
use Illuminate\Support\Facades\Auth;
use App\Models\Worker;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\WorkerStoreRequest;
use Validator;
class WorkerController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:worker', ['except' => ['login', 'register','verify']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request){
    	// $validator = Validator::make($request->all(), [
        //     'email' => 'required|email',
        //     'password' => 'required|string|min:6',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 422);
        // }

        // if (! $token = auth()->guard('worker')->attempt($validator->validated())) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }

        // return response()->json([
        //     "Worker" => auth()->user(),
        //     "_token" => $token,
        //     ]);
        return(new WorkerLoginService())->Login($request);

    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */

     public function verify($token)
     {
        $worker = Worker::whereVerification_token($token)->first();
        if (!$worker) {
                     return response()->json(["Message" => "this token is invalid"], 400);
            }

        $worker -> verification_token = null;
        $worker -> verified_at = now();
        $worker -> save();
        return response()->json(["Message" => "your account has been verified "], 200);
     }

    public function register(WorkerStoreRequest $request)
    {

        // $validator = Validator::make($request->all(), [
        //         'name' => 'required|string|between:2,100',
        //         'email' => 'required|string|email|max:100|unique:admins',
        //         'password' => 'required|string|min:6',
        //         'phone' => 'required|string|min:6',
        //         'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        //         'location' => 'required|string',
        //   ]);

        // if($validator->fails())  {
        //     return response()->json($validator->errors()->toJson(), 400);
        //     }


   


        // $worker = Worker::create(array_merge(
        //             $validator->validated(),
        //             ['password' => bcrypt($request->password),
        //              'photo' => $request->file('photo')->store('workers')
        //             ]
        //         ));

        // return response()->json([
        //         'message' => 'User successfully registered',
        //         'user' => $worker
        // ], 201);


        return (new WorkerRegisterService())->Register($request);
    }



    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->guard('worker')->logout();

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
        return response()->json(auth()->guard('worker')->user());
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
            'user' => auth()->guard('worker')->user(),
        ]);
    }

}
