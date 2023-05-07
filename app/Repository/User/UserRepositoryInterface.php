<?php

namespace App\Repository\User;

use App\Repository\EloquentRepositoryInterface;

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
}
