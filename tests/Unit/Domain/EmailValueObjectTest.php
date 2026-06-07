<?php

declare(strict_types=1);

use App\Domain\Auth\Exceptions\InvalidEmailException;
use App\Domain\Auth\ValueObjects\Email;

describe('Email value object', function (): void {
    it('accepts a valid email', function (): void {
        $email = new Email('user@example.com');

        expect($email->value)->toBe('user@example.com');
    });

    it('normalizes an email to lowercase', function (): void {
        $email = new Email('USER@EXAMPLE.COM');

        expect($email->value)->toBe('user@example.com');
    });

    it('rejects an invalid email', function (): void {
        expect(fn () => new Email('not-an-email'))->toThrow(InvalidEmailException::class);
    });

    it('compares email equality by value', function (): void {
        expect((new Email('a@b.com'))->equals(new Email('a@b.com')))->toBeTrue();
    });
});
