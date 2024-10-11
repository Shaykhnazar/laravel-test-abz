<?php

namespace App\Services;

use App\Services\Fractal\Serializers\CustomUserSerializer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use League\Fractal\TransformerAbstract;
use Spatie\Fractal\Fractal;

class Responder
{
    protected ?TransformerAbstract $transformer = null;

    protected ?Builder $builder = null;

    protected ?int $perPage = null;

    /**
     * @param Builder $builder
     * @param TransformerAbstract $transformer
     * @return Responder
     */
    public function __invoke(Builder $builder, TransformerAbstract $transformer): static
    {
        $this->builder = $builder;
        $this->transformer = $transformer;

        return $this;
    }

    /**
     * Use pagination.
     * If nothing passed in $perPage, then ignoring this method call.
     *
     * @return $this
     */
    public function paginate(?int $perPage): static
    {
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * Edge load relations.
     *
     * @param string|null $includes
     * @return Responder
     */
    public function load(?string $includes): static
    {
        $relations = explode(',', $includes);
        foreach ($relations as $relation) {
            if ($this->builder->getModel()->isRelation($relation)) {
                $this->builder->with($relation);
            }
        }

        return $this;
    }

    /**
     * Create Fractal instance with loaded data
     */
    public function send(): \Spatie\Fractalistic\Fractal
    {
        $models = $this->builder->get();

        return Fractal::create($models, $this->transformer);
    }

    /**
     * Create Fractal instance with loaded data
     */
    public function sendModelByFind($id): \Spatie\Fractalistic\Fractal
    {
        $model = $this->builder->find($id);

        return Fractal::create($model, $this->transformer)
            ->serializeWith(new CustomUserSerializer());
    }

    /**
     * Отдать собственную коллекцию.
     */
    public function sendCustomUsers(): \Spatie\Fractalistic\Fractal|Collection
    {
        $paginatedData = $this->perPage
            ? $this->builder->paginate($this->perPage)
            : $this->builder->get();

        // Check if requested page exceeds total number of pages
        if ($this->perPage && $paginatedData->currentPage() > $paginatedData->lastPage()) {
            // Return the response for page not found
            return collect([
                'success' => false,
                'message' => 'Page not found',
            ]);
        }

        // If pagination is enabled, extract pagination details
        if ($this->perPage) {
            $meta = [
                'success'      => true,
                'page'         => $paginatedData->currentPage(),
                'total_pages'  => $paginatedData->lastPage(),
                'total_users'  => $paginatedData->total(),
                'count'        => $paginatedData->count(),
                'links'        => [
                    'next_url' => $paginatedData->nextPageUrl(),
                    'prev_url' => $paginatedData->previousPageUrl(),
                ]
            ];

            $models = $paginatedData->items();
        } else {
            $meta = ['total' => $this->builder->count()];
            $models = $paginatedData;
        }

        // Return the response with the formatted data and metadata
        return Fractal::create($models, $this->transformer)
            ->serializeWith(new CustomUserSerializer($meta));
    }
}
