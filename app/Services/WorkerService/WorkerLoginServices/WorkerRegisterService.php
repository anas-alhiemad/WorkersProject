<?php
namespace App\Services\WorkerService\WorkerLoginServices;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Worker;
use Illuminate\Support\Facades\Mail;
use App\Mail\verificationEmail;
class WorkerRegisterService{

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

    function Store($data,$request){
            $Worker = $this ->model->create(array_merge(
                $data->validated(),
                ['password' => bcrypt($request->password),
                 'photo' => $request->file('photo')->store('workers')
                ]
            ));
            return $Worker ->email;
        }



    function generateToken($email){
        $token = substr(md5(rand(0,9).$email. time()),0,32);
        $Worker = $this ->model->whereEmail($email)->first();
        $Worker ->verification_token = $token ;
        $Worker ->save();
        return $Worker;
         }




    function SendEmail($worker){
        Mail::to($worker ->email)->send(new verificationEmail($worker));
        
    }



    function  Register($request){

        try {
            DB::beginTransaction();
            $data = $this->validation($request);
            $email = $this->Store($data,$request);
            $worker = $this->generateToken($email);
            $this->SendEmail($worker);
            DB::commit();
            return response()->json(["Message"=>"account has been created please check your email"]);
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }

}}