<?php
//<5.5版本兼容
if(!function_exists('array_column')) {
    function array_column($data, $col)
    {
        return array_map(function($arr) use ($col) {
            return $arr[$col];
        }, $data);
    }
}

/*
 * 01背包问题
 */
class Knapsack
{
    /**
     * 物品重量,index从1开始表示第1个物品
     * @var array
     */
    public $w = array();

    /**
     * 物品价值,index从1开始表示第1个物品
     * @var array
     */
    public $v = array();

    /**
     * 最大价值,$wv[$i][$w]中$i表示前i个物品重量为w时的最大价值
     * @var array
     */
    public $wv = array();

    /**
     * 物品总数
     * @var int
     */
    public $n = 0;
    /**
     * 物品总重量
     * @var int
     */
    public $W = 0;

    /**
     * 背包中的物品
     * @var array
     */
    public $goods = array();

    /**
     * Knapsack constructor.
     * @param array $goods 物品信息,格式如下:
     * [
     *   [index, w, v]   //good1
     *   ...
     * ]
     *
     * eg:
     * [
     *   [MV10001, 2, 100]
     *   [MV10002, 3, 120]
     *   [MV10003, 1, 200]
     * ]
     *
     * @param $c
     */
    public function __construct(array $goods, $c)
    {
        $this->goods = $goods;

        $this->W = $c;
        $this->n = count($goods);
        //初始化物品价值
        $v = array_column($goods, 2);
        array_unshift($v, 0);
        $this->v = $v;
        //初始化物品重量
        $w = array_column($goods, 1);
        array_unshift($w, 0);
        $this->w = $w;
        //初始化最大价值
        $this->wv = array_fill(0, $this->n + 1, array_fill(0, $this->W + 1, 0));

        $this->pd();

        $this->canPut();
    }

    /**
     * 动态规划过程
     *
     * 1.不放第i件物品
     *   wv[i][j] = wv[i-1][j]
     *
     * 2.放入第i件物品
     *   wv[i][j] = max(wv[i-1][j], wv[i-1][j - w[i]] + v[i]
     */
    public function pd()
    {
        for ($i = 0; $i <= $this->W; $i++) {
            for ($j = 0; $j <= $this->n; $j++) {
                //未放入物品和重量为空时,价值为0
                if ($i == 0 || $j == 0) {
                    continue;
                }

                //决策
                if ($i < $this->w[$j]) {
                    $this->wv[$j][$i] = $this->wv[$j - 1][$i];
                } else {
                    $this->wv[$j][$i] = max($this->wv[$j - 1][$i], $this->wv[$j - 1][$i - $this->w[$j]] + $this->v[$j]);
                }
            }
        }
    }

    public function canPut()
    {
        $c = $this->W;
        for ($i = $this->n; $i > 0; $i--) {

            //背包质量为c时,前i-1个和前i-1个物品价值不变,表示第1个物品未放入
            if ($this->wv[$i][$c] == $this->wv[$i - 1][$c]) {
                $this->goods[$i - 1][3] = 0;
            } else {
                $this->goods[$i - 1][3] = 1;
                $c = $c - $this->w[$i];
            }
        }
    }

    public function getMaxPrice()
    {
        return $this->wv[$this->n][$this->W];
    }

    public function getGoods()
    {
        $filter = function($value) {
            return $value[3];
        };
        $goods = array_filter($this->goods, $filter);

        //价值由大到小排序，争取价值相同时按照每小时平均争取价值由大到小排序
        usort($goods, function($a, $b) {
            if ($a[2] == $b[2]) {
                if ($a[2] / $a[1] < $b[2] / $b[1]) {
                    return 1;
                }
                return 0;
            }
            return $a[2] < $b[2];
        });

        return $goods;
    }
}

//[MV10001 2 100,MV10002 3 120,MV10003 1 200,MV10004 3 200,MV10005 4 70,MV10006 3 120,MV10007 2 10,MV10008 2 30,MV10009 6 500,MV10010 3 400]
while(!$input = trim(fgets(STDIN), " \t\n\r\0\x0B[]"));
$arr = explode(',', $input);
$filter = function ($value) {
    return explode(' ', $value);
};

$knapsack = new Knapsack(array_map($filter, $arr), 8);
$goods = $knapsack->getGoods();

echo $knapsack->getMaxPrice(), ' ', implode(' ', array_column($goods, 0)), PHP_EOL;