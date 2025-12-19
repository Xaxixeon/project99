<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Offline-first status update for mobile/API; reuses core logic.
     */
    public function updateStatus(Request $req, Order $order): JsonResponse
    {
        return app(\App\Http\Controllers\OrderController::class)->updateStatus($req, $order);
    }
}
