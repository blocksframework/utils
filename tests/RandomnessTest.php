<?php

namespace Blocks\Utils\Tests;

use Blocks\Utils\Randomness;
use PHPUnit\Framework\TestCase;

class RandomnessTest extends TestCase {
    /**
     * Test normal string generation with default character set.
     */
    public function testGenerateStringWithDefaultCharset(): void {
        $length = 10;
        $result = Randomness::generateString( $length );
        
        $this->assertIsString( $result );
        $this->assertEquals( $length, strlen( $result ) );
    }

    /**
     * Test string generation with custom character set.
     */
    public function testGenerateStringWithCustomCharset(): void {
        $length = 20;
        $customChars = 'ABC123';
        $result = Randomness::generateString( $length, $customChars );
        
        $this->assertIsString( $result );
        $this->assertEquals( $length, strlen( $result ) );
        
        // Verify all characters are from the custom set
        for ( $i = 0; $i < strlen( $result ); $i++ ) {
            $this->assertStringContainsString( $result[$i], $customChars );
        }
    }

    /**
     * Test string generation with single character set.
     */
    public function testGenerateStringWithSingleCharacter(): void {
        $length = 5;
        $result = Randomness::generateString( $length, 'A' );
        
        $this->assertEquals( 'AAAAA', $result );
        $this->assertEquals( $length, strlen( $result ) );
    }

    /**
     * Test string generation with predefined NUMERIC constant.
     */
    public function testGenerateStringWithNumericConstant(): void {
        $length = 8;
        $result = Randomness::generateString( $length, Randomness::NUMERIC );
        
        $this->assertIsString( $result );
        $this->assertEquals( $length, strlen( $result ) );
        $this->assertMatchesRegularExpression( '/^[0-9]+$/', $result );
    }

    /**
     * Test string generation with predefined ALPHANUMERIC constant.
     */
    public function testGenerateStringWithAlphanumericConstant(): void {
        $length = 15;
        $result = Randomness::generateString( $length, Randomness::ALPHANUMERIC );
        
        $this->assertIsString( $result );
        $this->assertEquals( $length, strlen( $result ) );
        $this->assertMatchesRegularExpression( '/^[a-zA-Z0-9]+$/', $result );
    }

    /**
     * Test string generation with human-readable constant.
     */
    public function testGenerateStringWithHumanReadableConstant(): void {
        $length = 12;
        $result = Randomness::generateString( $length, Randomness::ALPHANUMERIC_WITHOUT_SIMILARLY_LOOKING_CHARACTERS );
        
        $this->assertIsString( $result );
        $this->assertEquals( $length, strlen( $result ) );
        
        // Verify no confusing characters (0, 1, 2, 5, I, O, S, Z)
        $this->assertStringNotContainsString( '0', $result );
        $this->assertStringNotContainsString( '1', $result );
        $this->assertStringNotContainsString( '2', $result );
        $this->assertStringNotContainsString( '5', $result );
    }

    /**
     * Test that zero length throws exception.
     */
    public function testGenerateStringThrowsExceptionForZeroLength(): void {
        $this->expectException( \InvalidArgumentException::class );
        $this->expectExceptionMessage( 'Length must be a positive integer' );
        
        Randomness::generateString( 0 );
    }

    /**
     * Test that negative length throws exception.
     */
    public function testGenerateStringThrowsExceptionForNegativeLength(): void {
        $this->expectException( \InvalidArgumentException::class );
        $this->expectExceptionMessage( 'Length must be a positive integer' );
        
        Randomness::generateString( -5 );
    }

    /**
     * Test that empty character set throws exception.
     */
    public function testGenerateStringThrowsExceptionForEmptyCharset(): void {
        $this->expectException( \InvalidArgumentException::class );
        $this->expectExceptionMessage( 'Character set cannot be empty' );
        
        Randomness::generateString( 10, '' );
    }

    /**
     * Test that multibyte character set throws exception.
     */
    public function testGenerateStringThrowsExceptionForMultibyteCharset(): void {
        $this->expectException( \InvalidArgumentException::class );
        $this->expectExceptionMessage( 'Character set must contain only single-byte ASCII characters' );
        
        Randomness::generateString( 10, '😀😃😄' );
    }

    /**
     * Test that multibyte UTF-8 characters are rejected.
     */
    public function testGenerateStringThrowsExceptionForUTF8Charset(): void {
        $this->expectException( \InvalidArgumentException::class );
        $this->expectExceptionMessage( 'Character set must contain only single-byte ASCII characters' );
        
        Randomness::generateString( 10, 'ąčęėįšųū' );
    }

    /**
     * Test randomness - ensure different strings are generated.
     */
    public function testGenerateStringProducesRandomOutput(): void {
        $results = [];
        
        for ( $i = 0; $i < 10; $i++ ) {
            $results[] = Randomness::generateString( 20 );
        }
        
        // All strings should be unique (statistically extremely likely)
        $this->assertEquals( count( $results ), count( array_unique( $results ) ) );
    }

    /**
     * Test that the method generates strings with correct distribution.
     * This is a statistical test that might rarely fail due to randomness.
     */
    public function testGenerateStringHasReasonableDistribution(): void {
        $charset = 'AB';
        $iterations = 1000;
        $totalLength = 10 * $iterations;
        $concatenated = '';
        
        for ( $i = 0; $i < $iterations; $i++ ) {
            $concatenated .= Randomness::generateString( 10, $charset );
        }
        
        $countA = substr_count( $concatenated, 'A' );
        $countB = substr_count( $concatenated, 'B' );
        
        // Each character should appear roughly 50% of the time
        // Allow 10% tolerance (45-55%)
        $this->assertGreaterThan( $totalLength * 0.45, $countA );
        $this->assertLessThan( $totalLength * 0.55, $countA );
        $this->assertGreaterThan( $totalLength * 0.45, $countB );
        $this->assertLessThan( $totalLength * 0.55, $countB );
    }

    /**
     * Test large string generation.
     */
    public function testGenerateStringWithLargeLength(): void {
        $length = 10000;
        $result = Randomness::generateString( $length, Randomness::NUMERIC );
        
        $this->assertEquals( $length, strlen( $result ) );
    }
}
