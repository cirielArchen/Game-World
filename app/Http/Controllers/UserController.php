<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Gate;
use app\Policies\UserPolicy;

class UserController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function list(Request $request)
    {
        /*
        if (!Gate::allows('admin-level', false)) {
            abort(403);
        }
        */

        // Gate::authorize('admin-level');

        $users = $this->userRepository->all();

        return view('user.list', ['users' => $users]);
    }

    public function show(int $userId)
    {
        Gate::authorize('admin-level');

        $user = $this->userRepository->get($userId);

        Gate::authorize('view', $user);

        return view('user.show', [
            'user' => $user,
        ]);
    }
}
