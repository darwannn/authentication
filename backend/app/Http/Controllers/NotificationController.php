<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //
    public function show()
    {

        if (auth()->check()) {
            $notifications = auth()->user()->notifications;
            return Response::success(['notifications' => $notifications], null);
        } else {
            return Response::error('Unauthorized', 401);
        }
    }

    public function update($id)
    {

        if (auth()->check()) {
            $notification = auth()->user()->notifications->find($id);

            if ($notification) {
                if ($notification->read_at) {
                    $notification->markAsUnread();
                } else {
                    $notification->markAsRead();
                }

                return Response::success(null, null);
            } else {
                return Response::error('Notification not found', 404);
            }
        } else {
            return Response::error('Unauthorized', 401);
        }
    }

    public function updateAll()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return Response::success(null, null);
    }
}
