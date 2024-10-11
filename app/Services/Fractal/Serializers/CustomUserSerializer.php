<?php

namespace App\Services\Fractal\Serializers;

use League\Fractal\Serializer\DataArraySerializer;

class CustomUserSerializer extends DataArraySerializer
{
    /**
     * Additional metadata that will be included in the response.
     *
     * @var array
     */
    protected array $additional = [];

    /**
     * CustomUserSerializer constructor.
     *
     * @param array|null $additional
     */
    public function __construct(?array $additional = [])
    {
        $this->additional = $additional;
    }

    /**
     * {@inheritDoc}
     */
    public function collection(?string $resourceKey, array $data): array
    {
        return [
            ...$this->additional,  // Include additional metadata
            'users' => $data
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function item(?string $resourceKey, array $data): array
    {
        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function null(): ?array
    {
        return ['data' => []];
    }
}
