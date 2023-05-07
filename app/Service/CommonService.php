<?php

namespace App\Service;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CommonService
{

    public function all(
        $model,
        $select,
        $with,
        $orderBy
    ): Collection {
        try {
            $data = $model
                ->query()
                ->select($select)
                ->with($with)
                ->orderBy('id', $orderBy)
                ->get();

            return $data;
        } catch (\Throwable $th) {
            info($th);
            abort(404, 'Not Found');
        }
    }

    public function index(
        $model,
        $select,
        $with,
        $orderBy,
        $perPage,
        $search
    ): LengthAwarePaginator {
        try {
            $data = $model
                ->query()
                ->select($select)
                ->with($with);


            if ($search) {
                $data = $data
                    ->Search($search);
            }

            $data = $data
                ->orderBy('id', $orderBy)
                ->paginate($perPage);

            return $data;
        } catch (\Throwable $th) {
            info($th);
            abort(404, 'Not Found');
        }
    }

    public function show(
        $model,
        $id,
        $select,
        $with,
    ): Model {
        try {
            $data = $model
                ->query()
                ->select($select)
                ->with($with)
                ->findOrFail($id);

            return $data;
        } catch (\Throwable $th) {
            info($th);
            abort(404, 'Not Found');
        }
    }
}
