<?php

namespace App\Services\Wp;

use Exception;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
//use Ultra\Shop\Services\ExternalApi\Tracker\ExternalApiService;
//use Ultra\Shop\Services\ExternalApi\Tracker\Middleware\GuzzleMiddleware as TrackMiddleware;

trait BaseWp
{
    private PendingRequest $client;

    public function __construct(string $baseUrl)
    {
        $this->client = Http::baseUrl($baseUrl)
            ->withMiddleware(new TrackMiddleware(ExternalApiService::WP_POST))
            ->retry(2, 200, function (Exception $exception) {
                return $exception instanceof ConnectionException;
            })
            ->withHeaders([
                'cache-control' => 'no-cache',
                'connection' => 'keep-alive'
            ])
            ->acceptJson();
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

    public function post(string $page, array $params = [], int $timeout = 300): ?array
    {
        $response = $this->client->post($page, [
            RequestOptions::JSON    => $params,
            RequestOptions::TIMEOUT => $timeout,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
