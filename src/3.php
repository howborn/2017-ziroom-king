<?php
/**
 * 比较规则
 */
function cmp($a, $b) {
    if ($a == $b) {
        return 0;
    }
    return $a . $b > $b . $a ? -1 : 1;
}

function array_form_max_str(array $Arr) {
    //参数校验
    if (!is_array($Arr)) {
        return '';
    }

    foreach ($Arr as $value) {
        if ($value < 0) {
            return '';
        }
    }

    usort($Arr, "cmp");
    //拼接
    return implode('', $Arr);
}

while(!$input = trim(fgets(STDIN), " \t\n\r\0\x0B[]"));
echo array_form_max_str(explode(',', $input)), PHP_EOL;