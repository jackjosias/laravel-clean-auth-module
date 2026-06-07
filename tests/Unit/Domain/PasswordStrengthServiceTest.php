<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Auth\Exceptions\WeakPasswordException;
use App\Domain\Auth\Services\PasswordStrengthService;
use PHPUnit\Framework\TestCase;

final class PasswordStrengthServiceTest extends TestCase
{
    public function test_scores_a_very_short_password_as_zero(): void
    {
        $this->assertSame(0, $this->service()->score('abc'));
    }

    public function test_scores_a_strong_password_as_four(): void
    {
        $this->assertSame(4, $this->service()->score('Str0ng!Passw0rd#2026'));
    }

    public function test_rejects_a_weak_password(): void
    {
        $this->expectException(WeakPasswordException::class);

        $this->service()->ensureStrong('weak');
    }

    public function test_accepts_a_strong_password(): void
    {
        $this->expectNotToPerformAssertions();

        $this->service()->ensureStrong('Str0ng!Passw0rd#');
    }

    public function test_keeps_labels_synchronized_with_the_javascript_labels(): void
    {
        $service = $this->service();

        $this->assertSame('Tres faible', $service->label(0));
        $this->assertSame('Tres fort', $service->label(4));
    }

    private function service(): PasswordStrengthService
    {
        return new PasswordStrengthService;
    }
}
