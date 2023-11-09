<?php

namespace App\Services\Wp;

use App\Services\Entity\UpdateArticle;
use Illuminate\Contracts\Cache\Lock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ArticleManager
{
    protected ?Lock $lock = null;

    public function __construct(private Model $article)
    {
    }

    public function importArticle(): Model
    {
        $lock = Cache::lock('articleimport:article' . $this->article->id, 60);

        if (!$lock->get()) return $this->article;

        $this->lock = $lock;

        $importArticle = articleService()->article($this->article->wp_article_id);

        if (is_null($importArticle)) return $this->article;

        UpdateArticle::process($importArticle);

        return $this->article;
    }
}
