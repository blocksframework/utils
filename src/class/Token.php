<?php

namespace Blocks\Utils;

/**
 * Cryptographically secure token generation.
 * 
 * Provides methods for generating secure tokens using PHP's random_bytes()
 * function for cryptographically secure pseudo-random generation.
 */
class Token {
    /**
     * Generate a cryptographically secure hexadecimal token.
     * 
     * Uses random_bytes() to generate a token consisting of hexadecimal characters (0-9, a-f).
     * More efficient than Randomness::generateString() when you only need hexadecimal output.
     * 
     * For custom character sets (alphanumeric, special characters, etc.), use 
     * Randomness::generateString() instead.
     * 
     * @param int $length The desired length of the token (must be positive)
     * @return string The generated hexadecimal token (lowercase a-f, 0-9)
     * @throws \InvalidArgumentException If length is not positive
     * @throws \Exception If random_bytes() fails (extremely rare)
     */
    public static function generate( int $length ): string {
        if ( $length <= 0 ) {
            throw new \InvalidArgumentException( 'Length must be a positive integer' );
        }

        // Round up to nearest even number since bin2hex produces 2 chars per byte
        $bytesNeeded = (int) ceil( $length / 2 );

        $token = bin2hex( random_bytes( $bytesNeeded ) );

        // Use substr since bin2hex always produces ASCII hexadecimal characters
        return substr( $token, 0, $length );
    }
}
