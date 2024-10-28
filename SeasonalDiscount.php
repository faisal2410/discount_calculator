<?php
class SeasonalDiscount implements DiscountInterface
{
    private $seasonalDiscount;
    private $generalDiscount;

    public function __construct($seasonalDiscount,$generalDiscount)
    {
        $this->seasonalDiscount = $seasonalDiscount;
        $this->generalDiscount=$generalDiscount;
    }

    public function calculate($amount)
    {
        $seasonalDiscount= $amount * ($this->seasonalDiscount / 100);
        $generalDiscount= $amount * ($this->generalDiscount / 100);
        return $amount-$seasonalDiscount-$generalDiscount;

        // $amount * ($this->percentage / 100)
    }
}