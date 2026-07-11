<?php

namespace App\Http\Controllers\Api\V1\Uam;

use App\Http\Controllers\Controller;
use App\Http\Requests\Uam\StoreUserRequest;
use App\Http\Requests\Uam\UpdateUserRequest;
use App\Http\Resources\Uam\UserResource;
use App\Models\User;
use App\Services\Uam\UserServiceInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct(
        private readonly UserServiceInterface $users,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return UserResource::collection($this->users->getAllUsers());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->users->createUser($request->validated());

        return UserResource::make($user)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): UserResource
    {
        return UserResource::make($this->users->getUserById((string) $user->id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): UserResource
    {
        return UserResource::make($this->users->updateUser($user, $request->validated()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        $this->users->deleteUser($user);

        return ApiResponse::noContent();
    }
}
