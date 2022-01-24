<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\Model\User;
use App\Repository\UserRepository as UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    private User $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function updateModel(User $user, array $data): void
    {
        $user->email = $data['email'] ?? $user->email;
        $user->name = $data['name'] ?? $user->name;
        $user->phone = $data['phone'] ?? $user->phone;
        if (!empty($data['defaultAvatar']))
        {
            $user->avatar = null;
        } else {
            $user->avatar = $data['avatar'] ?? $user->avatar;
        }

        $user->save();
    }

    public function all(): Collection
    {
        return $this->userModel->get();
    }

    public function get(int $id): User
    {
        return $this->userModel->find($id);
    }
}
