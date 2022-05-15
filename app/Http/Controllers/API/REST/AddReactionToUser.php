<?php

namespace App\Http\Controllers\API\REST;

use App\Http\Controllers\API\REST\Requests\AddReactionToUserRequest;
use App\Http\Controllers\Controller;
use App\Models\UserReaction;
use App\Services\UserReactionService;
use Symfony\Component\HttpFoundation\Response;

class AddReactionToUser extends Controller
{
    private UserReactionService $userReactionService;

    public function __construct(UserReactionService $userReactionService)
    {
        $this->userReactionService = $userReactionService;
    }

    public function __invoke(AddReactionToUserRequest $request)
    {
         $reaction = $this->userReactionService->add(
            $request->get('from_user_id'),
            $request->get('to_user_id'),
            UserReaction::TYPE_SWIPE,
            $request->get('reaction', UserReaction::REACTION_SWIPE_LIKE),
        );

        return (new \App\Http\Resources\UserReaction($reaction))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
