<?php

namespace Vanguard\Http\Controllers\Api\Users;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Vanguard\Jobs\SendEmailJob;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Vanguard\Events\User\Banned;
use Vanguard\Events\User\Deleted;
use Vanguard\Events\User\UpdatedByAdmin;
use Vanguard\Http\Controllers\Api\ApiController;
use Vanguard\Http\Filters\UserKeywordSearch;
use Vanguard\Http\Requests\User\CreateUserRequest;
use Vanguard\Http\Requests\User\UpdateUserRequest;
use Vanguard\Http\Resources\UserResource;
use Vanguard\Repositories\User\UserRepository;
use Vanguard\Support\Enum\UserStatus;
use Vanguard\User;

class UsersController extends ApiController
{
    public function __construct(private UserRepository $users)
    {
        $this->middleware('permission:users.manage');
    }

    /**
     * Paginate all users.
     */
    public function index(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $users = QueryBuilder::for(User::class)
            ->allowedIncludes(UserResource::allowedIncludes())
            ->allowedFilters([
                AllowedFilter::custom('search', new UserKeywordSearch),
                AllowedFilter::exact('status'),
            ])
            ->allowedSorts(['id', 'first_name', 'last_name', 'email', 'created_at', 'updated_at'])
            ->defaultSort('id')
            ->paginate($request->per_page ?: 20);

        return UserResource::collection($users);
    }

    // public function store(CreateUserRequest $request): UserResource
    // {
    //     $data = $request->only([
    //         'email', 'password', 'username', 'first_name', 'last_name',
    //         'phone', 'address', 'country_id', 'birthday', 'role_id',
    //     ]);

    //     $data += [
    //         'status' => UserStatus::ACTIVE,
    //         'email_verified_at' => $request->verified ? now() : null,
    //     ];

    //     $user = $this->users->create($data);

    //     return new UserResource($user);
    // }


public function store(CreateUserRequest $request): UserResource
{
    $data = $request->only([
        'email', 'password', 'username', 'first_name', 'last_name',
        'phone', 'address', 'country_id', 'birthday', 'role_id',
    ]);

    // Handle password (in case it's sent hashed or plain)
    if ($request->filled('password')) {
        $plainPassword = $request->password;
        $data['password'] = Hash::make($request->password);
    } else {
        // Generate random password if not provided
        $plainPassword = Str::random(12);
        $data['password'] = Hash::make($plainPassword);
    }

    $data += [
        'status' => UserStatus::ACTIVE,
        'email_verified_at' => $request->boolean('verified', true) ? now() : null,
    ];

    $user = $this->users->create($data);

    // Fire Laravel Registered event (for any listeners)
    event(new Registered($user));

    // Send Welcome Email with login credentials
    $this->sendApiCreatedUserWelcomeEmail($user, $plainPassword);

    return new UserResource($user->load('role'));
}

    public function show($id): UserResource
    {
        $user = QueryBuilder::for(User::where('id', $id))
            ->allowedIncludes(UserResource::allowedIncludes())
            ->firstOrFail();

        return new UserResource($user);
    }

    public function update(User $user, UpdateUserRequest $request): UserResource
    {
        $data = $request->only([
            'email', 'password', 'username', 'first_name', 'last_name',
            'phone', 'address', 'country_id', 'birthday', 'status', 'role_id',
        ]);

        $user = $this->users->update($user->id, $data);

        event(new UpdatedByAdmin($user));

        // If user status was updated to "Banned",
        // fire the appropriate event.
        if ($this->userIsBanned($user, $request)) {
            event(new Banned($user));
        }

        return new UserResource($user);
    }

    /**
     * Check if user is banned during last update.
     */
    private function userIsBanned(User $user, Request $request): bool
    {
        return $user->status != $request->status && $request->status == UserStatus::BANNED;
    }

    public function destroy(User $user): \Illuminate\Http\JsonResponse
    {
        if ($user->id == auth()->id()) {
            return $this->errorForbidden(__('You cannot delete yourself.'));
        }

        event(new Deleted($user));

        $this->users->delete($user->id);

        return $this->respondWithSuccess();
    }
    private function sendApiCreatedUserWelcomeEmail($user, $plainPassword)
{
    $subject = "Welcome to " . config('app.name') . " – Your Account is Ready";

    $message = view('emails.welcome_new_user', [
        'user' => $user,
        'password' => $plainPassword,
        'loginUrl' => config('app.frontend_url', url('/login')), // e.g. your mobile/web app login
    ])->render();

    dispatch(new SendEmailJob([
        'recipient'  => $user->email,
        'subject'    => $subject,
        'message'    => $message,
        'user_id'    => $user->id,
        'category'   => 'api_user_creation',
        'status'     => Email::STATUS_PENDING,
    ]));
}
}
