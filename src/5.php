<?php
/*
 * 01背包问题
 */
class Knapsack
{
    public $P = array();
    public $W = array();
    public $V = array();
    public $N = 0;
    public $C = 0;
    public $B = array();

    /**
     * Knapsack constructor.
     * @param array $w 重量
     * @param array $v 价值
     * @param $c
     */
    public function __construct(array $w, array $v, $c)
    {
        $this->C = $c;
        $this->V = $v;
        $this->W = $w;
        $this->N = count($w);
    }

    public function pd()
    {
        for ($i = 0; $i < $this->N; $i++) {
            for ($j = 0; $j <= $this->C; $j++) {
                if ($i == 0 || $j == 0) {
                    $this->P[$i][$j] = 0;
                    continue;
                }

                if ($j < $this->W[$i]) {
                    $this->P[$i][$j] = $this->P[$i - 1][$j];
                } else {
                    $this->P[$i][$j] = max($this->P[$i - 1][$j], $this->P[$i - 1][$j - $this->W[$i]] + $this->V[$i]);
                }
            }
        }

        print_r($this->P);
    }

    public function mark()
    {
        $i = $this->N - 1;
        $j = $this->C;

        while($i > 0 && $j > 0)
        {
            if($this->P[$i][$j] == $this->P[$i-1][$j - $this->W[$i]] + $this->V[$i])
            {
                echo $i, PHP_EOL;
                $j -= $this->W[$i]; //进入下一个物品的求解
            }

            $i--;
        }
    }

    public function run()
    {
        $this->pd();

        //$this->mark();
    }
}

/*while(!$input = trim(fgets(STDIN), " \t\n\r\0\x0B[]"));*/
$input = 'MV10001 2 100,MV10002 3 120,MV10003 1 200,MV10004 3 200,MV10005 4 70,MV10006 3 120,MV10007 2 10,MV10008 2 30,MV10009 6 500,MV10010 3 400';
$arr = explode(',', $input);
array_walk($arr, function (&$value) {
    $value = explode(' ', $value);
});

$knapsack = new Knapsack(array_column($arr, 1), array_column($arr, 2), 3);
$knapsack->run();