<?php

define('MAX', 9999999999);

class Path
{
    /**
     * 图对应索引数组
     * @var array
     */
    public $indexMatrix = array();

    /**
     * 顶点与索引映射关系
     * @var array
     */
    public $indexMap = array();

    public $startPoint;
    public $endPoint;
    public $len = 0;

    /**
     * 最短距离
     * @var array
     */
    public $D = array();

    /**
     * 已寻找集合
     * @var array
     */
    public $U = array();

    /**
     * 最短路径
     * @var array
     */
    public $P = array();

    public function __construct(array $matrix, $startPoint, $endPoint)
    {
        $this->indexMap = array_keys($matrix);
        $this->len = count($matrix);

        array_walk($matrix, function(&$value) {
            $value = array_values($value);
        });
        $this->indexMatrix = array_values($matrix);
        $this->startPoint = array_search($startPoint, $this->indexMap);
        $this->endPoint = array_search($endPoint, $this->indexMap);
    }

    public function init()
    {
        for ($i = 0; $i < $this->len; $i++) {
            //初始化距离
            $this->D[$i] = $this->indexMatrix[$this->startPoint][$i] > 0 ? $this->indexMatrix[$this->startPoint][$i] : MAX;
            $this->P[$i] = array();
            //初始化已寻找集合
            if ($i != $this->startPoint) {
                array_push($this->P[$i], $i);
                $this->U[$i] = false;
            } else {
                $this->U[$i] = true;
            }
        }
    }

    public function dijkstra()
    {
        $this->init();

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
            $this->U[$v] = true;

            //更新最短路径
            for ($i = 0; $i < $this->len; $i++) {
                if (!$this->U[$i] && ($min + $this->indexMatrix[$v][$i] < $this->D[$i])) {
                    $this->D[$i] = $min + $this->indexMatrix[$v][$i];
                    $this->P[$i] = array_merge($this->P[$v], array($i));
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

        foreach ($path as &$value) {
            $value = $this->indexMap[$value];
        }

        return $path;
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

while(!$input = trim(fgets(STDIN), " \t\n\r\0\x0B[]"));
$path = new Path($matrix, $input{0}, $input{1});
$path->dijkstra();
echo $path->getDistance(), ' ', implode('-', $path->getPath()), PHP_EOL;