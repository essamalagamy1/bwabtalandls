<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ChangePasswordRequest;
use App\Http\Requests\Api\ProfileUpdateRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ProfileController extends Controller
{
    public function index()
    {
        return Response::ok(data: new UserResource(auth()->user()));
    }

    public function update(ProfileUpdateRequest $request)
    {
        auth()->user()->update([
            'name' => $request->name,
        ]);
        if ($request->image) {
            auth()->user()->addMedia($request->image)->toMediaCollection('image');
        }

        return Response::ok(message: __('lang.success'));

    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        auth()->user()->update(['password' => $request->new_password]);

        return Response::ok(message: __('lang.reset_password_successfully'));
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return Response::ok(message: __('lang.logout_successfully'));
    }

    public function updateLanguage(Request $request)
    {
        auth()->user()->update(['lang' => $request->lang]);

        return Response::ok(message: __('lang.updated_successfully', ['attribute' => __('lang.language')]));
    }

    public function deleteAccount()
    {
        $user = auth()->user();
        $parentId = $user->parent_id;
        
        $user->delete();

        if ($parentId) {
            $otherChildrenCount = \App\Models\User::where('parent_id', $parentId)->count();
            if ($otherChildrenCount === 0) {
                \App\Models\User::where('id', $parentId)->delete();
            }
        }

        return Response::noContent();
    }
}
