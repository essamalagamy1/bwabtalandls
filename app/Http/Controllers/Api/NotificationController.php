<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\NotificationRequest;
use App\Http\Resources\NotificationResource;
use Illuminate\Support\Facades\Response;

class NotificationController extends Controller
{
    public function index()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $notifications = auth()->user()->notifications()->paginate(request()->query('limit', 10));
        $data = NotificationResource::collection($notifications);

        return Response::ok(__('lang.success'), $data, true);
    }

    public function read(NotificationRequest $request)
    {
        $notification = auth()->user()->notifications()->where('id', $request->notification_id)->first();
        $notification->markAsRead();

        return Response::ok(__('lang.success'));
    }

    public function readAll()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return Response::ok(__('lang.success'));
    }

    public function delete(NotificationRequest $request)
    {
        $notification = auth()->user()->notifications()->where('id', $request->notification_id)->first();
        $notification->delete();

        return Response::noContent();
    }

    public function unreadNotificationCount()
    {
        $data['count'] = auth()->user()->unreadNotifications->count();

        return Response::ok(__('lang.success'), $data);
    }

    public function disable()
    {
        // TODO: Implement disable notification logic
        return Response::ok(__('lang.success'));
    }
}
