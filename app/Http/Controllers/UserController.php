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
    // Signup
    public function add(SignupUserRequest $request){
        $photo=$this->getImagePath($request);
        $request->merge([ 'profile_pic' => $photo ]);
        // User records save in the database 
        $user=User::create($request->only(['name','password','email','profile_pic','dob','gender']));
        $token=Str::random(30);
        $tokenLink=route('verfiy', [$token]);
        // Mail sending to the user in the Mailtrap.io for user verifcation 
        SendEmailJob::dispatch(['email'=>$request->email,'tokenlink'=>$tokenLink,'subject'=>"Verify Email"]); 
        $user->tokens()->save(new VerifyToken(['token' =>$token ]));
        $message='Account Created  Successfully... Please check your mail for email verification... Thankyou';
        return response()->pfResponce($user,$message,200);   //using a  macro response 
    }
    // Image Storing->(image stored in local project folder (images))
    public function getImagePath($request){
        $path=null;
        if ($request->hasFile('profile_picture')) {
             $path = $request->profile_picture->store('images');
        }
        return $path;
    }
    //  Email Verfication
    public function verfiyEmail(EmailVerificationRequest $request,$hash){
        $user=VerifyToken::where('token',$hash)->first()->user;// check user records from database 
        $message ="user is verifed successfully...";
        $status=400;
        // Verified user and save in database 
        if(! $user->hasVerifiedEmail()) {
            $user->is_email_verified=true;//fill user verification in database
            $user->save(); 
            $user->tokens()->delete();// Delete email verification token from database after verfication 
            $status=200;
        }else{
            $message="user is alraedy verified";
        }
        return response()->pfResponce(null,$message,$status);  // using macroresposne 
    }
    // Login
    public function login(LoginRequest $request){
        $user=User::where('email',$request->email)->first();// check user records from database 
        $message="please first verify";
        $status=422;
        if($user && Hash::check($request->input('password'), $user->password)){
            // After login successfully token generater for further authentication which is also saved in the database 
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
        return response()->pfResponce($user,$message,$status); // using macro response    
    }
    // Forgot Password 
    public function forgot(ForgotPasswordRequest $request){
        $user=User::where('email',$request->input('email'))->first();// check user records from database 
        $message="forgot token send to your email";
        $status=200;
        if($user){
            // a random token generate , saved in database and send to th route  
            $token=Str::random(30);
            $tokenLink=route('password.reset', [$token]);
            // Mail sending to the user in the Mailtrap.io for user reset password
            SendEmailJob::dispatch(['email'=>$request->email,'tokenlink'=>$tokenLink,'subject'=>"Reset Password Email"]);
           $user->tokens()->save(new VerifyToken(['token' => $token])); 
        }
        else{
            $message="email not exist";
             $status=400;
        }
        return response()->pfResponce(null,$message,$status);// using macro response 
    }
    // Reset password
    public  function resetPassword(ResetPasswordRequest $request) {
        $user=$request->user;// get user records from database
        $user->password=$request->input('password');
        $user->save();// save new password in database against this user
        $user->tokens()->delete();//delete all existing token from database against this user id
        return response()->pfResponce(null,"password change successfully",200); //using macro response
    }
    // View user profile
    public  function profile(Request $request) {
        $token=VerifyToken::where('token',$request->header('Authorization'))->first();// check user records from database 
        $message="user profile viewed successfully";
        $status=200;
         if(!$token){
            $message="user not found";
            $status=400;
            $user=null;
        }else{
            $user=$token->user;
        }
        return response()->pfResponce($user,$message,$status); //using macro response
    }
    // Update user profile  
    public  function update(ProfileUpdateRequest $request) {
        $message="user update successfully!";
        $status=200;
        $token=$request->header('Authorization');// fetch token from header
        $user=VerifyToken::where('token',$token)->first()->user;// get user records from database
        if(!$request->has('profile_pic')){
            $photo=$this->getImagePath($request);// call getimage function for storing image in local storage and retrive extension
            if(!is_null($photo)) $request->merge([ 'profile_pic' => $photo ]);
            $user->update($request->only(['name','password','profile_pic','dob','gender']));// update edit data in datbase 
        }else{
            $message="invalid request parameter";
            $status=400;
        }
        return response()->pfResponce($user,$message,$status);// using macro response 
    }
    // User logout
    public  function logout(Request $request) {
        $token=VerifyToken::where('token',$request->header('Authorization'))->first();// match user token with input token from database
        $message="user logout successfully";
        $status=200;
        // if token matched then logout successfully and token delete from database against this user else show error message 
         if(!$token){
            $message="user not found";
            $status=400;
        }else{
            $token->delete();
        }
        return response()->pfResponce(null,$message,$status); // using macro response
    }

   



}
