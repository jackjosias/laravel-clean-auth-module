<?php

declare(strict_types=1);

namespace App\Domain\Auth\Services;

use App\Domain\Auth\Exceptions\WeakPasswordException;

final class PasswordStrengthService
{
    private const int MIN_LENGTH = 10;

    private const int STRONG_LENGTH = 14;

    public function score(string $password): int
    {
        $score = 0;
        $score += strlen($password) >= self::MIN_LENGTH ? 1 : 0;
        $score += strlen($password) >= self::STRONG_LENGTH ? 1 : 0;
        $score += preg_match('/[A-Z]/', $password) && preg_match('/[a-z]/', $password) ? 1 : 0;
        $score += preg_match('/[0-9]/', $password) ? 1 : 0;
        $score += preg_match('/[\W_]/', $password) ? 1 : 0;

        return min($score, 4);
    }

    public function ensureStrong(string $password): void
    {
        $score = $this->score($password);

        if ($score < 3) {
            throw WeakPasswordException::withScore($score);
        }
    }

    public function label(int $score): string
    {
        return match ($score) {
            0 => 'Tres faible',
            1 => 'Faible',
            2 => 'Moyen',
            3 => 'Fort',
            4 => 'Tres fort',
            default => 'Inconnu',
        };
    }
}
