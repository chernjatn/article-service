<?php

namespace App\Services\Wp;

use Illuminate\Support\Collection;
use App\Services\Wp\Contracts\PostService as PostServiceContract;
use App\Services\Wp\DTO\Post;
use App\Services\Contracts\ImportService;

class PostService implements PostServiceContract, importService
{
    private array $cached = [];
    private int $version;

    public function setVersion(int $version): self
    {
        $this->version = $version;
        return $this;
    }

    public function posts($params = []): Collection
    {
        return collect($this->connection()->posts($params) ?? [])
            ->except('totalPages')
            ->map(
                function (array $post): Post {
                    return new Post((object) $post, $this->version);
                }
            )
            ->sortKeys();
    }

    public function postsPage(int $page): Collection
    {
        return $this->posts(['page' => $page]);
    }

    public function post(int $id): ?Post
    {
        return transform($this->connection()->postById($id), fn (array $post) => new Post((object) $post, $this->version));
    }

    public function postsPageCount($perPage = 10): int
    {
        return $this->cached['totalPages'] ??= $this->connection()->postsTotalPages($perPage);
    }

    public function connection(): Wp
    {
        return WpConnectionManager::getConnection();
    }
}
