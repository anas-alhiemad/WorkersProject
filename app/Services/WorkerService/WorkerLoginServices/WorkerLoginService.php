<?php

namespace App\Services\WorkerService\WorkerLoginServices;
use Validator;
use App\Models\Worker;
class WorkerLoginService 
{
    protected $model;
    function __construct()
    {
        $this->model = new Worker;
    }
 
    function validation ($request){
        $validator = Validator::make($request->all(),$request->rules());
         if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
         return $validator;
        }
    function IsValidData($data){

        if (! $token = auth()->guard('worker')->attempt($data->validated())) {
            return response()->json(['error' => 'InValidData'], 401);
        }
        return $token;
    }

    function GetStatus($email){
        $worker = $this->model->whereEmail($email)->first();
        $status = $worker->status;
        return $status;
    }

    function IsVerified($email){
        $worker = $this->model->whereEmail($email)->first();
        $verified = $worker->verified_at;
        return $verified;
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->guard('worker')->user(),
        ]);
    }

    function Login($request){
        $data = $this->validation($request);
        $token = $this->IsValidData($data);
        if($this ->IsVerified($request->email) == null) {
            return response()->json(["Message" => "your account not verified"],422);
        }elseif($this->GetStatus($request->email) == 0){
            return response()->json(["Message" => "your account pending"],422);
        };
       return $this->createNewToken($token);
    }

}