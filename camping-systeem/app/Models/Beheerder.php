<?php

namespace App\Models;

class Beheerder
{
    protected string $table = 'inloggegevens';

    protected array $fillable = [
        'e-mailadres',
        'wachtwoord',
    ];

    public static function create(array $array)
    {
    }
}
