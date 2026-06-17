<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Http\Resources\MessageResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Annotations as OA;

class MessageController extends Controller
{
    public function __construct(
        private readonly MessageRepositoryInterface $messageRepository
    ) {
    }

/**
     * @OA\Get(
     *     path="/api/v1/messages/sent",
     *     summary="Get sent messages",
     *     tags={"Messages"},
     *     @OA\Response(
     *         response=200,
     *         description="List of sent messages"
     *     )
     * )
 */
    public function sent(): AnonymousResourceCollection
    {
        $messages = $this->messageRepository->getSentMessages();

        return MessageResource::collection($messages);
    }
}
