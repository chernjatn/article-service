<?php

namespace App\Services\Wp\DTO\Contracts;

interface Article
{
    public function getId(): int;
    public function getTitle(): string;
    public function getContent(): string;
    public function getExcerpt(): string;
    public function toArray(): array;
}
