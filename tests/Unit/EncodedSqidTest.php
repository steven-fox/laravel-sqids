<?php

use StevenFox\LaravelSqids\Sqids\DecodedSqid;
use StevenFox\LaravelSqids\Sqids\EncodedSqid;

it('can be instantiated via the new function and no config name', function () {
    $sqid = EncodedSqid::new('Uk');

    expect($sqid)
        ->toBeInstanceOf(EncodedSqid::class)
        ->and($sqid->id())->toBe('Uk');
});

it('can decode using the default id configuration', function () {
    $sqid = new EncodedSqid(
        'Uk',
        new \Sqids\Sqids(),
        new \StevenFox\LaravelSqids\Sqidder(
            new \StevenFox\LaravelSqids\ConfigRepository(config()),
            new \StevenFox\LaravelSqids\CoderFactory(),
        )
    );

    $decoded = $sqid->decode();

    expect($decoded)->toBeInstanceOf(DecodedSqid::class)
        ->and($decoded->numbers())->toEqual([1]);
});

it('can decode using a specific id configuration', function () {
    $sqid = EncodedSqid::new('Ko', 'other');

    $decoded = $sqid->decode();

    expect($decoded)->toBeInstanceOf(DecodedSqid::class)
        ->and($decoded->numbers())->toEqual([1]);
});
