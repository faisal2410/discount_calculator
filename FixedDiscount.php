<?php
// include "DiscountInterface.php";

class FixedDiscount implements DiscountInterface
{
    private $fixedAmount;

    public function __construct($fixedAmount)
    {
        $this->fixedAmount = $fixedAmount;
    }

    public function calculate($amount)
    {
        return max($amount - $this->fixedAmount, 0); // Ensure discount does not exceed the amount
    }
}