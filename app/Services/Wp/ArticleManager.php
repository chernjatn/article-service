<?php

namespace App\Services\Wp;

use App\Services\Entity\UpdateArticle;
use Illuminate\Contracts\Cache\Lock;
use Illuminate\Support\Facades\Cache;
use App\Models\Article;

class ArticleManager
{
    protected ?Lock $lock = null;

    public function __construct(private Article $article)
    {
    }

    public function importArticle(): void
    {
        $lock = Cache::lock('articleimport:article' . $this->article->getId(), 60);

        if (!$lock->get()) return;

        $this->lock = $lock;

        $importArticle = articleService()->article($this->article->getId());

        if (is_null($importArticle)) {
            return;
        }

        UpdateArticle::process($importArticle);
    }
}
