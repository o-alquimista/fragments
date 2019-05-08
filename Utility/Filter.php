<?php

/**
 * Filter Utility
 *
 * Input sanitizing
 */

namespace Fragments\Utility\Filter;

interface Filter {

    public static function clean($input);

}

class FilterInput implements Filter {

    public static function clean($input) {

        /*
         * Method clean() returns $input sanitized
         * to help prevent XSS.
         */

        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;

    }

}

?>