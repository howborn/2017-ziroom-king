<?php

class Rank
{
    public $data = array();
    public $operate = array('', '-', '+');
    public $originLen = 0;
    public $sum = 0;

    public function __construct(array $data, $sum)
    {
        $this->originLen = count($data);
        $this->sum = $sum;

        foreach ($data as $k => $value) {
            $this->data[$k*2] = $value;
        }
    }

    public function ternary($number)
    {
        $pos = 2 * $this->originLen - 3;

        do {
            $mod = $number % 3;
            $number = (int)($number / 3);
            $this->data[$pos] = $this->operate[$mod];
            $pos -= 2;
        } while($number);
        //高位补0
        while ($pos > 0) {
            $this->data[$pos] = $this->operate[0];
            $pos -= 2;
        }

        ksort($this->data);
    }

    public function run()
    {
        $result = array();
        $times = pow(3, $this->originLen - 1);
        for ($i = 0; $i < $times; $i++) {
            //模拟3进制的运算
            $this->ternary($i);
            //计算结果
            $str = implode('', $this->data);
            if (eval("return $str;") == $this->sum) {
                $result[] = $str;
            }
        }

        return $result;
    }
}

$numArr = explode(' ', trim(fgets(STDIN), " \t\n\r\0\x0B[]"));
$rank = new Rank($numArr, 110);

array_walk($rank->run(), function($value) {
    echo $value, PHP_EOL;
});