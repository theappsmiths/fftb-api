<?php

namespace App\Observers\User;

use Auth;

use App\Modules\Users\Infrastructure\Models\User as UserModel;

use App\Jobs\User\UpdateUserLog;

class User {

    public function updated (UserModel $user) {

        // call queue-job to send Email to user for password reset link
        dispatch (new UpdateUserLog ([
            'userId' => $user->id,
            'updatedBy' => Auth::id() ?? null,
            'changeset' => $user->getDirty(),
            'previousState' => $user->getOriginal(),
            'newState' => $user->getAttributes(),
        ]));
    }
}