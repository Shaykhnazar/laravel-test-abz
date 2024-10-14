<?php

namespace App\Services\Fractal\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     */
    public function transform(User $user): array
    {
        return [
            'id'                    => $user->id,
            'name'                  => $user->name,
            'email'                 => $user->email,
            'phone'                 => $user->phone,
            'position'              => $user->position->name ?? 'N/A',
            'position_id'           => $user->position_id,
            'registration_timestamp' => $user->created_at->timestamp,
            'photo'                  => $user->photo ?? null,
        ];
    }
}
