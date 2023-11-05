<?php

namespace App\Services\Wp\DTO;

use App\Services\Wp\DTO\Contracts\Article as ArticleContract;

class Article implements ArticleContract
{
    public function __construct(protected object $data, protected int $version)
    {
    }

    public function __get($name)
    {
        return $this->data->$name ?? null;
    }

    public function __isset($name)
    {
        return isset($this->data->$name);
    }

    public function toArray(): array
    {
        return [
            'id'       => $this->getId(),
            'title'    => $this->getTitle(),
            'content'  => $this->getContent(),
            'version'  => $this->getVersion(),
        ];
    }

    public function getId(): int
    {
        return $this->data->id;
    }

    public function getTitle(): string
    {
        return $this->data->title['rendered'] ?? '';
    }

    public function getContent(): string
    {
        return $this->data->content['rendered'] ?? '';
    }

    public function getVersion(): int
    {
        return $this->version;
    }
}
