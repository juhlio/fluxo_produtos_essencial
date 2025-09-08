<?php

namespace App\Events;

use App\Models\User;

class UserRolesChanged
{
    public function __construct(
        public ?User $actor,   // quem executou (pode ser null)
        public User $user,     // usuário alvo
        public array $before,  // papéis antes
        public array $after    // papéis depois
    ) {}
}
