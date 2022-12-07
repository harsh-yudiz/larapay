<?php

return [
    'custom_length'         =>  env('CUSTOM_ID_LENGTH', 8),
    'default_password'      =>  env('PASSWORD_LENGTH', 16),
    'caching'               =>  env('CACHE_ALLOW', false),
    'paypal_authentication_api' => 'https://api-m.sandbox.paypal.com/v1/oauth2/token',
    'method_post' => 'POST',
    'method_get' => 'GET',
    'Authentication' => 'QWZET19PVXNHVlNHSEwxQnZZZDBRMTFEWktsX1hZaGJ6MENiR3lhenFDaHdDUE5zU1hkYXZ4eHl5WUNsMWphWHZmQnRua2V6R045NW9qT3k6RUhOMDFaY1lncGxZbDFwSjBDMExCVXBiZlNlTVpSeVRrekZzWHdoLWdvOTYzQTNXY1JWUzFZYW5oNmVhcmQyTTYxcE5BV2dPa1RBU3ExaDA=',
    'paypal_create_product_endpoint' => 'https://api-m.sandbox.paypal.com/v1/catalogs/products',
    'paypal_create_plan_endpoint' => 'https://api-m.sandbox.paypal.com/v1/billing/plans',
    'paypal_subscription_scheduled' => 'https://api-m.sandbox.paypal.com/v1/billing/subscriptions',

];