<?php

namespace Blocks\Utils\Tests;

use Blocks\Utils\Token;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase {
    /**
     * Test normal token generation.
     */
    public function testGenerateToken(): void {
        $length = 32;
        $result = Token::generate( $length );
        
        $this->assertIsString( $result );
        $this->assertEquals( $length, strlen( $result ) );
    }

    /**
     * Test that generated tokens contain only hexadecimal characters.
     */
    public function testGenerateTokenIsHexadecimal(): void {
        $result = Token::generate( 40 );
        
        $this->assertMatchesRegularExpression( '/^[0-9a-f]+$/', $result );
    }

    /**
     * Test that generated tokens are lowercase hexadecimal.
     */
    public function testGenerateTokenIsLowercase(): void {
        $result = Token::generate( 100 );
        
        // Verify no uppercase hex characters (A-F)
        $this->assertDoesNotMatchRegularExpression( '/[A-F]/', $result );
        $this->assertMatchesRegularExpression( '/^[0-9a-f]+$/', $result );
    }

    /**
     * Test token generation with even length.
     */
    public function testGenerateTokenWithEvenLength(): void {
        $length = 20;
        $result = Token::generate( $length );
        
        $this->assertEquals( $length, strlen( $result ) );
        $this->assertMatchesRegularExpression( '/^[0-9a-f]+$/', $result );
    }

    /**
     * Test token generation with odd length.
     */
    public function testGenerateTokenWithOddLength(): void {
        $length = 15;
        $result = Token::generate( $length );
        
        $this->assertEquals( $length, strlen( $result ) );
        $this->assertMatchesRegularExpression( '/^[0-9a-f]+$/', $result );
    }

    /**
     * Test token generation with length of 1.
     */
    public function testGenerateTokenWithLengthOne(): void {
        $result = Token::generate( 1 );
        
        $this->assertEquals( 1, strlen( $result ) );
        $this->assertMatchesRegularExpression( '/^[0-9a-f]$/', $result );
    }

    /**
     * Test that zero length throws exception.
     */
    public function testGenerateTokenThrowsExceptionForZeroLength(): void {
        $this->expectException( \InvalidArgumentException::class );
        $this->expectExceptionMessage( 'Length must be a positive integer' );
        
        Token::generate( 0 );
    }

    /**
     * Test that negative length throws exception.
     */
    public function testGenerateTokenThrowsExceptionForNegativeLength(): void {
        $this->expectException( \InvalidArgumentException::class );
        $this->expectExceptionMessage( 'Length must be a positive integer' );
        
        Token::generate( -10 );
    }

    /**
     * Test randomness - ensure different tokens are generated.
     */
    public function testGenerateTokenProducesRandomOutput(): void {
        $results = [];
        
        for ( $i = 0; $i < 10; $i++ ) {
            $results[] = Token::generate( 32 );
        }
        
        // All tokens should be unique (statistically extremely likely)
        $this->assertEquals( count( $results ), count( array_unique( $results ) ) );
    }

    /**
     * Test that the method generates tokens with reasonable distribution.
     * 
     * This is a statistical test - each hex digit should appear roughly 6.25% of the time.
     * With a 2% tolerance (4.25-8.25%), expect < 1 failure per 1000 runs due to randomness.
     */
    public function testGenerateTokenHasReasonableDistribution(): void {
        $iterations = 1000;
        $tokenLength = 100;
        $concatenated = '';
        
        for ( $i = 0; $i < $iterations; $i++ ) {
            $concatenated .= Token::generate( $tokenLength );
        }
        
        // Count how many times each hex digit appears
        $totalChars = strlen( $concatenated );
        $counts = [];
        
        for ( $digit = 0; $digit <= 9; $digit++ ) {
            $counts[(string) $digit] = substr_count( $concatenated, (string) $digit );
        }
        
        for ( $char = 'a'; $char <= 'f'; $char++ ) {
            $counts[$char] = substr_count( $concatenated, $char );
        }
        
        // Each of 16 hex digits should appear roughly 1/16 of the time (6.25%)
        // Allow 2% tolerance (4.25-8.25%)
        foreach ( $counts as $digit => $count ) {
            $percentage = $count / $totalChars;
            $this->assertGreaterThan( 0.0425, $percentage, "Digit {$digit} appears too rarely" );
            $this->assertLessThan( 0.0825, $percentage, "Digit {$digit} appears too frequently" );
        }
    }

    /**
     * Test large token generation.
     */
    public function testGenerateTokenWithLargeLength(): void {
        $length = 10000;
        $result = Token::generate( $length );
        
        $this->assertEquals( $length, strlen( $result ) );
        $this->assertMatchesRegularExpression( '/^[0-9a-f]+$/', $result );
    }

    /**
     * Test that Token class is more efficient than character-based generation.
     * This is a performance comparison test.
     */
    public function testGenerateTokenPerformance(): void {
        $length = 1000;
        
        $start = microtime( true );
        for ( $i = 0; $i < 100; $i++ ) {
            Token::generate( $length );
        }
        $tokenTime = microtime( true ) - $start;
        
        // Just verify it completes in reasonable time (< 1 second for 100 iterations)
        $this->assertLessThan( 1.0, $tokenTime, 'Token generation should be fast' );
    }

    /**
     * Test that Token::generate() is faster than Randomness::generateString() for hex.
     * 
     * Validates the claim that Token is "more efficient than Randomness::generateString()
     * when you only need hexadecimal output."
     */
    public function testGenerateTokenIsFasterThanRandomness(): void {
        $length = 1000;
        $iterations = 100;
        
        // Time Token::generate()
        $start = microtime( true );
        for ( $i = 0; $i < $iterations; $i++ ) {
            Token::generate( $length );
        }
        $tokenTime = microtime( true ) - $start;
        
        // Time Randomness::generateString() with hex characters
        $start = microtime( true );
        for ( $i = 0; $i < $iterations; $i++ ) {
            \Blocks\Utils\Randomness::generateString( $length, '0123456789abcdef' );
        }
        $randomnessTime = microtime( true ) - $start;
        
        // Token should be significantly faster (allow some variance but expect < 90% of Randomness time)
        $this->assertLessThan( $randomnessTime * 0.9, $tokenTime, 
            sprintf( 'Token (%.4fs) should be faster than Randomness (%.4fs)', $tokenTime, $randomnessTime ) );
    }
}
