<?php

namespace App\Services\Wp\Contracts;

use Illuminate\Support\Collection;
use App\Services\Wp\DTO\Article as ArticleDTO;

interface ArticleService
{
    /**
     * @return Collection<ArticleDTO>
     */
    public function articles(): Collection;

    /**
     * @param int $page
     * @return Collection<ArticleDTO>
     */
    public function articlesPage(int $page): Collection;

    /**
     * @return int
     */
    public function articlesPageCount(): int;

    /**
     * @param int $id
     * @return ArticleDTO|null
     */
    public function article(int $id): ?ArticleDTO;

}
