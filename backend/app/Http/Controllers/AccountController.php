<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function me()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return Response::success(['user' => auth()->user()], null);
    }
}
