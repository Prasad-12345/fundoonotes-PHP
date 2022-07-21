<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset as ModelsPasswordReset;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Notification\PasswordResetRequest;
use App\Http\Controllers\MailController;
use App\Mail\SendMail;

class PasswordController extends Controller
{
    public function resetPassword(Request $request){
        $request->validate([
            'userId' => 'required',
            'email' => 'required',
            'password' =>'required',
            'newPassword' => 'required'
        ]);
        $result = Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        if($result){
            User::where('id', $request->userId)->update(['password' => Hash::make($request->newPassword)]);
            return response()->json(['message'=>"password updated successfully", 'status'=>200]);
            
        }
        else{
            Log::channel('custom')->error("Check your old password");
            return response()->json(['message'=>"Check your old password", 'status'=>400]);
        }
    }

    public function forgotPassword(Request $request){
        $request->validate([
            'email' => 'required',
        ]);
        $email = $request->email;
        $user = User::where('email', $email)->first();
        if(!$user){
            return response()->json(['message' => "Email does not exists", 'status' => 404]);
        }
        else{
            
        $token = Str::random(10);
        $reset = new ModelsPasswordReset();
    
        $reset->email = $request->input('email');
        $reset->token = $request->input('token');

        Mail::to($email)->send(new SendMail($token, $email));
        return $this->successResponse();
        }
        
    }

    public function reset(Request $request, $token){
        $request->validate([
            'password' => 'require'
        ]);

        $passwordReset = PasswordReset::where('token', $token)->first();
        if(!$passwordReset){
            return response()->json(['message' => "Token is invalid or expired"]);
        }

        $user = User::where('email', $passwordReset->email)->first();
        $user->password = Hash::make($request->password);

        PasswordReset::where('email', $user->email)->delete();
        return "Password changed";
    }
}
