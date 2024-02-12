<?php

namespace App\Helpers;

use App\Mail\MyMail;
use App\Models\User;

class Auth
{
    public static function send_email($email, $link)
    {
        $subject = '';
        $body = '';

        if (strpos($link, 'activate') !== false) {
            $subject = 'Account Activation';
            $body = 'To be able to use your account, please activate it first by clicking the link below.';
        } else {
            $subject = 'Reset Password';
            $body = 'To reset your password, please click the link below.';
        }
        $payload = [
            'to' => $email,
            'subject' => $subject,
            'body' => $body,
            'link' => $link
        ];
        // 'link' => "http://127.0.0.1:8000/api/auth/activate/$code/$id"
        $is_sent = \Mail::to($payload['to'])->send(new MyMail($payload));
        if (!$is_sent) {
            return Response::error('Failed to send email', 500);
        }
        return true;
    }

    public static function generate_code()
    {
        do {
            return $code = mt_rand(1000000000, 9999999999);
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
