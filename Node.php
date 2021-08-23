<?php

//节点类
class Node
{
    //key
    public $key;
    //值
    public $value;
    //频率
    public $freq;
    //前驱
    public $pre = null;
    //后继
    public $next = null;

    public function __construct($key, $value, $freq)
    {
        $this->key = $key;
        $this->value = $value;
        $this->freq = $freq;
        $this->next = null;
        $this->pre = null;
    }

    //打印
    public function print()
    {
        return $this->key . '-' . $this->value . '-' . $this->freq;
    }

    public function __toString()
    {
        return $this->print();
    }
}