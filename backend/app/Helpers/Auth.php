<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Mail\MyMail;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

class Auth
{
    public static function send_email($email, $action)
    {


        $code = Auth::generate_code();
        $expires_at = Carbon::now()->addMinutes(15);

        $subject = '';
        $body = '';

        $verification = DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email,],
            [
                'token' => $code,
                'expires_at' => $expires_at
            ]
        );


        if ($verification) {
            if ($action == "activate") {
                $subject = 'Account Activation';
                $body = 'To be able to use your account, please activate it first by clicking the link below.';
                $link = env('CLIENT_URL') . "/activate/$code/$email";
            } else {
                $subject = 'Reset Password';
                $body = 'To reset your password, please click the link below.';
                $link = env('CLIENT_URL') . "/new-password/$code/$email";
            }
            $payload = [
                'to' => $email,
                'subject' => $subject,
                'body' => $body,
                'link' => $link,
            ];
            // 'link' => "http://127.0.0.1:8000/api/auth/activate/$code/$id"
            $is_sent = \Mail::to($payload['to'])->send(new MyMail($payload));
            if (!$is_sent) {
                return Response::error('Failed to send email', 500);
            }

            return true;
        }
    }

    public static function generate_code()
    {
        do {
            return $code = Str::random(40);
        } while (User::where('code', $code)->exists());
    }

    public static function check_identifier($identifier)
    {
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $identifierType = 'email';
        } else {
            $identifierType = 'username';
        }
        return $identifierType;
    }
}
