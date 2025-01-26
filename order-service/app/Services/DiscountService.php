<?php

namespace App\Services;

use App\Models\Order;
use App\Constants\DiscountConstants;
use App\Constants\CategoryConstants;

class DiscountService
{
    public function calculateDiscounts(Order $order)
    {
        $discounts = [];
        $total = $order->total;

        // Check for 6 items from category 2
        $category2Items = $order->items->filter(function ($item) {
            return $item->product->category === CategoryConstants::CATEGORY_2 && $item->quantity >= 6;
        });
        self::category2DiscountAmount($category2Items, $total, $discounts);

        // Check for category 1 items discount %20 discount
        $category1Items = $order->items->filter(function ($item) {
            return $item->product->category === CategoryConstants::CATEGORY_1;
        });

        self::category1DiscountAmount($category1Items, $total, $discounts);

        // Check for total over 1000
        self::over1000Discount($order, $total, $discounts);

        return [
            'orderId' => $order->id,
            'discounts' => $discounts,
            'totalDiscount' => number_format(array_sum(array_column($discounts, 'discountAmount')), 2),
            'discountedTotal' => number_format($total, 2)
        ];
    }

    private static function category2DiscountAmount($category2Items, &$total, &$discounts)
    {
        foreach ($category2Items as $item) {
            $freeItemCount = floor($item->quantity / 6);
            $discountAmount = $item->unit_price * $freeItemCount;
            if ($discountAmount > 0) {
                $discounts[] = [
                    'discountReason' => DiscountConstants::BUY_5_GET_1,
                    'discountAmount' => number_format($discountAmount, 2),
                    'subtotal' => number_format($total - $discountAmount, 2)
                ];
                $total -= $discountAmount;
            }
        }
    }

    private static function category1DiscountAmount($category1Items, &$total, &$discounts)
    {
        if ($category1Items->count() >= 2) {
            $cheapestItem = $category1Items->sortBy('unit_price')->first();
            $discountAmount = $cheapestItem->unit_price * 0.2;
            $discounts[] = [
                'discountReason' => DiscountConstants::CATEGORY_1_DISCOUNT,
                'discountAmount' => number_format($discountAmount, 2),
                'subtotal' => number_format($total - $discountAmount, 2)
            ];
            $total -= $discountAmount;
        }
    }

    private static function over1000Discount($order, &$total, &$discounts)
    {
        if ($order->total >= 1000) {
            $discountAmount = $total * 0.1;
            $discounts[] = [
                'discountReason' => DiscountConstants::TEN_PERCENT_OVER_1000,
                'discountAmount' => number_format($discountAmount, 2),
                'subtotal' => number_format($total - $discountAmount, 2)
            ];
            $total -= $discountAmount;
        }
    }
}
