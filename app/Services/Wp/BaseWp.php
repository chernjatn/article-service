<?php

namespace App\Services\Wp;

use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

trait BaseWp
{
    private PendingRequest $client;

    public function __construct(array $config)
    {
        $this->client = Http::baseUrl($config['url'])
            ->withBasicAuth($config['login'], $config['password'])
            ->retry(2, 200, function (Exception $exception) {
                return $exception instanceof ConnectionException;
            })
            ->withHeaders([
                'cache-control' => 'no-cache',
                'connection' => 'keep-alive'
            ]);
    }

    public function get(string $page, array $params = [], int $timeout = 300): ?array
    {
        try {
            $response = $this->client->timeout($timeout)->get($page, $params);

            $totalPages = $response->header('X-WP-TotalPages');

            $content = $response->json();

            if (!is_null($content) && !is_null($totalPages)) {
                $content['totalPages']['value'] = $totalPages;
            }

            return $content ?? null;
        } catch (\Throwable $e) {
            report($e);
        }

        return null;
    }

    public function post(string $page, array $params = []): ?array
    {
        $response = $this->client->post($page, $params);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function delete(int $id): ?array
    {
        $response = $this->client->delete($id);

        return json_decode($response->getBody()->getContents(), true);
    }
}
