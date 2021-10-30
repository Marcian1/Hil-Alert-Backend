<?php
namespace App\Http\Controllers;

use App\Http\Requests\ForgotRequest;
use App\Http\Requests\ResetRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ForgotController extends Controller
{
    public function forgot(ForgotRequest $request) {
        $email = $request->input('email');

        if(User::where('email',$email)->doesntExist()) {
            return response([
                'message' => 'User doesn\'t exist!'
            ],404);
        }
        $token = Str::random(10);

        
        
        try {
            \DB::table('password_resets')->insert(
                [
                    'email' => $email,
                    'token' => $token
                ]
            );
            
            \Mail::send('Mails.forgot', ['token' => $token], function($message) use ($email) {
                $message->to($email);
                $message->subject("Reset your password");
            });

            return response([
                'message' => 'Check your email!'
            ]);
        } catch (\Exception $exception) {
            return response([
                'exception' => $exception->getMessage()
            ],400);
        }
    }
    public function reset(ResetRequest $request) {
        $token = $request->input('token');
        if(!$passwordResets = \DB::table('password_resets')->where('token',$token)->first()){
            return response([
                'message' => 'Invalid token!'
            ],400);
        }

        if(!$user = User::where('email',$passwordResets->email)->first()){
            return response([
                'message' => 'User doesn\'t exist!'
            ],404);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();
        return response([
            'message' => 'Success'
        ]);
    }
}
