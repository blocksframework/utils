<?php

namespace Blocks\Utils;

/**
 * Cryptographically secure random string generation.
 * 
 * Uses PHP's random_int() function for cryptographically secure
 * pseudo-random number generation with custom character sets.
 */
class Randomness {
    /**
     * Numeric characters (0-9).
     */
    public const NUMERIC = '0123456789';
    
    /**
     * Alphanumeric characters (a-z, A-Z, 0-9).
     */
    public const ALPHANUMERIC = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    
    /**
     * Alphanumeric characters excluding similarly looking characters (I, O, S, Z, 0, 1, 2, 5).
     * Useful for human-readable codes.
     */
    public const ALPHANUMERIC_WITHOUT_SIMILARLY_LOOKING_CHARACTERS = 'ABCDEFGHJKLMNPQRTUVWXY346789';
    
    /**
     * Alphanumeric characters plus special characters for strong passwords.
     */
    public const ALPHANUMERIC_SPECIAL_CHARACTERS = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789~!@#$%^&*()_-+={}[]\|:;,./';

    /**
     * Generate a random string of specified length using a custom character set.
     * 
     * Uses cryptographically secure random_int() for character selection.
     * Character set must contain only single-byte ASCII characters for reliable indexing.
     * 
     * @param int $length The desired length of the random string (must be positive)
     * @param string|null $customChars Custom character set to use (defaults to ALPHANUMERIC_SPECIAL_CHARACTERS)
     * @return string The generated random string
     * @throws \InvalidArgumentException If length is not positive, character set is empty, or contains multibyte characters
     * @throws \Exception If random_int() fails (extremely rare)
     */
    public static function generateString( int $length, ?string $customChars = null ): string {
        if ( $length <= 0 ) {
            throw new \InvalidArgumentException( 'Length must be a positive integer' );
        }

        $chars = $customChars ?? self::ALPHANUMERIC_SPECIAL_CHARACTERS;

        if ( empty( $chars ) ) {
            throw new \InvalidArgumentException( 'Character set cannot be empty' );
        }

        // Ensure charset contains only single-byte characters for safe byte-indexing
        if ( mb_strlen( $chars, '8bit' ) !== mb_strlen( $chars, 'UTF-8' ) ) {
            throw new \InvalidArgumentException( 'Character set must contain only single-byte ASCII characters' );
        }

        $charsLength = strlen( $chars ) - 1;

        if ( $charsLength < 0 ) {
            throw new \InvalidArgumentException( 'Character set cannot be empty' );
        }

        $string = '';

        for ( $i = 0; $i < $length; $i++ ) {
            $string .= $chars[random_int( 0, $charsLength )];
        }

        return $string;
    }
}
