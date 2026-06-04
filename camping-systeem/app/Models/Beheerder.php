<?php

namespace App\Models;

class Beheerder
{
    protected string $table = 'inloggegevens';

    protected array $fillable = [
        'email',
        'password',
    ];

    public static function create(array $array)
    {
    }
}
