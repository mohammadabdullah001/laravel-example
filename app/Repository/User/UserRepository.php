<?php

namespace App\Repository\User;

use App\Models\User;
use App\Service\CommonService;
use App\Repository\Base\BaseRepository;
use App\Repository\User\Interfaces\UserRepositoryInterface;

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

    public function store(
        $request
    ) {
        $data = $this->commonService->store(
            $this->model,
            $request
        );

        return $data;
    }


    public function update(
        $id,
        $request
    ) {
        $data = $this->commonService->update(
            $this->model,
            $id,
            $request
        );

        return $data;
    }

    public function destroy(
        $id
    ) {
        $data = $this->commonService->destroy(
            $this->model,
            $id
        );

        return $data;
    }
}
