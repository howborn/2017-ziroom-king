<?php

/**
 * 约瑟夫环公式：f(N,M)=(f(N−1,M)+M)%n
 * @param $n
 * @param $e
 * @return int
 */
function josephus($n, $e)
{
    $idx = 0;
    for ($i = 2; $i <= $n; $i ++) {
        $idx = ($idx + $e) % $i;
    }
    return $idx;
}

while(!$input = trim(fgets(STDIN), " \t\n\r\0\x0B[]"));
$input = str_replace(' ', '&', $input);
parse_str($input, $arr);
echo josephus($arr['n'], $arr['e']), PHP_EOL;