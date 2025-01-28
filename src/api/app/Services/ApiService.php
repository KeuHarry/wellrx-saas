<?php

namespace App\Services;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ApiService
{
    public function __construct(private BaseRepository $baseRepository)
    {
        $this->baseRepository = $baseRepository;
    }

    public function updateKey(array $inputs): array
    {
        $newInputArray = [];
        foreach ($inputs as $key => $value) {
            $newKey = '';
            if (preg_match('/[A-Z]/', $key)) {
                $keyArray = str_split($key);

                foreach ($keyArray as $char) {
                    if (preg_match('/[A-Z]/', $char)) {
                        $newKey = $newKey.'_'.strtolower($char);
                    } else {
                        $newKey .= $char;
                    }
                }
            } else {
                $newKey = $key;
            }

            $newInputArray[$newKey] = $value;
        }

        return $newInputArray;
    }

    public function getModelCount(): int
    {
        return $this->baseRepository->getModelCount();
    }

    public function create(array $content): Model
    {
        $inputs = $this->updateKey($content);
        return $this->baseRepository->create($inputs);
    }

    /**
     * @param mixed[][] $contents
     */
    public function bulkCreate(array $contents): bool
    {
        $inputs = []; 
        foreach ($contents as $content) {
            $input = $this->updateKey($content);
            $inputs[] = $input;
        }
        return $this->baseRepository->bulkCreate($inputs);
    }

    public function getById(int $id): Model
    {
        return $this->baseRepository->findById($id);
    }

    public function getAll(): Collection
    {
        return $this->baseRepository->findAll();
    }

    public function delete(int $id): int
    {
        return $this->baseRepository->delete($id);
    }

    public function update(string $id, array $content): int
    {
        $inputs = $this->updateKey($content);
        return $this->baseRepository->update($id, $inputs);
    }

    // public function search(
    //     ?string $search = null,
    //     ?string $sortBy = null,
    //     ?string $sortOrder = null,
    //     int $offset = 0,
    //     int $limit = 10
    // ): Collection
    // {

    //     $shelves = Shelf::where('name', 'LIKE', '%'.$search.'%')
    //             ->offset($offset)
    //             ->limit($limit)
    //             ->orderBy($sortBy, $sortOrder)
    //             ->get();

    //     if (! assert($shelves instanceof Collection)) {
    //         throw new InternalServerErrorException;
    //     }

    //     return $shelves;
    // }
}
