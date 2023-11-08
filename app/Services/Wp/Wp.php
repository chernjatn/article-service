<?php

namespace App\Services\Wp;

use App\Services\Connection\Contracts\Connection;

class Wp implements Connection
{
    use BaseWp;

    private const MID_TIMEOUT = 15;

    public function articles(array $params = []): ?array
    {
        return $this->get('posts', $params, self::MID_TIMEOUT);
    }

    public function articlesTotalPages(int $perPage): int
    {
        $posts = $this->articles(['page' => 1, 'per_page' => $perPage]);

        if (!empty($posts)) {
            return (int) $posts['totalPages']['value'];
        }

        return 0;
    }

    public function articleById(int $id, array $params = []): ?array
    {
        return $this->get('posts/' . $id, $params, self::MID_TIMEOUT);
    }

    public function createArticle($params = []): ?array
    {
        return $this->post('posts', $params);
    }

    public function deleteArticle($params = []): ?array
    {
        return $this->post('posts', $params);
    }
}
