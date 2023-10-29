<?php

namespace App\Services\Wp;

use App\Services\Connection\Contracts\Connection;

class Wp implements Connection
{
    use BaseWp;

    private const MID_TIMEOUT = 15;

    public function posts(array $params = []): ?array
    {
        return $this->get('posts', $params, self::MID_TIMEOUT);
    }

    public function postsTotalPages(int $perPage): int
    {
        $posts = $this->posts(['page' => 1, 'per_page' => $perPage]);

        if (!empty($posts)) {
            return (int) $posts['totalPages']['value'];
        }

        return 0;
    }

    public function postById(int $id, array $params = []): ?array
    {
        return $this->get('posts/' . $id, $params, self::MID_TIMEOUT);
    }

    public function createPost($params = []): ?array
    {
        return $this->post('posts', $params);
    }
}
