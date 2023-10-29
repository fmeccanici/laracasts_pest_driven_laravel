<?php

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;

it('finds missing debug statements', function() {
    // Act & Assert
    expect(['dd', 'dump', 'ray'])->not->toBeUsed();
});

it('does not use validator facade', function() {
    expect(\Illuminate\Support\Facades\Validator::class)
        ->not
        ->toBeUsed()
        ->ignoring([UpdateUserProfileInformation::class, UpdateUserPassword::class, ResetUserPassword::class, CreateNewUser::class]);
});
