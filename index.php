<?php
include_once "DiscountInterface.php";
include_once "PercentageDiscount.php";
include_once "FixedDiscount.php";
include_once "BulkDiscount.php";
include_once "SeasonalDiscount.php";
include_once "DiscountCalculator.php";
include_once "DiscountFactory.php";

// User input
$amount = floatval(readline("Enter the original amount: "));
$discountType = readline("Enter discount type (percentage/fixed/bulk/seasonal): ");

// Create the discount object using the factory
try {
    if ($discountType === 'percentage' ) {
        $percentage = floatval(readline("Enter the discount percentage: "));
        $discount = DiscountFactory::createDiscount($discountType, $percentage);
    } elseif ($discountType === 'fixed') {
        $fixedAmount = floatval(readline("Enter the fixed discount amount: "));
        $discount = DiscountFactory::createDiscount($discountType, $fixedAmount);
    } elseif ($discountType === 'bulk') {
        // Values are retrieved from the configuration file
        $discount = DiscountFactory::createDiscount($discountType, null);
    } elseif($discountType==='seasonal'){
        $generalDiscount = floatval(readline("Enter the Geneal discount percentage: "));
        $discount = DiscountFactory::createDiscount($discountType, $generalDiscount);

    }
    else {
        echo "Invalid discount type!";
        exit;
    }

    // Calculate the discount using DiscountCalculator
    $calculator = new DiscountCalculator();
    $finalAmount = $calculator->calculateDiscount($discount, $amount);
    

    echo "The final amount after discount is: Tk" . number_format($finalAmount, 2);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
