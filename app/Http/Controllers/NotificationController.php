<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getUserNotifications()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized user',
            ]);
        }
        $notifications = $user->notifications;
        return response()->json([
            'status' => 200,
            'message' => 'Notifications retrieved successfully',
            'notifications' => $notifications,
        ]);
    }
}
