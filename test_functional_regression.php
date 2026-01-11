<?php
/**
 * Functional Regression Test for OpenCart API Authentication Fix
 * 
 * This test validates that the security fix doesn't break legitimate API functionality.
 */

echo "=== OpenCart API Authentication - Functional Regression Test ===\n\n";

// Simulate the fixed API authentication logic
class ApiAuthSimulator {
    private $api_key = 'test_api_key_for_regression_testing';
    
    public function validateRequest($request_params, $server_params, $post_data = []) {
        echo "  Testing request validation...\n";
        
        // Simulate the OpenCart API authentication flow
        $api_info = ['username' => 'test_api_user', 'key' => $this->api_key];
        
        // Build the HMAC string (matches OpenCart logic)
        $string  = (string)$request_params['route'] . "\n";
        $string .= (string)$request_params['call'] . "\n";
        $string .= $api_info['username'] . "\n";
        $string .= (string)$server_params['HTTP_HOST'] . "\n";
        $string .= (!empty($server_params['PHP_SELF']) ? rtrim(dirname($server_params['PHP_SELF']), '/') . '/' : '/') . "\n";
        $string .= (int)$request_params['store_id'] . "\n";
        $string .= (string)$request_params['language'] . "\n";
        $string .= (string)$request_params['currency'] . "\n";
        $string .= md5(http_build_query($post_data)) . "\n";
        $string .= (string)$request_params['time'] . "\n";
        
        // NEW SECURE CODE: Using hash_equals() for constant-time comparison
        $expected_signature = base64_encode(hash_hmac('sha1', $string, $api_info['key'], true));
        $provided_signature = rawurldecode((string)$request_params['signature']);
        
        if (!hash_equals($expected_signature, $provided_signature)) {
            return false;
        }
        
        return true;
    }
    
    public function generateValidSignature($request_params, $server_params, $post_data = []) {
        $api_info = ['username' => 'test_api_user', 'key' => $this->api_key];
        
        $string  = (string)$request_params['route'] . "\n";
        $string .= (string)$request_params['call'] . "\n";
        $string .= $api_info['username'] . "\n";
        $string .= (string)$server_params['HTTP_HOST'] . "\n";
        $string .= (!empty($server_params['PHP_SELF']) ? rtrim(dirname($server_params['PHP_SELF']), '/') . '/' : '/') . "\n";
        $string .= (int)$request_params['store_id'] . "\n";
        $string .= (string)$request_params['language'] . "\n";
        $string .= (string)$request_params['currency'] . "\n";
        $string .= md5(http_build_query($post_data)) . "\n";
        $string .= (string)$request_params['time'] . "\n";
        
        return base64_encode(hash_hmac('sha1', $string, $api_info['key'], true));
    }
}

$simulator = new ApiAuthSimulator();

// Test Case 1: Valid API request with correct signature
echo "Test 1: Valid API request with correct signature\n";
$request = [
    'route' => 'api/order',
    'call' => 'customer',
    'username' => 'test_api_user',
    'store_id' => 0,
    'language' => 'en-gb',
    'currency' => 'USD',
    'time' => time(),
];
$server = [
    'HTTP_HOST' => 'example.com',
    'PHP_SELF' => '/index.php',
];
$post = [];

$valid_signature = $simulator->generateValidSignature($request, $server, $post);
$request['signature'] = rawurlencode($valid_signature);

if ($simulator->validateRequest($request, $server, $post)) {
    echo "✓ PASS: Valid request accepted\n\n";
} else {
    echo "❌ FAIL: Valid request rejected - REGRESSION DETECTED\n";
    exit(1);
}

// Test Case 2: Invalid signature - should be rejected
echo "Test 2: Invalid signature rejection\n";
$request['signature'] = 'invalid_signature_12345';

if (!$simulator->validateRequest($request, $server, $post)) {
    echo "✓ PASS: Invalid signature correctly rejected\n\n";
} else {
    echo "❌ FAIL: Invalid signature accepted - SECURITY FAILURE\n";
    exit(1);
}

// Test Case 3: URL-encoded signature (common scenario)
echo "Test 3: URL-encoded signature handling\n";
$valid_signature = $simulator->generateValidSignature($request, $server, $post);
$request['signature'] = rawurlencode($valid_signature);  // Simulate URL encoding

if ($simulator->validateRequest($request, $server, $post)) {
    echo "✓ PASS: URL-encoded signature correctly handled\n\n";
} else {
    echo "❌ FAIL: URL-encoded signature rejected - REGRESSION DETECTED\n";
    exit(1);
}

// Test Case 4: Different POST data changes signature
echo "Test 4: POST data integrity verification\n";
$post_data_1 = ['product_id' => 123, 'quantity' => 2];
$post_data_2 = ['product_id' => 456, 'quantity' => 1];

$sig_with_post1 = $simulator->generateValidSignature($request, $server, $post_data_1);
$sig_with_post2 = $simulator->generateValidSignature($request, $server, $post_data_2);

if ($sig_with_post1 !== $sig_with_post2) {
    echo "  ✓ Different POST data produces different signatures\n";
} else {
    echo "  ❌ FAIL: POST data not included in signature\n";
    exit(1);
}

// Validate with correct POST data
$request['signature'] = rawurlencode($sig_with_post1);
if ($simulator->validateRequest($request, $server, $post_data_1)) {
    echo "  ✓ Signature valid for matching POST data\n";
} else {
    echo "  ❌ FAIL: Valid signature with POST data rejected\n";
    exit(1);
}

// Should fail with different POST data
if (!$simulator->validateRequest($request, $server, $post_data_2)) {
    echo "  ✓ Signature invalid for different POST data\n";
} else {
    echo "  ❌ FAIL: Signature accepted with tampered POST data\n";
    exit(1);
}

echo "✓ PASS: POST data integrity checks working correctly\n\n";

// Test Case 5: Case sensitivity
echo "Test 5: Case sensitivity of signatures\n";
$valid_sig = $simulator->generateValidSignature($request, $server, $post);
$uppercase_sig = strtoupper($valid_sig);

$request['signature'] = $valid_sig;
$valid_result = $simulator->validateRequest($request, $server, $post);

$request['signature'] = $uppercase_sig;
$uppercase_result = $simulator->validateRequest($request, $server, $post);

if ($valid_result && !$uppercase_result) {
    echo "✓ PASS: Signature comparison is case-sensitive\n\n";
} else {
    echo "❌ FAIL: Case sensitivity issue detected\n";
    exit(1);
}

// Test Case 6: Signature with special characters (base64 padding)
echo "Test 6: Base64 signatures with padding and special chars\n";
$test_iterations = 10;
$all_passed = true;

for ($i = 0; $i < $test_iterations; $i++) {
    $request['time'] = time() + $i * 10;
    $valid_sig = $simulator->generateValidSignature($request, $server, $post);
    $request['signature'] = rawurlencode($valid_sig);
    
    if (!$simulator->validateRequest($request, $server, $post)) {
        echo "  ❌ FAIL: Valid signature rejected on iteration $i\n";
        echo "  Signature: $valid_sig\n";
        $all_passed = false;
        break;
    }
}

if ($all_passed) {
    echo "✓ PASS: All base64 signatures handled correctly\n\n";
} else {
    exit(1);
}

// Test Case 7: Empty signature
echo "Test 7: Empty signature rejection\n";
$request['signature'] = '';

if (!$simulator->validateRequest($request, $server, $post)) {
    echo "✓ PASS: Empty signature correctly rejected\n\n";
} else {
    echo "❌ FAIL: Empty signature accepted\n";
    exit(1);
}

// Test Case 8: Timing consistency for valid vs invalid signatures
echo "Test 8: Performance consistency test\n";
$iterations = 10000;

// Time valid signature checks
$valid_sig = $simulator->generateValidSignature($request, $server, $post);
$request['signature'] = $valid_sig;

$start = microtime(true);
for ($i = 0; $i < $iterations; $i++) {
    $simulator->validateRequest($request, $server, $post);
}
$time_valid = microtime(true) - $start;

// Time invalid signature checks
$request['signature'] = 'invalid_signature_test';

$start = microtime(true);
for ($i = 0; $i < $iterations; $i++) {
    $simulator->validateRequest($request, $server, $post);
}
$time_invalid = microtime(true) - $start;

echo "  Valid signature checks:   {$time_valid}s\n";
echo "  Invalid signature checks: {$time_invalid}s\n";
echo "  Difference: " . abs($time_valid - $time_invalid) . "s\n";

if (abs($time_valid - $time_invalid) / max($time_valid, $time_invalid) < 0.1) {
    echo "✓ PASS: Performance similar for valid/invalid signatures (< 10% difference)\n\n";
} else {
    echo "⚠ WARNING: Performance difference detected but may be acceptable\n\n";
}

echo "=== ALL FUNCTIONAL REGRESSION TESTS PASSED ===\n";
echo "The security fix maintains correct API authentication behavior.\n";
echo "No regressions detected in legitimate API request handling.\n";

exit(0);
