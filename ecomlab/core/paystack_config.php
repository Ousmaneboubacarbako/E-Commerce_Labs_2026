<?php
/**
 * Paystack Configuration File
 * 
 * SECURITY WARNING: This file contains sensitive API keys.
 * - NEVER commit this file to version control (add to .gitignore)
 * - NEVER share these keys publicly
 * - Use test keys during development
 * - Use live keys only in production
 * 
 * Get your keys from: https://dashboard.paystack.com/#/settings/developer
 */

// Test Keys (for development)
define('PAYSTACK_PUBLIC_KEY_TEST', 'pk_test_7d11525b99b4a29dc901066aab18d56ab2cd4d95');
define('PAYSTACK_SECRET_KEY_TEST', 'sk_test_8002c21c6bcda710eac630c288dca7947b3cae5f');

// Live Keys (for production - keep secure!)
define('PAYSTACK_PUBLIC_KEY_LIVE', 'pk_live_YOUR_PUBLIC_KEY_HERE');
define('PAYSTACK_SECRET_KEY_LIVE', 'sk_live_YOUR_SECRET_KEY_HERE');

// Environment: 'test' or 'live'
define('PAYSTACK_ENVIRONMENT', 'test');

// Get the appropriate keys based on environment
if (PAYSTACK_ENVIRONMENT === 'live') {
    define('PAYSTACK_PUBLIC_KEY', PAYSTACK_PUBLIC_KEY_LIVE);
    define('PAYSTACK_SECRET_KEY', PAYSTACK_SECRET_KEY_LIVE);
} else {
    define('PAYSTACK_PUBLIC_KEY', PAYSTACK_PUBLIC_KEY_TEST);
    define('PAYSTACK_SECRET_KEY', PAYSTACK_SECRET_KEY_TEST);
}

/**
 * Test Card Details (Paystack Sandbox)
 * These cards work only in test mode and don't charge real money
 * 
 * Success Card:
 * - Card Number: 4084 0840 8408 4081
 * - Expiry: Any future date
 * - CVV: 408
 * - OTP: 123456
 * 
 * Insufficient Funds Card (to test failure):
 * - Card Number: 5060 6666 6666 6666 6666
 * - Expiry: Any future date
 * - CVV: 123
 */
