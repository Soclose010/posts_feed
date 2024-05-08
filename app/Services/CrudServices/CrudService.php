<?php

namespace App\Services\CrudServices;

use App\DataTransferObjects\Dto;
use App\Services\Action\ActionService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

abstract class CrudService
{
    public function __construct(
        protected readonly string $model,
        protected readonly string $dto)
    {
    }

    public function create(Dto $dto): Dto
    {
        $model = $this->model::create($this->fieldsForOperation($dto));
        ActionService::write(
            Auth::id() ?? null,
            $this->getOwner($model),
            "create",
            null,
            $model
        );
        return $this->dto::fromModel($model);
    }

    public function update(Dto $dto): Dto
    {
        $model = $this->getModel($dto->id);
        $oldModel = clone $model;
        $model = tap($model->fill($this->fieldsForOperation($dto)))->save();
        ActionService::write(
            Auth::id(),
            $this->getOwner($model),
            "update",
            $oldModel,
            $model
        );
        return $this->dto::fromModel($model);
    }

    public function delete(string $id): void
    {
        $model = $this->getModel($id);
        $modelOwner = $this->getOwner($model);
        $model->delete();
        ActionService::write(
            Auth::id(),
            $modelOwner,
            "delete",
            $model,
            null
        );
    }

    public function get(string $id): ?Dto
    {
        if ($model = $this->model::where("id", $id)->first()) {
            return $this->dto::fromModel($model);
        }
        return null;
    }

    abstract public function getOwner(Model $model): string;

    public function getModel(string $id): Model
    {
        return $this->model::find($id);
    }

    public function all(): Collection
    {
        return $this->model::all()->collect()->map(function ($item) {
            return $this->dto::fromModel($item);
        });
    }

    protected function fieldsForOperation(Dto $dto): array
    {
        return array_filter($dto->toArray(), function ($value) {
            return $value !== null;
        });
    }
}
