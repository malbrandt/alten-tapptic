<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $from_user_id
 * @property int $to_user_id
 * @property string $type
 * @property string $reaction
 *
 * @method static UserReaction likes()
 * @method static UserReaction dislikes()
 */
class UserReaction extends Model
{
    use HasFactory;

    public const TYPE_SWIPE = 'swipe';
    public const TYPES = [
        self::TYPE_SWIPE,
    ];

    public const REACTION_SWIPE_LIKE = 'like';
    public const REACTION_SWIPE_DISLIKE = 'dislike';
    public const REACTIONS_SWIPE = [
        self::REACTION_SWIPE_LIKE,
        self::REACTION_SWIPE_DISLIKE,
    ];

    public const TYPE_REACTIONS = [
        self::TYPE_SWIPE => self::REACTIONS_SWIPE,
    ];

    public $timestamps = false;

    protected $casts = [
        'from_user_id' => 'int',
        'to_user_id' => 'int',
    ];
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'type',
        'reaction',
    ];
    protected $table = 'user_reactions';

    public function scopeSwipes(Builder $query)
    {
        return $query->where('type', self::TYPE_SWIPE);
    }

    public function setTypeAttribute(string $type): void
    {
        if (! \in_array($type, self::TYPES, true)) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'Invalid type: "%s". Use on of following: %s.',
                    $type, \implode(', ', self::TYPES)
                )
            );
        }

        $this->attributes['type'] = $type;
    }

    public function setReactionAttribute(string $reaction): void
    {
        if (empty($this->attributes['type'])) {
            throw new \LogicException('You need to set the "type" attribute before setting reaction.');
        }

        if (! \in_array($reaction, self::TYPE_REACTIONS[$this->attributes['type']], true)) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'Invalid reaction: "%s" for type "%s". Use on of following: %s.',
                    $reaction, $this->attributes['type'],
                    \implode(', ', self::TYPE_REACTIONS[$this->attributes['type']])
                )
            );
        }

        $this->attributes['reaction'] = $reaction;
    }

    public function scopeLikes($query)
    {
        return $query
            ->where('type', self::TYPE_SWIPE)
            ->where('reaction', self::REACTION_SWIPE_LIKE);
    }

    public function scopeDislikes($query)
    {
        return $query
            ->where('type', self::TYPE_SWIPE)
            ->where('reaction', self::REACTION_SWIPE_DISLIKE);
    }
}
