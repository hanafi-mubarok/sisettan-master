<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function read(Request $request, Notification $notification)
    {
        $currentUser = $request->user();
        $currentRole = strtolower((string) ($currentUser->role_name ?? ''));
        $currentUserId = (int) ($currentUser->id ?? 0);

        abort_unless(
            $notification->role === $currentRole || (int) $notification->user_id === $currentUserId,
            403
        );

        if (! $notification->is_read) {
            $notification->forceFill([
                'is_read' => true,
                'read_at' => now(),
            ])->save();
        }

        $redirectTarget = $notification->link ?: url()->previous();

        return redirect()->to($redirectTarget);
    }
}