<?php

namespace App\Services\Entity;

use App\Services\Wp\DTO\Contracts\Post as PostContract;
use App\Models\Post;

class UpdatePost
{
    public static function process(PostContract $postDTO): Post
    {
        /** @var Post $post */
        $post = Post::query()
            ->withoutGlobalScopes()
            ->where('wp_post_id', $postDTO->getId())
            ->get();

        if (!is_null($post)) {
            $post->fill([
                'title' => $postDTO->getTitle(),
                'content' => $postDTO->getContent(),
            ]);
        }

        $post->saveOrFail();

        return $post;
    }
}
