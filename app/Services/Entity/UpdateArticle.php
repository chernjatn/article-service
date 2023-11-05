<?php

namespace App\Services\Entity;

use App\Models\Article;
use App\Services\Wp\DTO\Article as ArticleDTO;

class UpdateArticle
{
    public static function process(ArticleDTO $articleDTO)
    {
        Article::query()->where('wp_article_id', $articleDTO->getId())
            ->update(['content' => $articleDTO->getContent()]);
    }
}
