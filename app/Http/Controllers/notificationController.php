<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class notificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json([
            'status' => 'success',
            'notification' => $notification
        ]);
    }
    public function markAllAsRead()
    {
        Auth::user()->notifications()->markAsRead();
        return response()->json([
            'status' => 'success',
        ]);
    }

    public function destroy($id)
    {
        DatabaseNotification::where('id', $id)->delete();

        return response()->json(['status' => 'success']);
    }
}
