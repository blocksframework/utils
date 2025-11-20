<?php

namespace Blocks\Utils\Param;

class StringsArrayParam {
    /**
     * Param converter.
     *
     * @param array|string $param
     *
     * @return string[]
     * @throws \InvalidArgumentException When parameter is invalid or array contains non-string items
     */
    public static function get( array|string $param ): array {
        $results = [];

        if ( is_array( $param ) ) {
            foreach ( $param as $item ) {
                if ( is_string( $item ) ) {
                    $results[] = $item;
                }
                else {
                    throw new \InvalidArgumentException( 'A single item of the passed array is not a string' );
                }
            }
        }
        elseif ( is_string( $param ) ) {
            $results[] = $param;
        }
        else {
            throw new \InvalidArgumentException( 'The passed argument is neither an array of strings, nor a string' );
        }

        return $results;
    }
}
