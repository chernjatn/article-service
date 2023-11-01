<?php

namespace App\Abstracts;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Contracts\ImportService;

abstract class ImportJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected ImportService $importService,
        protected int           $version,
        protected int           $page = 0
    ) {
        $this->queue = static::getQueueName();
    }

    protected function beforeHandle()
    {
        $this->importService->setVersion($this->version);
    }

    abstract public static function getQueueName(): string;
}
