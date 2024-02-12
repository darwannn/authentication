<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Mail\MyMail;
use App\Models\User;
use App\Helpers\Auth;

use App\Helpers\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\PersonalAccessToken;

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
        // if (filter_var($request->identifier, FILTER_VALIDATE_EMAIL)) {
        //     $identifierType = 'email';
        // } else {
        //     $identifierType = 'username';
        // }


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

                $code = Auth::generate_code();
                $expires_at = Carbon::now()->addMinutes(15);
                $user->code = $code;
                $user->expires_at = $expires_at;
                $user->save();
                $is_sent = Auth::send_email($user->email, env('CLIENT_URL') . "/activate/$code/$user->id");
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
        $code = 0;
        $inputs = $request->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'username' => ['required', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required',  'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&,*._])(?=.*\d).{8,16}$/', 'confirmed'],
            'password_confirmation' => ['required'],
        ], $this->custom_message);

        try {
            $inputs['password'] = bcrypt($inputs['password']);


            $code = Auth::generate_code();
            $expires_at = Carbon::now()->addMinutes(15);
            $user = User::create([
                'first_name' => $inputs['first_name'],
                'last_name' => $inputs['last_name'],
                'username' => $inputs['username'],
                'email' => $inputs['email'],
                'password' => $inputs['password'],
                'code' => $code,
                'expires_at' => $expires_at,


            ]);


            $is_sent = Auth::send_email($user->email, env('CLIENT_URL') . "/activate/$code/$user->id");
            if ($is_sent) {
                return Response::success(null, 'To be able to use your account, please activate it first by clicking the link sent to your email.');
            }
        } catch (\Exception $e) {
            return response()->json(["error" => $e]);
            error_log($e);
            return Response::error();
        }
    }

    public function activate($code, $id)
    {

        $user = User::where(['code' => $code, 'id' => $id])->first();
        if (!$user) {
            return Response::error('Unautorized', 403);
        }

        if ($user->expires_at < Carbon::now()) {
            $code = Auth::generate_code();
            $expires_at = Carbon::now()->addMinutes(15);
            $user->code = $code;
            $user->expires_at = $expires_at;
            $user->save();

            $is_sent = Auth::send_email($user->email, env('CLIENT_URL') . "/activate/$code/$user->id");
            if ($is_sent) {
                return Response::success(null, 'Activation code expired. A new verification code has been sent to your email.');
            }
        }

        $user->status = 'verified';
        $user->code = 0;
        $user->save();
        return Response::success(null, 'Account activated. You can now login to your account.');
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
            $code = Auth::generate_code();
            $expires_at = Carbon::now()->addMinutes(15);
            $user = User::where($identifierType, $inputs['identifier'])->first();

            if ($user) {
                $user->update([
                    $identifierType => $inputs['identifier'],
                    'status' => 'verified',
                    'code' => $code,
                    'expires_at' => $expires_at
                ]);


                $is_sent = Auth::send_email($user->email, env('CLIENT_URL') . "/new-password/$code/$user->id");
                if ($is_sent) {
                    return Response::success(null, 'To be able to change your password, please click the link sent to your email.');
                }
            }
        } catch (\Exception $e) {
            error_log($e);
            return Response::error();
        }
    }


    public function new_password(Request $request, $code, $id)
    {
        $user = User::where(['code' => $code, 'id' => $id,])->where('expires_at', '>', Carbon::now())->first();

        if (!$user) {
            return Response::error('Unautorized', 403);
        }

        $inputs = $request->validate([
            'password' => ['required',  'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&,*._])(?=.*\d).{8,16}$/', 'confirmed'],
            'password_confirmation' => ['required'],
        ], $this->custom_message);

        try {
            $user->password = bcrypt($inputs['password']);
            $user->code = 0;

            $user->status = 'verified';


            $user->save();
            return Response::success(null, 'Password changed successfully. You can now login with your new password.');
        } catch (\Exception $e) {
            error_log($e);
            return Response::error();
        }
    }

    public function verify(Request $request, $code, $id)
    {
        try {
            $user = User::where(['code' => $code, 'id' => $id,])->where('expires_at', '>', Carbon::now())->first();

            if ($user) {
                return Response::success(null, 'Authorized');
            }
            return Response::error('Unauthorized', 403);
        } catch (\Exception $e) {
            error_log($e);
            return Response::error();
        }
    }

    public function delete_unused_tokens()
    {
        try {

            $tokens = PersonalAccessToken::where('last_used_at', '<=', Carbon::now()->subHour())
                ->orWhere('expires_at', '<=', Carbon::now())
                ->delete();

            // if ($tokens) {
            return Response::success(null, 'Deleted unused tokens');
            // }
        } catch (\Exception $e) {
            error_log($e);
            return Response::error();
        }
    }
}
