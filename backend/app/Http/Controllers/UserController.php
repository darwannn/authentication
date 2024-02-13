<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\User;
use App\Helpers\Auth;

use App\Helpers\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    protected $custom_message;

    public function __construct()
    {
        $this->custom_message = [
            'identifier.required' => 'Email or username field is required.',
            'password.regex' => 'The password must be between 8 and 16 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character.',
            'identifier.exists' => 'Account does not exist.',
        ];
    }

    public function login(Request $request)
    {
        $identifierType = Auth::check_identifier($request->identifier);
        $inputs =  $request->validate([
            'identifier' => ['required', Rule::exists('users',  $identifierType)],
            'password' => ['required'],
        ], $this->custom_message);
        try {

            $user = User::where($identifierType, $inputs['identifier'])->first();
            if (!$user || !Hash::check($inputs['password'], $user->password)) {
                return Response::error('Incorrect Password', 400);
            }

            if ($user->status == 'pending') {


                $is_sent = Auth::send_email($user->email, "activate");
                if ($is_sent) {
                    return Response::error('Account not activated. To be able to use your account, please activate it first by clicking the link  sent to your email.');
                }
            }


            $token = $user->createToken(env('SANCTUM_SECRET'))->plainTextToken;
            return Response::success(['user' => $user, 'token' => $token], 'Login successfully');
        } catch (\Exception $e) {
            return response()->json(["error" => $e]);
            error_log($e);
            return Response::error();
        }
    }

    public function register(Request $request)
    {

        $inputs = $request->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'username' => ['required', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required',  'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&,*._])(?=.*\d).{8,16}$/', 'confirmed'],
            'password_confirmation' => ['required'],
        ], $this->custom_message);


        $inputs['password'] = bcrypt($inputs['password']);


        $user = User::create([
            'first_name' => $inputs['first_name'],
            'last_name' => $inputs['last_name'],
            'username' => $inputs['username'],
            'email' => $inputs['email'],
            'password' => $inputs['password'],
        ]);


        $is_sent = Auth::send_email($user->email, "activate");
        if ($is_sent) {
            return Response::success(null, 'To be able to use your account, please activate it first by clicking the link sent to your email.');
        }
    }

    public function activate($token, $email)
    {
        try {
            $password_reset = DB::table('password_reset_tokens')->where(['token' => $token, 'email' => $email])->first();
            if (!$password_reset) {
                return Response::error('Unautorized', 403);
            }

            if ($password_reset->expires_at < Carbon::now()) {
                $is_sent = Auth::send_email($password_reset->email, "activate");
                if ($is_sent) {
                    return Response::success(null, 'Activation link expired. A new verification link has been sent to your email.');
                }
            }

            $user = User::where('email', $email)->update(['status' => 'verified']);
            if (!$user) {
                return Response::error('Unautorized', 403);
            }



            DB::table('password_reset_tokens')->where(['token' => $token, 'email' => $email])->delete();

            return Response::success(null, 'Account activated. You can now login to your account.');
        } catch (\Exception $e) {
            error_log($e);
            return Response::error();
        }
    }

    public function logout()
    {
        try {
            auth()->user()->tokens()->delete();
            return Response::success(null, 'Logged out');
        } catch (\Exception $e) {
            error_log($e);
            return Response::error();
        }
    }

    public function forgot_password(Request $request)
    {

        $identifierType = Auth::check_identifier($request->identifier);
        $inputs =  $request->validate([
            'identifier' => ['required', Rule::exists('users',  $identifierType)],
        ], $this->custom_message);

        try {
            $user = User::where($identifierType, $inputs['identifier'])->first();

            if ($user) {
                $is_sent = Auth::send_email($user->email, "new-password");
                if ($is_sent) {
                    return Response::success(null, 'To be able to change your password, please click the link sent to your email.');
                }
            }
        } catch (\Exception $e) {
            error_log($e);
            return Response::error();
        }
    }


    public function new_password(Request $request, $token, $email)
    {
        $password_reset = DB::table('password_reset_tokens')->where(['token' => $token, 'email' => $email])->where('expires_at', '>', Carbon::now())->first();
        if (!$password_reset) {
            return Response::error('Unautorized', 403);
        }
        $inputs = $request->validate([
            'password' => ['required',  'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&,*._])(?=.*\d).{8,16}$/', 'confirmed'],
            'password_confirmation' => ['required'],
        ], $this->custom_message);
        try {
            $user = User::where('email', $email)->update(
                [
                    'password' => bcrypt($inputs['password']),
                    'status' => 'verified'
                ]
            );
            if ($user) {
                DB::table('password_reset_tokens')->where(['token' => $token, 'email' => $email])->delete();
            }
            return Response::success(null, 'Password changed successfully. You can now login with your new password.');
        } catch (\Exception $e) {
            error_log($e);
            return Response::error();
        }
    }

    public function verify($token, $email)
    {
        try {
            $user = DB::table('password_reset_tokens')->where(['token' => $token, 'email' => $email,])->where('expires_at', '>', Carbon::now())->first();

            if ($user) {
                return Response::success(null, 'Authorized');
            }
            return Response::error('Unauthorized', 403);
        } catch (\Exception $e) {
            error_log($e);
            return Response::error();
        }
    }
}
