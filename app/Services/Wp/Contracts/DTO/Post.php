<?php

namespace App\Services\Wp\Contracts\DTO;

interface Post
{
    public function getStatus(): bool;
    public function getId(): int;
    public function getTitle(): string;
    public function getCreated(): string;
    public function getContent(): string;
    public function toArray(): array;
}
