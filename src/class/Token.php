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
     * More efficient than character-based string generation for hex-only tokens.
     * 
     * @param int $length The desired length of the token (must be positive)
     * @return string The generated hexadecimal token
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

        return mb_substr( $token, 0, $length );
    }
}
