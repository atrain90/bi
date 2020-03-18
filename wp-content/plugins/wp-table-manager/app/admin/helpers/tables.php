<?php
/**
 * WP Table Manager
 *
 * @package WP Table Manager
 * @author  Joomunited
 * @version 1.0
 */

/**
 * Class WptmTablesHelper
 */
class WptmTablesHelper
{
    /**
     * Convert an object of files to a multidimentional array
     *
     * @param array /object $tables Table array
     *
     * @return array
     */
    public static function categoryObject($tables)
    {
        $ordered = array();
        foreach ($tables as $table) {
            $ordered[$table->id_category][] = $table;
        }
        return $ordered;
    }
}
