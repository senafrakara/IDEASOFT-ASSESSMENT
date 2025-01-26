<?php

namespace App\Http\Controllers;

use App\Services\DiscountService;
use App\Models\Order;

class DiscountController extends Controller
{
    protected $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    /**
     * @OA\Get(
     *     path="/api/orders/{id}/discounts",
     *     tags={"Discounts"},
     *     summary="Calculate discounts for an order",
     *     description="Retrieve applicable discounts for a specific order.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the order to calculate discounts for",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Discounts calculated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="discounts", type="array", 
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="type", type="string", example="Percentage"),
     *                     @OA\Property(property="amount", type="number", format="float", example=10.00),
     *                     @OA\Property(property="description", type="string", example="10% off for orders above $100")
     *                 )
     *             ),
     *             @OA\Property(property="totalDiscount", type="number", format="float", example=15.00)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Order not found")
     *         )
     *     )
     * )
     */
    public function calculateDiscounts($orderId)
    {
        $order = Order::with('items.product')->find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $result = $this->discountService->calculateDiscounts($order);

        return response()->json($result);
    }
}
