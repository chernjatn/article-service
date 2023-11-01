<?php

namespace App\Services\Wp\DTO;

use App\Services\Wp\DTO\Contracts\Post as PostContract;

class Post implements PostContract
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
            'status'   => $this->getStatus(),
            'created'  => $this->getCreated(),
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

    public function getStatus(): bool
    {
        return $this->data->status == 'publish';
    }

    public function getCreated(): string
    {
        return $this->data->date;
    }

    public function getVersion(): int
    {
        return $this->version;
    }
}
