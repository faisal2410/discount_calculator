<?php
// include "DiscountInterface.php";

class PercentageDiscount implements DiscountInterface
{
    private $percentage;

    public function __construct($percentage)
    {
        $this->percentage = $percentage;
    }

    public function calculate($amount)
    {
        return $amount- $amount * ($this->percentage / 100);
    }
}