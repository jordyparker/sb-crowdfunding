<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    /**
     * Returns the list of categories
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(): Response
    {
        $categories = Cache::remember('categories', now()->addHour(), function () {
            return Category::query()
                ->with('parent', 'children')->get();
        });

        return response([
            'data' => CategoryResource::collection($categories),
            'message' => __('Categories successfully retrieved')
        ], 200);
    }
}
