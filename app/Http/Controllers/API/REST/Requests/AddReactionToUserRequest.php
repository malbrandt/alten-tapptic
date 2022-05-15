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
                // Prevent from adding reaction of same type between same two users
                function ($attribute, $value, $fail) {
                    $alreadyReacted = UserReaction::query()
                        ->where('from_user_id', $this->request->get('from_user_id'))
                        ->where('to_user_id', $this->request->get('to_user_id'))
                        ->where('type', UserReaction::TYPE_SWIPE)
                        ->exists();

                    if ($alreadyReacted) {
                        $fail('Reactions to users cannot be changed.');
                    }
                },
            ],
        ];
    }
}
