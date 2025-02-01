<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Users\StoreUserRequest;
use App\Http\Requests\Dashboard\Users\UpdateUserRequest;
use App\Models\User;
use App\Services\StorageService;
use App\Traits\ApiResponseTrait;
use App\Pipelines\Filters\UserFilter;
use App\Services\AuthService;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private AuthService $authService) {}
    /**
     * get all the users
     *
     */
    public function index()
    {
        $users = app(Pipeline::class)
            ->send(User::query())
            ->through([
                UserFilter::class,
            ])
            ->thenReturn()
            ->paginate(10);
        return view('users.index', compact('users'));
    }
    /**
     * view the store user form
     *
     */
    public function create()
    {
        return view('users.create');
    }
    /**
     * Add new User
     */
    public function store(StoreUserRequest $request)
    {
        // get data from request
        $userData = $request->validated();
        // handle the profile image & phone verification data
        $userData = $this->authService->handleStoreUserData($request, $userData);
        // create new user
        User::create($userData);
        return ApiResponseTrait::successResponse([], __('messages.added'));
    }

    /**
     * return update user form
     * @param mixed $userId
     */
    public function edit($userId)
    {
        $user = User::findOrFail($userId);
        return view('users.edit', compact('user'));
    }

    /**
     * update user data
     * @param UpdateUserRequest $request
     * @param User $user
     */

    public function update(UpdateUserRequest $request, User $user)
    {
        // get data from request
        $userData = $request->validated();
        // handle update user data
        $userData = $this->authService->handleUpdateUserData($request, $userData, $user);
        $user->update($userData);
        return ApiResponseTrait::successResponse([], __('messages.updated'));
    }

    /**
     * delete user
     */
    public function destroy(User $user)
    {
        // delete the user image
        if (!empty($user->profile_image)) {
            StorageService::deleteImage($user->profile_image);
        }
        if (!empty($user->thumbnail_image)) {
            StorageService::deleteImage($user->thumbnail_image);
        }
        $user->delete();
        return back()->with('Success', __('messages.deleted'));
    }
}
