<?php

namespace App\Services\Wp;

use Illuminate\Support\Collection;
use App\Services\Wp\Contracts\ArticleService as articleServiceContract;
use App\Services\Wp\DTO\Article;
use App\Services\Contracts\ImportService;

class ArticleService implements ArticleServiceContract, importService
{
    private array $cached = [];

    public function articles($params = []): Collection
    {
        return collect($this->connection()->articles($params) ?? [])
            ->except('totalPages')
            ->map(
                function (array $article): Article {
                    return new Article((object) $article);
                }
            )
            ->sortKeys();
    }

    public function articlesPage(int $page): Collection
    {
        return $this->articles(['page' => $page]);
    }

    public function article(int $id): ?Article
    {
        return transform($this->connection()->articleById($id), fn (array $article) => new Article((object) $article, $this->version));
    }

    public function articlesPageCount($perPage = 10): int
    {
        return $this->cached['totalPages'] ??= $this->connection()->articlesTotalPages($perPage);
    }

    public function createArticle($params = []): Article
    {
        $response = $this->connection()->createArticle($params);

        return new Article((object) $response);
    }

    public function deleteArticle(int $id)
    {
        $this->connection()->deleteArticle($id);
    }

    public function connection(): Wp
    {
        return WpConnectionManager::getConnection();
    }
}
