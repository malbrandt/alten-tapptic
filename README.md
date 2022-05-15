# Tapptic - Backend Assessment - task #2

## Introduction

This repository contains solution for task #2 (app that allows for liking/disliking other users and creation of matched
users) for tapptic backend assessment.

## REST API reference

- Add reaction to the user
    - path: `POST /api/reactions`
    - params: `{ from_user_id: int, to_user_id: int, reaction: enum('like', 'dislike') }`
    - returns:
        - HTTP status code 201: if successfully created, returns created reaction. Format (
          JSON): `{ "from_user_id": int, "to_user_id": int, "type": string, "reaction": string }`
        - HTTP status code 422: if validation fails. Format (
          JSON): `{ "message": "The given data was invalid.", "errors": { "reaction": ["Reactions to users cannot be changed."] } }`

## Database structure

- users
    - `id` (unsigned big integer, AI, PK, NN)
    - `name` (varchar)
- user_reactions
    - `id` (unsigned big integer, AI, PK, NN)
    - `from_user_id` (unsigned big integer, NN, FK(references `users.id`), "User who reacted")
    - `to_user_id` (unsigned big integer, NN, FK(references `users.id`), "User you responded to")
    - `type` (enum: `['swipe']`)
    - `reaction` (varchar(30))
    - index on: `[type, reaction]`
- user_matches
    - `first_user_id` (unsigned big integer, FK(references `users.id`), NN)
    - `second_user_id` (unsigned big integer, FK(references `users.id`), NN)

## Testing

To run tests, execute in project dir:

```bash
 ./vendor/bin/phpunit tests
```
