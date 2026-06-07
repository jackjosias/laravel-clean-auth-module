<?php

declare(strict_types=1);

namespace App\Presentation\Http\Resources;

use App\Domain\Auth\Entities\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AuthUserResource extends JsonResource
{
    /**
     * @return array{id: string, email: string, name: string, created_at: string}
     */
    public function toArray(Request $request): array
    {
        /** @var User $user */
        $user = $this->resource;

        return [
            'id' => $user->getId()->value,
            'email' => $user->getEmail()->value,
            'name' => $user->getName(),
            'created_at' => $user->getCreatedAt()->format(DATE_ATOM),
        ];
    }
}
