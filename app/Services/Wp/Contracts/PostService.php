<?php

namespace App\Services\Wp\Contracts;

use Illuminate\Support\Collection;
use App\Services\Wp\DTO\Post as PostDTO;

interface PostService
{
    /**
     * @return Collection<PostDTO>
     */
    public function posts(): Collection;

    /**
     * @param int $page
     * @return Collection<PostDTO>
     */
    public function postsPage(int $page): Collection;

    /**
     * @return int
     */
    public function postsPageCount(): int;


    /**
     * @param int $id
     * @return PostDTO|null
     */
    public function post(int $id): ?PostDTO;

}
