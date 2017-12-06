<?php
/**
 * 比较规则,ab和ba组合后的数字进行比较
 */
function cmp($a, $b) {
    if ($a == $b) {
        return 0;
    }
    return $a . $b > $b . $a ? -1 : 1;
}

function array_form_max_str(array $Arr) {
    foreach ($Arr as $value) {
        if ($value < 0) {
            return '';
        }
    }

    usort($Arr, "cmp");
    //拼接
    return implode('', $Arr);
}

//4,94,9,14,1
while(!$input = trim(fgets(STDIN), " \t\n\r\0\x0B[]"));
echo array_form_max_str(explode(',', $input)), PHP_EOL;