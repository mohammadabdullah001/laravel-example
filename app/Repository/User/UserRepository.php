<?php

namespace App\Repository\User;

use App\Models\User;
use App\Service\CommonService;
use App\Repository\BaseRepository;
use App\Repository\User\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    private User $model;
    private CommonService $commonService;

    public function __construct(
        User $model,
        CommonService $commonService
    ) {
        $this->model = $model;
        $this->commonService = $commonService;
    }

    public function all(
        $select,
        $with,
        $orderBy
    ) {
        $data = $this->commonService->all(
            $this->model,
            $select,
            $with,
            $orderBy
        );

        return $data;
    }

    public function index(
        $select,
        $with,
        $orderBy,
        $perPage,
        $search
    ) {
        $data = $this->commonService->index(
            $this->model,
            $select,
            $with,
            $orderBy,
            $perPage,
            $search
        );

        return $data;
    }

    public function show(
        $id,
        $select,
        $with
    ) {
        $data = $this->commonService->show(
            $this->model,
            $id,
            $select,
            $with
        );

        return $data;
    }
}
