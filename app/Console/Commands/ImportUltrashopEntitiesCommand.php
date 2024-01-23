<?php

namespace App\Console\Commands;

use App\Models\Ultrashop\TradeName;
use Arr;
use Http;
use Illuminate\Console\Command;
use Illuminate\Http\Client\PendingRequest;
use Throwable;

class ImportUltrashopEntitiesCommand extends Command
{
    protected $signature = 'import:ultrashop-entities';

    protected $description = 'Import ultrashop entities to cache file';

    public function handle(): void
    {
        $this->importTradeNames();
    }

    private function importTradeNames(): void
    {
        $this->importSimpleDictionary('trade-names', TradeName::class);
    }

    private function importSimpleDictionary(string $url, string $modelClass): void
    {
        $this->info("Start import {$url}");

        try {
            $data = $this->getUltrashopClient()
                ->get("/{$url}")
                ->json();

            $this->info("Fetched {$url}: " . count($data));

            $this->importData($modelClass, $data);
            $this->deleteOldRecords($modelClass, array_column($data, 'id'));
        } catch (Throwable $e) {
            $this->error($e->getMessage());
            report($e);
        }
    }

    private function importData(string $modelClass, array $data): void
    {
        $fields = ['id', 'name'];

        foreach (array_chunk($data, 1000) as $chunkItems) {
            $values = [];
            foreach ($chunkItems as $item) {
                $values[] = Arr::only($item, $fields);
            }

            rescue(
                fn () => $modelClass::upsert($values, ['id'], $fields),
            );
        }

        $this->info("Imported {$modelClass}");
    }

    private function deleteOldRecords(string $modelClass, array $importedIds): void
    {
        rescue(
            fn () => $modelClass::query()->whereKeyNot($importedIds)->delete(),
        );
    }

    private function getUltrashopClient(): PendingRequest
    {
        return Http::baseUrl(config('services.ultrashop.url'))
            ->withHeaders([
                'X-Authorization' => config('services.ultrashop.token'),
            ]);
    }
}
