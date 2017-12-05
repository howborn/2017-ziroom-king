<?php

class Pool
{
    public $gridArray = array();
    public $maxHeight = 0;
    public $row = 0;
    public $col = 0;

    public function __construct(array $data)
    {
        $this->row = count($data);
        $this->col = count($data[0]);

        foreach ($data as $row => $rowArray) {
            foreach ($rowArray as $col => $height) {
                $this->gridArray[$row][$col]['height'] = (int)$height;
                $this->gridArray[$row][$col]['water'] = 0;

                if ($this->maxHeight < $height) {
                    $this->maxHeight = $height;
                }
            }
        }
    }

    public function addWater()
    {
        foreach ($this->gridArray as $row => $rowArray) {
            foreach ($rowArray as $col => $grid) {
                if (!$this->isBorder($row, $col)) {
                    $this->gridArray[$row][$col]['water'] = $this->maxHeight - $this->gridArray[$row][$col]['height'];
                }
            }
        }
    }

    public function removeWater()
    {
        foreach ($this->gridArray as $row => $rowArray) {
            foreach ($rowArray as $col => $grid) {
                if ($this->canRemove($row, $col)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function canRemove($row, $col)
    {
        $can = false;

        if (!$this->isBorder($row, $col)) {
            //上
            if ($this->gridArray[$row][$col]['water'] + $this->gridArray[$row][$col]['height'] >
                $this->gridArray[$row - 1][$col]['water'] + $this->gridArray[$row - 1][$col]['height']) {
                $this->gridArray[$row][$col]['water'] =
                    $this->gridArray[$row - 1][$col]['water'] + $this->gridArray[$row - 1][$col]['height']
                    - $this->gridArray[$row][$col]['height'];
                if ($this->gridArray[$row][$col]['water'] < 0) {
                    $this->gridArray[$row][$col]['water'] = 0;
                }
                $can = true;
            }
            //右
            if ($this->gridArray[$row][$col]['water'] + $this->gridArray[$row][$col]['height'] >
                $this->gridArray[$row][$col + 1]['water'] + $this->gridArray[$row][$col + 1]['height']) {
                $this->gridArray[$row][$col]['water'] =
                    $this->gridArray[$row][$col + 1]['water'] + $this->gridArray[$row][$col + 1]['height']
                    - $this->gridArray[$row][$col]['height'];
                if ($this->gridArray[$row][$col]['water'] < 0) {
                    $this->gridArray[$row][$col]['water'] = 0;
                }
                $can = true;
            }
            //下
            if ($this->gridArray[$row][$col]['water'] + $this->gridArray[$row][$col]['height'] >
                $this->gridArray[$row + 1][$col]['water'] + $this->gridArray[$row + 1][$col]['height']) {
                $this->gridArray[$row][$col]['water'] =
                    $this->gridArray[$row + 1][$col]['water'] + $this->gridArray[$row + 1][$col]['height']
                    - $this->gridArray[$row][$col]['height'];
                if ($this->gridArray[$row][$col]['water'] < 0) {
                    $this->gridArray[$row][$col]['water'] = 0;
                }
                $can = true;
            }
            //左
            if ($this->gridArray[$row][$col]['water'] + $this->gridArray[$row][$col]['height'] >
                $this->gridArray[$row][$col - 1]['water'] + $this->gridArray[$row][$col - 1]['height']) {
                $this->gridArray[$row][$col]['water'] =
                    $this->gridArray[$row][$col - 1]['water'] + $this->gridArray[$row][$col - 1]['height']
                    - $this->gridArray[$row][$col]['height'];
                if ($this->gridArray[$row][$col]['water'] < 0) {
                    $this->gridArray[$row][$col]['water'] = 0;
                }
                $can = true;
            }
        }

        return $can;
    }

    public function isBorder($row, $col)
    {
        if ($row == 0
            || $row == $this->row - 1
            || $col == 0
            || $col == $this->col - 1
        ) {
            return true;
        }

        return false;
    }

    public function collect()
    {
        $sum = 0;
        foreach ($this->gridArray as $row => $rowArray) {
            foreach ($rowArray as $col => $grid) {
                $sum += $grid['water'];
            }
        }

        return $sum;
    }

    public function run()
    {
        $this->addWater();
        //进行漏水，一直到所有砖不需要漏水
        while ($this->removeWater());

        return $this->collect();
    }

}

$input = explode(',', trim(fgets(STDIN), " \t\n\r\0\x0B[]"));
$filter = function ($value) {
    return explode(' ', $value);
};

$pool = new Pool(array_map($filter, $input));
echo $pool->run(), PHP_EOL;