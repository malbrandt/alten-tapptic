<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $first_user_id
 * @property int $second_user_id
 */
class UserMatch extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'first_user_id' => 'int',
        'second_user_id' => 'int',
    ];
    protected $fillable = [
        'first_user_id',
        'second_user_id',
    ];
    protected $table = 'user_matches';
}
