# Discount Calculator

## Project Overview
This project is a flexible and scalable Discount Calculator application built with PHP. It allows applying various discount types, such as percentage-based, fixed-amount, seasonal, and bulk discounts, to an original amount. This design adheres to the Open/Closed Principle (OCP) of SOLID principles, enabling easy addition of new discount types without modifying existing code.

## Project Structure
The project structure is as follows:

project-root/ ├── index.php # Entry point of the application

 ├── DiscountInterface.php # Interface for discount types

  ├── PercentageDiscount.php # Class for percentage-based discount
  
 ├── FixedDiscount.php # Class for fixed-amount discount 
   
 ├── BulkDiscount.php # Class for bulk discount based on a threshold 

 ├── SeasonalDiscount.php # Class for seasonal discount 
   
 ├── DiscountCalculator.php # Class to calculate the final amount after applying discounts 
   
 ├── DiscountFactory.php # Factory class to create discount instances based on configuration 
   
 ├── discounts_config.json # JSON configuration file for discount types and parameters


 
## Project Creation Steps

### Define the Discount Interface (DiscountInterface.php): 

Ensures all discount classes implement the calculate method.

### Create Discount Classes (PercentageDiscount.php, FixedDiscount.php, BulkDiscount.php, and SeasonalDiscount.php):

 Each class implements the DiscountInterface to handle specific discount calculations.

### Create the DiscountCalculator (DiscountCalculator.php):

 Applies any discount type on a given amount.

### Implement the Factory Pattern (DiscountFactory.php): 

Dynamically creates instances of discount classes based on user input and discounts_config.json.

Configure Discount Types (discounts_config.json): Defines available discount types and parameters for each.

### Create the Entry Point (index.php):

 Main file to handle user input, create discount instances using DiscountFactory, and calculate the final amount.

 ## How to Run the Project

 ```
 git clone <repository-url>
cd project-root
```
Clone the repository (or download it as a ZIP):

## Prepare your environment: 

Ensure you have PHP installed. You can check your PHP version by running:

```
php -v

```

### Run the Application:
 Execute the application by running index.php in the command line:
```
php index.php
```


 Enter the original amount when prompted.

Choose the discount type (percentage, fixed, bulk, or seasonal).

Follow the additional prompts based on the selected discount type (e.g., enter a percentage or fixed amount).

### Applied SOLID Principles

### Single Responsibility Principle: Each class has a single responsibility, handling only one aspect of the application.

### Open/Closed Principle: New discount types can be added without modifying existing code, as classes implement DiscountInterface.

### Dependency Inversion Principle: DiscountCalculator relies on DiscountInterface, making it flexible and easy to extend.


### Example Usage

```
Enter the original amount: 1000
Enter discount type (percentage/fixed/bulk/seasonal): percentage
Enter the discount percentage: 10
The final amount after discount is: Tk900.00
```



## Details Steps:

### Step 1: Define the Discount Interface
In `DiscountInterface.php`, the `DiscountInterface` ensures all discount classes implement the `calculate` method, enabling `DiscountCalculator` to apply any discount that implements this interface.

```php
interface DiscountInterface {
    public function calculate($amount);
}
```

### Step 2: Create Discount Classes
Each discount class implements DiscountInterface with unique calculations.

#### PercentageDiscount.php

```php
class PercentageDiscount implements DiscountInterface {
    private $percentage;

    public function __construct($percentage) {
        $this->percentage = $percentage;
    }

    public function calculate($amount) {
        return $amount * ($this->percentage / 100);
    }
}
```
#### FixedDiscount.php

```php
class FixedDiscount implements DiscountInterface {
    private $fixedAmount;

    public function __construct($fixedAmount) {
        $this->fixedAmount = $fixedAmount;
    }

    public function calculate($amount) {
        return $this->fixedAmount;
    }
}
```

#### Additional discount classes include BulkDiscount and SeasonalDiscount, each with unique logic.

### Step 3: Create DiscountCalculator.php

The DiscountCalculator class applies any discount type that implements DiscountInterface.

```php
class DiscountCalculator {
    public function calculateDiscount(DiscountInterface $discount, $amount) {
        return $discount->calculate($amount);
    }
}
```

### Step 4: Implement DiscountFactory.php

The DiscountFactory dynamically creates discount instances based on user input and configuration from discounts_config.json.

```php
class DiscountFactory {
    private static $config;

    private static function loadConfig() {
        if (self::$config === null) {
            self::$config = json_decode(file_get_contents(__DIR__ . "/discounts_config.json"), true);
        }
    }

    public static function createDiscount($type, $value) {
        self::loadConfig();
        switch ($type) {
            case 'percentage':
                return new PercentageDiscount($value);
            case 'fixed':
                return new FixedDiscount($value);
            case 'bulk':
                return new BulkDiscount(self::$config['bulk']['parameters']['threshold'], self::$config['bulk']['parameters']['bulkDiscountAmount']);
            case 'seasonal':
                return new SeasonalDiscount(self::$config['seasonal']['parameters']['seasonalDiscount'], $value);
            default:
                throw new Exception("Invalid discount type.");
        }
    }
}
```

### Step 5: Configure Discount Types

discounts_config.json defines available discount types and parameters, such as bulkDiscountAmount for bulk discounts and seasonalDiscount for seasonal discounts.

```json
{
    "percentage": { "class": "PercentageDiscount", "description": "A percentage discount" },
    "fixed": { "class": "FixedDiscount", "description": "A fixed discount" },
    "bulk": { "class": "BulkDiscount", "parameters": { "threshold": 1000, "bulkDiscountAmount": 50 } },
    "seasonal": { "class": "SeasonalDiscount", "parameters": { "seasonalDiscount": 15 } }
}
```

### Step 6: Create the Main Entry (index.php)

index.php handles user input, creates the discount object using DiscountFactory, and calculates the final amount with DiscountCalculator.

```php
include_once "DiscountInterface.php";
include_once "PercentageDiscount.php";
include_once "FixedDiscount.php";
include_once "BulkDiscount.php";
include_once "SeasonalDiscount.php";
include_once "DiscountCalculator.php";
include_once "DiscountFactory.php";

$amount = floatval(readline("Enter the original amount: "));
$discountType = readline("Enter discount type (percentage/fixed/bulk/seasonal): ");

try {
    $discount = match($discountType) {
        'percentage' => DiscountFactory::createDiscount($discountType, floatval(readline("Enter the discount percentage: "))),
        'fixed' => DiscountFactory::createDiscount($discountType, floatval(readline("Enter the fixed discount amount: "))),
        'bulk' => DiscountFactory::createDiscount($discountType, null),
        'seasonal' => DiscountFactory::createDiscount($discountType, floatval(readline("Enter the general discount percentage: "))),
        default => throw new Exception("Invalid discount type!")
    };

    $calculator = new DiscountCalculator();
    $finalAmount = $calculator->calculateDiscount($discount, $amount);
    echo "The final amount after discount is: Tk" . number_format($finalAmount, 2);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

## Applied SOLID Principles

### Single Responsibility Principle (SRP)

Each class in the project has a single responsibility:

DiscountInterface defines a structure.
Each discount class implements one discount type.

DiscountCalculator applies the discount.
DiscountFactory creates instances of discount classes.

### Open/Closed Principle (OCP)

The project follows OCP by:

Using DiscountInterface to add new discount types without changing existing code.

Using DiscountFactory and discounts_config.json to dynamically manage new discounts.

### Dependency Inversion Principle (DIP)
DiscountCalculator depends on DiscountInterface, an abstraction, making the code more flexible and extendable.


## Summary

This Discount Calculator project adheres to SOLID principles for scalability and ease of maintenance. New discount types can be added by defining a new class and updating the configuration, with minimal changes to the core structure.

By adhering to SOLID principles, this Discount Calculator demonstrates a robust, extensible design, accommodating future changes with minimal code modification.


### License and Copyright
© 2024 [Faisal Ahmed]. All rights reserved.

### This project is provided under an open license, and it is meant for educational and non-commercial use only. Redistribution, modification, or commercial use without permission is prohibited.
