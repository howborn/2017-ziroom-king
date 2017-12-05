<?php

define('MAX', 9999999);

class Path
{
    public $matrix = array();
    public $indexMatrix = array();
    public $indexMap = array();
    public $startPoint;
    public $endPoint;
    public $len = 0;

    public $D = array();
    public $U = array();
    public $P = array();

    public function __construct(array $matrix, $startPoint, $endPoint)
    {
        $this->matrix = $matrix;
        $trans = function ($matrix) {
            $data = array();
            foreach ($matrix as $key => $value) {
                $data[] = array_values($value);
            }
            return $data;
        };
        $this->indexMatrix = $trans($matrix);
        $this->indexMap = array_keys($matrix);
        $this->len = count($matrix);
    }

    public function init()
    {
        //初始化图
        for ($i = 0; $i < $this->len; $i++) {
            //初始化距离
            $D[$i] = $this->indexMatrix[$this->startPoint][$i] > 0 ? $this->indexMatrix[$this->startPoint][$i] : MAX;
            $P[$i] = array();
            //初始化已寻找集合
            if ($i != $this->startPoint) {
                array_push($P[$i], $i);
                $U[$i] = false;
            } else {
                $U[$i] = true;
            }
        }
    }

    public function dijkstra()
    {
        for ($l = 1; $l < $this->len; $l++) {
            $min = MAX;
            //查找距离源点最近的节点{v}
            $v = $this->startPoint;
            for ($i = 0; $i < $this->len; $i++) {
                if (!$this->U[$i] && $this->D[$i] < $min) {
                    $min = $this->D[$i];
                    $v = $i;
                }
            }
            $U[$v] = true;
            //更新最短路径
            for ($i = 0; $i < $this->len; $i++) {
                if (!$U[$i] && ($min + $this->indexMatrix[$v][$i] < $this->D[$i])) {
                    $D[$i] = $min + $this->indexMatrix[$v][$i];
                    $P[$i] = array_merge($this->P[$v], array($i));
                }
            }
        }
    }

    public function getDistance()
    {
        return $this->D[$this->endPoint];
    }

    public function getPath()
    {
        $path = $this->P[$this->endPoint];
        array_unshift($path, $this->startPoint);

        $filter = function($value) {
            return $this->indexMap[$value];
        };

        return array_map($filter, $path);
    }
}

/**
 * 图
 */
$matrix = array(
    'A' => array('A' => MAX, 'B' => 15, 'C' => 6, 'D' => MAX, 'E' => MAX, 'F' => 25, 'G' => MAX, 'H' => MAX),
    'B' => array('A' => 15, 'B' => MAX, 'C' => 9, 'D' => MAX, 'E' => 7, 'F' => MAX, 'G' => MAX, 'H' => MAX),
    'C' => array('A' => MAX, 'B' => 9, 'C' => MAX, 'D' => 11, 'E' => MAX, 'F' => MAX, 'G' => MAX, 'H' => MAX),
    'D' => array('A' => MAX, 'B' => MAX, 'C' => 11, 'D' => MAX, 'E' => 12, 'F' => MAX, 'G' => MAX, 'H' => 5),
    'E' => array('A' => MAX, 'B' => 7, 'C' => 6, 'D' => 12, 'E' => MAX, 'F' => 5, 'G' => MAX, 'H' => 7),
    'F' => array('A' => 25, 'B' => MAX, 'C' => 6, 'D' => MAX, 'E' => 5, 'F' => MAX, 'G' => 12, 'H' => MAX),
    'G' => array('A' => MAX, 'B' => MAX, 'C' => MAX, 'D' => MAX, 'E' => MAX, 'F' => 12, 'G' => MAX, 'H' => 17),
    'H' => array('A' => MAX, 'B' => MAX, 'C' => MAX, 'D' => 5, 'E' => 7, 'F' => 25, 'G' => 17, 'H' => MAX),
);

function dijkstra($matrix, $startpoint, $endpoint, &$path = array())
{
    $idx = array_keys($matrix);
    $trans = function ($matrix) {
        $data = array();
        foreach ($matrix as $key => $value) {
            $data[] = array_values($value);
        }
        return $data;
    };
    $matrix = $trans($matrix);
    $points = array_keys($matrix);
    $count = count($points);

    $startpoint = array_search($startpoint, $idx);
    $endpoint = array_search($endpoint, $idx);

    //最小距离点和已寻找集合
    $D = $U = $P = array();
    //初始化图
    for ($i = 0; $i < $count; $i++) {
        //初始化距离
        $D[$i] = $matrix[$startpoint][$i] > 0 ? $matrix[$startpoint][$i] : MAX;
        $P[$i] = array();
        //初始化已寻找集合
        if ($i != $startpoint) {
            array_push($P[$i], $idx[$i]);
            $U[$i] = false;
        } else {
            $U[$i] = true;
        }
    }

    //n次循环完成转移节点任务
    for ($l = 1; $l < $count; $l++) {
        $min = MAX;
        //查找距离源点最近的节点{v}
        $v = $startpoint;
        for ($i = 0; $i < $count; $i++) {
            if (!$U[$i] && $D[$i] < $min) {
                $min = $D[$i];
                $v = $i;
            }
        }
        $U[$v] = true;
        //更新最短路径
        for ($i = 0; $i < $count; $i++) {
            if (!$U[$i] && ($min + $matrix[$v][$i] < $D[$i])) {
                $D[$i] = $min + $matrix[$v][$i];
                $P[$i] = array_merge($P[$v], array($idx[$i]));
            }
        }
    }

    foreach ($P as &$one) {
        array_unshift($one, $idx[$startpoint]);
    }
    $path = array_combine($idx, $P);

    return $D[$endpoint];
}

$path = array();
$input = trim(fgets(STDIN), " \t\n\r\0\x0B[]");
echo dijkstra($matrix, $input{0}, $input{1}, $path), ' ', implode('-', $path[$input{1}]), PHP_EOL;