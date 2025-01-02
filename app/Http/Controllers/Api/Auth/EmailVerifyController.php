<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class EmailVerifyController extends Controller
{


    protected function redirectUrl(){
        return [
            // 'success' => config('app.frontend_url') . '/auth/verify-email-success',
            'success' => env('FRONTEND_URL') ?? env('APP_URL') . '/auth/verify-email-success',
            'error' => 'username'
        ];
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $message = 'Email verification failed';
        $status_code = 500;
        $user = null;

        // Email verification details
        $id = $request->id;
        $hash = $request->hash;
        $expires = $request->expires;

        // Check if the URL is signed
        $valid_signature = $request->hasValidSignature();

        // Get user information
        $user = User::where('id', $id)->first();
        if(!$user){

            $message = 'user not found';
            $status_code = 404;
            return response()->json(['success' => false,'message' => $message], $status_code);

        }


        // Check if the user exists and the email has been verified
        if($user->email_verified_at != null && $user->email){

            // Redirect to success page
            return redirect($this->redirectUrl()['success']);

            // Redirect to success page
            // $message = 'Email already verified';
            // $status_code = 200;

            // return response()->json(['success' => true,'message' => $message], $status_code);

        }




        $now = time();
        $valid_hash = hash_equals($hash, sha1($user->email));

        // $details = [
        //     'user_id'=>$id,
        //     'hash' => $hash,
        //     'expires' => $expires,
        //     'valid_signature' => $valid_signature,
        //     'valid_hash' => $valid_hash,
        //     'time' => $now,
        //     'valid_time' => ($expires >= $now),
        //     'time_remaining' => ($expires - $now),
        //     'user' => $user
        // ];


        // Check if the URL is signed and if it's not expired
        if (!$valid_signature && !$valid_hash && !($expires >= $now)) {

            $message = 'Invalid signature or hash or expired URL';
            $status_code = 403;
            return response()->json(['success' => false,'message' => $message], $status_code);

        // Check successful
        }else{
                $user->email_verified = true;
                $user->email_verified_at = now();
                $user->save();

                $message = 'Email verified successfully';
                $status_code = 200;

                // Redirect to success page
                return redirect($this->redirectUrl()['success']);

        }

        return response()->json(['success'=> true,'message' => $message], $status_code);
    }
}
