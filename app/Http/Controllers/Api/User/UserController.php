<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Repository\User\Interfaces\UserRepositoryInterface;

class UserController extends Controller
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function user(): JsonResource
    {
        $data = auth()->user();

        return new UserResource($data);
    }

    public function all(Request $request): ResourceCollection
    {
        $select = explode(
            ',',
            $request->query('select', 'id')
        );
        $with = $request->query('with', []);
        $orderBy = $request->query('orderBy', 'DESC');

        $data = $this->repository->all(
            $select,
            $with,
            $orderBy
        );

        return UserResource::collection($data);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): ResourceCollection
    {
        $select = explode(
            ',',
            $request->query('select', 'id')
        );
        $with = $request->query('with', []);
        $orderBy = $request->query('orderBy', 'DESC');
        $perPage = $request->query('perPage', 15);
        $search = $request->query('search');

        $data = $this->repository->index(
            $select,
            $with,
            $orderBy,
            $perPage,
            $search
        );

        return UserResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->repository->store($request);

        return new UserResource($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id): JsonResource
    {
        $select = explode(
            ',',
            $request->query('select', 'id')
        );
        $with = $request->query('with', []);

        $data = $this->repository->show(
            $id,
            $select,
            $with
        );

        return new UserResource($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {

        $data = $this->repository->update($id, $request);

        return new UserResource($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->repository->destroy($id);

        return response()->noContent();
    }
}
