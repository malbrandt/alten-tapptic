<?php

namespace App\Http\Controllers\API\REST\Requests;

use App\Models\UserReaction;
use Illuminate\Foundation\Http\FormRequest;

class AddReactionToUserRequest extends FormRequest
{
    public function authorize()
    {
        return true; // no authorization due to task assumptions
    }

    public function rules()
    {
        return [
            'from_user_id' => 'bail|required|int|exists:users,id',
            'to_user_id' => 'bail|required|int|exists:users,id',
            'reaction' => [
                'bail',
                'required',
                'string',
                'in:' . \implode(',', UserReaction::TYPE_REACTIONS[UserReaction::TYPE_SWIPE]),
            ],
        ];
    }
}
