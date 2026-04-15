<?php

use App\Eloquent\User\EloquentUserRepository;

it('normalizes supported year_level values into canonical labels', function (): void {
    $repository = (new ReflectionClass(EloquentUserRepository::class))->newInstanceWithoutConstructor();
    $normalizeYearLevelLabel = \Closure::bind(
        fn (mixed $value): ?string => $this->normalizeYearLevelLabel($value),
        $repository,
        EloquentUserRepository::class
    );

    expect($normalizeYearLevelLabel('1st Year'))->toBe('1st Year');
    expect($normalizeYearLevelLabel('2nd year'))->toBe('2nd Year');
    expect($normalizeYearLevelLabel('3'))->toBe('3rd Year');
    expect($normalizeYearLevelLabel('Year 4'))->toBe('4th Year');
});

it('rejects unsupported year_level values', function (): void {
    $repository = (new ReflectionClass(EloquentUserRepository::class))->newInstanceWithoutConstructor();
    $normalizeYearLevelLabel = \Closure::bind(
        fn (mixed $value): ?string => $this->normalizeYearLevelLabel($value),
        $repository,
        EloquentUserRepository::class
    );

    expect($normalizeYearLevelLabel('2004th Year'))->toBeNull();
    expect($normalizeYearLevelLabel('fifth year'))->toBeNull();
    expect($normalizeYearLevelLabel(null))->toBeNull();
});
