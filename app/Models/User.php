<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property UserReaction[]|Collection $reactions
 */
class User extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'users'; // better performance when table name specified (Laravel don't need to guess it from class name)

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    public function reactions(): HasMany
    {
        return $this->hasMany(UserReaction::class, 'from_user_id', 'id');
    }
}
