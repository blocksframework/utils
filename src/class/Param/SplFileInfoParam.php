<?php

namespace Blocks\Utils\Param;

use SplFileInfo;

class SplFileInfoParam {
    /**
     * Param converter.
     *
     * @param string|\SplFileInfo $param
     *
     * @return \SplFileInfo
     * @throws \InvalidArgumentException When parameter is neither string nor SplFileInfo
     */
    public static function get( \SplFileInfo|string $param ): \SplFileInfo {
        if ( is_string( $param ) ) {
            return new \SplFileInfo( $param );
        }
        if ( $param instanceof \SplFileInfo ) {
            return $param;
        }

        throw new \InvalidArgumentException( 'The passed argument is neither string nor SplFileInfo' );
    }
}
