<?php

declare(strict_types=1);

use App\Domain\Auth\ValueObjects\UserId;

describe('UserId value object', function (): void {
    it('accepts a valid uuid v4', function (): void {
        $id = new UserId('9f6b3b7e-8d42-4a86-9d0c-c2d1966a87de');

        expect($id->value)->toBe('9f6b3b7e-8d42-4a86-9d0c-c2d1966a87de');
    });

    it('rejects a non uuid value', function (): void {
        expect(fn () => new UserId('not-a-uuid'))->toThrow(InvalidArgumentException::class);
    });

    it('generates uuid v4 values', function (): void {
        expect((string) UserId::generate())->toMatch('/^[0-9a-f-]{36}$/');
    });
});
