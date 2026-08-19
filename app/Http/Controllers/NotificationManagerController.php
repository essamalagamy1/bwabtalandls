<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationManagerController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'endpoint' => 'required',
            'keys.auth' => 'required',
            'keys.p256dh' => 'required',
        ]);

        $user = auth()->user();
        if ($user) {
            $user->updatePushSubscription($request->endpoint, $request->keys['p256dh'], $request->keys['auth']);
        }

        return response()->json(['success' => true], 200);
    }
}
