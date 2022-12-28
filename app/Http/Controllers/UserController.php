<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailVerificationRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SignupUserRequest;
use App\Jobs\SendEmailJob;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Mail\VerfiUserEmail;
use App\Models\VerifyToken;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    // Signup->(In this function, user is being registered with all their )
    public function add(SignupUserRequest $request){
        $photo=$this->getImagePath($request);
        $request->merge([ 'profile_pic' => $photo ]);
        $user=User::create($request->only(['name','password','email','profile_pic','dob','gender']));
        $token=Str::random(30);
        $tokenLink=route('verfiy', [$token]);
        SendEmailJob::dispatch(['email'=>$request->email,'tokenlink'=>$tokenLink,'subject'=>"Verify Email"]); 
        $user->tokens()->save(new VerifyToken(['token' =>$token ]));
        $message='Account Created  Successfully... Please check your mail for email verification... Thankyou';
        return response()->pfResponce($user,$message,200);   //using a  macro response 
    }


    public function getImagePath($request){
        $path=null;
        if ($request->hasFile('profile_picture')) {
             $path = $request->profile_picture->store('images');
        }
        return $path;
        
    }




    public function verfiyEmail(EmailVerificationRequest $request,$hash){
        
        $user=VerifyToken::where('token',$hash)->first()->user;
        $message ="user is verifed successfully...";
        $status=400;
        
        if(! $user->hasVerifiedEmail()) {
            $user->is_email_verified=true;
            $user->save();
            $user->tokens()->delete();
            $status=200;
        }else{
            $message="user is alraedy verified";
        }
        return response()->pfResponce(null,$message,$status);
    }





    public function login(LoginRequest $request){
        $user=User::where('email',$request->email)->first();
        $message="please first verify";
        $status=422;
        if($user && Hash::check($request->input('password'), $user->password)){
            if(!$user->is_email_verified==null){
                $api_token=Str::random(20);
                $user->tokens()->save(new VerifyToken(['token' => $api_token]));
                $user['api_token']=$api_token;
                $status=200;
                $message="user login successfully";
            }
            else{
                $user=null;
                $message="please first verify";
            }
        }else{
            $user=null;
            $status=400;
             $message="user not found";
        }
       
        
        
        return response()->pfResponce($user,$message,$status);     
    }


    public function forgot(ForgotPasswordRequest $request){
        $user=User::where('email',$request->input('email'))->first();
        $message="forgot token send to your email";
        $status=200;
        if($user){
            $token=Str::random(30);
            $tokenLink=route('password.reset', [$token]);
            SendEmailJob::dispatch(['email'=>$request->email,'tokenlink'=>$tokenLink,'subject'=>"Reset Password Email"]);
           $user->tokens()->save(new VerifyToken(['token' => $token])); 
           
        }
        else{
            $message="email not exist";
             $status=400;
        }
        return response()->pfResponce(null,$message,$status);
    }


    
    public  function restPassword(ResetPasswordRequest $request) {
    
        $user=$request->user;
        $user->password=$request->input('password');
        $user->save();
        $user->tokens()->delete();
        return response()->pfResponce(null,"password change successfully",200); 
    }

    public  function profile(Request $request) {
        $token=VerifyToken::where('token',$request->header('Authorization'))->first();
        $message="user profile viewed successfully";
        $status=200;
         if(!$token){
            $message="user not found";
            $status=400;
            $user=null;
        }else{
            $user=$token->user;
        }
        
        return response()->pfResponce($user,$message,$status); 
    }


    public  function update(ProfileUpdateRequest $request) {
        $message="user update successfully!";
        $status=200;
        $token=$request->header('Authorization');
        $user=VerifyToken::where('token',$token)->first()->user;
        
        if(!$request->has('profile_pic')){
            $photo=$this->getImagePath($request);
            if(!is_null($photo)) $request->merge([ 'profile_pic' => $photo ]);
            $user->update($request->only(['name','password','profile_pic','dob','gender']));
        }else{
            $message="invalid request parameter";
            $status=400;
        }
       
        return response()->pfResponce($user,$message,$status); 
    }

    
    public  function logout(Request $request) {
        $token=VerifyToken::where('token',$request->header('Authorization'))->first();
        $message="user logout successfully";
        $status=200;
         if(!$token){
            $message="user not found";
            $status=400;
        }else{
            $token->delete();
        }
        return response()->pfResponce(null,$message,$status); 
    }

   



}
