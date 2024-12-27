<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InitiatePaymentController extends Controller
{
    public function __construct(private SubscriptionService $subscriptionService) {
        $this->subscriptionService = $subscriptionService;
    }

    public function createSession(Request $request): JsonResponse
    {
        $content = $request->all();
        $stripeSessionId = $this->subscriptionService->createSession(strval($content['priceId']));

        return new JsonResponse(['sessionId' => $stripeSessionId]);
    }
}
