<?php

namespace App\Http\Controllers\Main;

use App\Data\Responses\Blog\PostResponse;
use App\Data\Responses\Blog\PromotionResponse;
use App\Http\Controllers\Controller;
use App\Models\Blogs\Post;
use App\Models\Blogs\Promotion;
use Illuminate\Http\JsonResponse;

class MainController extends Controller
{

    public function getBlogs():JsonResponse
    {
        $blogs = Post::all();
        return PostResponse::jsonSerialize($blogs);
    }

    public function getBlog(string $uuid):JsonResponse
    {
        $blog = Post::where('uuid', $uuid)->firstOrFail();
        return response()->json((new PostResponse($blog))->toArray());
    }

    public function getPromotions():JsonResponse
    {
        $promotions = Promotion::all()
            ->filter(fn($promotion) => $promotion->metadata['valid_from'] <= now() && $promotion->metadata['valid_to'] >= now());
        return  PromotionResponse::jsonSerialize($promotions);
    }
}
