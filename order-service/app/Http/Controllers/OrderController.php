<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Info(
 *     title="Order Service API",
 *     version="1.0.0",
 *     description="API for managing orders"
 * )
 * 
 * @OA\Tag(
 *     name="Orders",
 *     description="Operations related to orders"
 * )
 */
class OrderController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Get all orders",
     *     description="Retrieve a list of all orders along with their associated items.",
     *     @OA\Response(
     *         response=200,
     *         description="List of orders with items",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="customer_id", type="integer", example=1),
     *                 @OA\Property(property="total", type="number", format="float", example=100.50),
     *                 @OA\Property(
     *                     property="items",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="order_id", type="integer", example=1),
     *                         @OA\Property(property="product_id", type="integer", example=1),
     *                         @OA\Property(property="quantity", type="integer", example=2),
     *                         @OA\Property(property="unit_price", type="number", format="float", example=50.25),
     *                         @OA\Property(property="total", type="number", format="float", example=100.50),
     *                         @OA\Property(property="product", type="object", nullable=true)
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Order::with('items')->get());
    }

    /**
     * @OA\Post(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Create a new order",
     *     description="Create a new order for a customer with the given items.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="customerId", type="integer", example=1),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="productId", type="integer", example=1),
     *                     @OA\Property(property="quantity", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="customer_id", type="integer", example=1),
     *             @OA\Property(property="total", type="number", format="float", example=100.50)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or insufficient stock",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Insufficient stock for product XYZ")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'customerId' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.productId' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $total = 0;
                $orderItems = [];

                foreach ($request->items as $item) {
                    $product = Product::findOrFail($item['productId']);

                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Insufficient stock for product {$product->name}");
                    }

                    $itemTotal = $product->price * $item['quantity'];
                    $total += $itemTotal;

                    $orderItems[] = [
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->price,
                        'total' => $itemTotal
                    ];

                    $product->stock -= $item['quantity'];
                    $product->save();
                }

                $order = Order::create([
                    'customer_id' => $request->customerId,
                    'total' => $total
                ]);

                $order->items()->createMany($orderItems);

                return response()->json($order->load('items'), 201);
            });
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/orders/{id}",
     *     tags={"Orders"},
     *     summary="Delete an order",
     *     description="Delete an order and all its associated items.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the order to delete",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Order deleted successfully"
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
    public function destroy(Order $order)
    {
        $order->items()->delete();
        $order->delete();
        return response()->json(null, 204);
    }
}
