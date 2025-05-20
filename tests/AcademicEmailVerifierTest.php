<?php

namespace AcademicEmailVerifier\Tests;

use AcademicEmailVerifier\AcademicEmailVerifier;
use PHPUnit\Framework\TestCase;

class AcademicEmailVerifierTest extends TestCase
{
    private AcademicEmailVerifier $verifier;

    protected function setUp(): void
    {
        $this->verifier = new AcademicEmailVerifier();
    }

    public function testIsValidEmail(): void
    {
        echo "\nTesting basic email format validation:\n";
        
        // Test valid email addresses
        $validEmails = [
            'test@example.com',
            'user.name@domain.co.uk',
            'user+tag@example.com'
        ];

        foreach ($validEmails as $email) {
            $result = $this->verifier->isValidEmail($email);
            echo "Testing valid email: {$email} - Result: " . ($result ? "PASS" : "FAIL") . "\n";
            $this->assertTrue($result);
        }

        // Test invalid email addresses
        $invalidEmails = [
            'invalid-email',
            'test@',
            '@example.com',
            'test@.com',
            ''
        ];

        foreach ($invalidEmails as $email) {
            $result = $this->verifier->isValidEmail($email);
            echo "Testing invalid email: {$email} - Result: " . (!$result ? "PASS" : "FAIL") . "\n";
            $this->assertFalse($result);
        }
    }

    public function testInvalidEmailFormat(): void
    {
        echo "\nTesting invalid email format handling:\n";
        $email = 'invalid-email';
        $result = $this->verifier->verify($email);
        
        echo "Testing email: {$email}\n";
        echo "Expected: Not academic, No university, Error message\n";
        echo "Actual: is_academic=" . ($result['is_academic'] ? 'true' : 'false') . 
             ", university=" . ($result['university'] ?? 'null') . 
             ", error=" . $result['error'] . "\n";
        
        $this->assertFalse($result['is_academic']);
        $this->assertNull($result['university']);
        $this->assertEquals('Invalid email format', $result['error']);
    }

    public function testNonAcademicEmail(): void
    {
        echo "\nTesting non-academic email verification:\n";
        $email = 'user@gmail.com';
        $result = $this->verifier->verify($email);
        
        echo "Testing email: {$email}\n";
        echo "Expected: Not academic, No university, Domain not found error\n";
        echo "Actual: is_academic=" . ($result['is_academic'] ? 'true' : 'false') . 
             ", university=" . ($result['university'] ?? 'null') . 
             ", error=" . $result['error'] . "\n";
        
        $this->assertFalse($result['is_academic']);
        $this->assertNull($result['university']);
        $this->assertEquals('Domain not found in academic institutions database', $result['error']);
    }

    public function testAcademicEmail(): void
    {
        echo "\nTesting academic email verification:\n";
        $email = 'student@harvard.edu';
        $result = $this->verifier->verify($email);
        
        echo "Testing email: {$email}\n";
        echo "Expected: Academic email, University found, No error\n";
        echo "Actual: is_academic=" . ($result['is_academic'] ? 'true' : 'false') . 
             ", university=" . ($result['university'] ?? 'null') . 
             ", error=" . ($result['error'] ?? 'null') . "\n";
        
        $this->assertTrue($result['is_academic']);
        $this->assertNotNull($result['university']);
        $this->assertNull($result['error']);
    }
} 