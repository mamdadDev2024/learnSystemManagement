<?php

namespace Modules\User\Services;

use App\Contracts\BaseService;
use Modules\User\Actions\DeleteUserAcion;
use Modules\User\Actions\UpdateUserAcion;
use Modules\User\Models\User;

class UserService extends BaseService
{
    public function __construct(
        private UpdateUserAcion $UpdateAction,
        private DeleteUserAcion $deleteAction
    ) {}

    public function delete(User $user) {
        return $this->execute(function () use ($user){
            $this->deleteAction->handle($user);
        } , 'delete user failed');
    }

    public function update(User $user , array $data)
    {
        return $this->execute(function () use ($user , $data){
            $newUser = $this->UpdateAction->handle($user , $data);
            if (!is_null($newUser))
                event(new )
        });
    }
}
