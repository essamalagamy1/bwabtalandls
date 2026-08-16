<?php

use App\Enums\Status;
use App\Models\Banner;
use App\Models\Category;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

if (! function_exists('siteSetting')) {
    function siteSetting()
    {
        return Cache::remember('site_setting', 3600, function () {
            return SiteSetting::with('media')->first();
        });
    }
}

if (! function_exists('categories')) {
    function categories()
    {
        return Cache::remember('categories', 3600, function () {
            return Category::where('status', Status::Active)
                ->whereNull('parent_id')
                ->with(['children' => function ($query) {
                    $query->where('status', Status::Active);
                }])
                ->withCount('products')
                ->get();
        });
    }
}

if (! function_exists('banners')) {
    function banners()
    {
        return Cache::remember('banners', 3600, function () {
            return Banner::where('status', Status::Active)
                ->orderByRaw('COALESCE(sort, 999999) ASC')
                ->get();
        });
    }
}

if (! function_exists('adminRoles')) {
    function adminRoles()
    {
        return Cache::remember('admin_roles_list', 3600, function () {
            return \Spatie\Permission\Models\Role::where('is_main', false)
                ->get(['id', 'name'])
                ->toArray();
        });
    }
}

if (! function_exists('clearRolesCache')) {
    function clearRolesCache()
    {
        Cache::forget('admin_roles_list');
    }
}
