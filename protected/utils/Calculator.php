<?php
class Calculator {

    private static $_result_cache=array();

    public static function level_pow($level, $factor=1.1){

        if ($level != 0) {
            $id = 'level_pow'. $level. $factor;
            if (!isset(self::$_result_cache[$id])) {
                return self::$_result_cache[$id] = $level. pow($factor, $level);
            }
            return self::$_result_cache[$id];
        } else {
            return 0;
        }
    }

}
