<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseRepository
{
    public Model $model;

    public function __construct()
    {
        //$this->model = $model;
    }

    public function getModelCount(): int
    {
        return $this->model::count();
    }

    public function findAll(): Collection
    {
        return $this->model::all();
    }

    public function create(array $content): Model
    {
        $resource = $this->model::create($content);

        return $resource;
    }

    public function bulkCreate(array $contents): bool
    {
        $result = DB::transaction(function () use ($contents) {
            foreach ($contents as $content) {
                $this->create($content);
            }

            return true;
        });

        return boolval($result);
    }


    public function update(int $id, array $content): int
    {
        $resource = $this->findById($id);
        $resource->update($content);

        return $resource->id;
    }

    public function findById(int $id): Model
    {
        return $this->model::findOrfail($id);
    }

    public function delete(int $id): int
    {
        return $this->model::destroy($id);
    }
}
