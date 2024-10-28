<?php

class DiscountCalculator
{
    public function calculateDiscount(DiscountInterface $discount, $amount)
    {
        return $discount->calculate($amount);
    }
}