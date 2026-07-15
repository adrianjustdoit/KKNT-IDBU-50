<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'name',
        'role',
        'division',
        'image_path',
        'hierarchy_level'
    ];

    public static array $roles = [
        'DPL' => 1,
        'Koordes' => 2,
        'Wakoordes' => 3,
        'Sekretaris' => 4,
        'Bendahara' => 5,
        'Kadiv' => 6,
        'Anggota' => 7,
    ];

    public static array $divisions = [
        'Acara',
        'PDD',
        'Perkap',
        'Humas'
    ];

    protected static function booted()
    {
        static::saving(function ($member) {
            $member->hierarchy_level = self::$roles[$member->role] ?? 99;
        });
    }
}
