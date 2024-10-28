<?php

class DiscountFactory
{
    private static $config;

    // Load configuration from JSON file only once
    private static function loadConfig()
    {
        if (self::$config === null) {
            $configPath = __DIR__ . "/discounts_config.json";
            if (!file_exists($configPath)) {
                throw new Exception("Configuration file not found.");
            }
            self::$config = json_decode(file_get_contents($configPath), true);
        }
    }

    public static function createDiscount($type, $value)
    {
        self::loadConfig();

        if (!isset(self::$config[$type])) {
            throw new Exception("Invalid discount type specified.");
        }

        $discountClass = self::$config[$type]['class'];
        $parameters = self::$config[$type]['parameters'] ?? [];

        // Dynamically instantiate discount types based on config and parameters
        switch ($type) {
            case 'percentage':
                return new PercentageDiscount($value);
            case 'fixed':
                return new FixedDiscount($value);
            case 'bulk':
                return new BulkDiscount($parameters['threshold'], $parameters['bulkDiscountAmount']);
            case 'seasonal':
                return new SeasonalDiscount($parameters['seasonalDiscount'],$value);
            default:
                throw new Exception("Discount class for $type not found.");
        }
    }
}
