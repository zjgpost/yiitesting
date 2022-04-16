<?php

namespace app\common\helps;

class Helps {
    
    public static function get_attend_status_label()
    {
        return ['ok' => '开启', 'hold' => '禁用'];
    }

    public static function get_attend_status() 
    {
        return ['all' => '全部', 'ok' => '开启', 'hold' => '禁用'];
    }

    /**
     * @param $count 列的数量
     * @return array 列名一维数组
     */
    public static function get_column($count)
    {
        $columnFlag = [
            0 => 'Z', 1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E', 6 => 'F', 7 => 'G', 8 => 'H',
            9 => 'I', 10 => 'J', 11 => 'K', 12 => 'L', 13 => 'M', 14 => 'N', 15 => 'O', 16 => 'P', 17 => 'Q',
            18 => 'R', 19 => 'S', 20 => 'T', 21 => 'U', 22 => 'V', 23 => 'W', 24 => 'X', 25 => 'Y', 26 => 'Z'
        ];

        if ($count == 0) {
            return [];
        }

        $column = [];
        for ($index = 1; $index <= $count; $index++) {
            if ($index <= 26) {
                $column[] = $columnFlag[$index].'1';
            } else {
                $value = floor($index / 26);
                if ($index % 26 == 0) {
                    $value -= 1;
                }
                $column[] = $columnFlag[$value] . $columnFlag[floor($index % 26)].'1';
            }
        }
        return $column;
    }

    /**
     * @param array $headers
     * @return array 一维数组
     */
    public static function get_header_columns($headers) {
        $count = count($headers);
        $keys = self:: get_column($count);
        return array_combine($keys, $headers);
    }

}