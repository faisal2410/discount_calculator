<?php
class BulkDiscount implements DiscountInterface
{
    private $threshold;
    private $bulkDiscountAmount;

    public function __construct($threshold, $bulkDiscountAmount)
    {
        $this->threshold = $threshold;
        $this->bulkDiscountAmount = $bulkDiscountAmount;
    }

    public function calculate($amount)
    {
        $discountAmount= ($amount >= $this->threshold) ? $this->bulkDiscountAmount : 0;
        return $amount-$discountAmount;
    }
}