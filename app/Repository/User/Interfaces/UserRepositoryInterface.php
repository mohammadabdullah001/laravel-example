<?php

namespace App\Repository\User\Interfaces;

use App\Repository\Base\Interfaces\EloquentRepositoryInterface;



interface UserRepositoryInterface extends EloquentRepositoryInterface
{
    public function all(
        $select,
        $with,
        $orderBy
    );
    public function index(
        $select,
        $with,
        $orderBy,
        $perPage,
        $search
    );
    public function show(
        $id,
        $select,
        $with
    );
    public function store(
        $request
    );
    public function update(
        $id,
        $request
    );
    public function destroy(
        $id
    );
}
