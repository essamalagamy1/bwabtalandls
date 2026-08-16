<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DataController extends Controller
{
    public function categories(Request $request)
    {
        $categories = Category::active()->with('media')->paginate($request->query('per_page', 20));

        return Response::ok(message: __('lang.categories'), data: CategoryResource::collection($categories), paginate: true);
    }

    public function subCategories(Request $request)
    {
        $subCategories = Category::active()->subCategory()
            ->when($request->parent_id, fn (Builder $query) => $query->where('parent_id', $request->parent_id))
            ->with('media')->paginate($request->query('per_page', 20));

        return Response::ok(message: __('lang.sub_categories'), data: CategoryResource::collection($subCategories), paginate: true);
    }
}
