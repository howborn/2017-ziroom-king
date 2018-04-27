<?php

function getCards($rent) {
    $cardMoney = array(1000, 500, 100, 50, 20, 10, 5, 1);
    $cardNumber = array_fill(0, count($cardMoney), 0);

    $i = 0;
    do {
        $cardNumber[$i] = intval($rent / $cardMoney[$i]);
        $rent = $rent % $cardMoney[$i];
        if ($rent < $cardMoney[$i]) {
            $i++;
        }
    } while ($rent);

    return $cardNumber;
}

//54
while(!$input = trim(fgets(STDIN), " \t\n\r\0\x0B[]"));
$card = getCards((int)$input);
echo array_sum($card), PHP_EOL;