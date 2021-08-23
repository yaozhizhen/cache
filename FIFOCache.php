<?php
require 'DoubleLinkList.php';

/**
 * Class FIFOCache
 * 先进先出算法
 * 1.淘汰缓存时，把最先进入链表的节点淘汰,
 * 2.可以看成一个先进先出的队列
 */
class FIFOCache
{
    const FAIL = false;
    const SUCCESS = true;
    public $capacity;
    public $list;

    public function __construct($capacity)
    {
        $this->capacity = $capacity;
        $this->list = new DoubleLinkList($capacity);
    }

    //获取
    public function get($key)
    {
        $node = $this->list->search($key);
        //如果没有
        if ($node === self::FAIL) {
            return $node;
        } else {
            return $node->value;
        }
    }

    //放入
    public function put($key, $value)
    {
        if ($this->capacity === 0) {
            return self::FAIL;
        }

        //如果链表里没有
        if ($this->list->search($key) === self::FAIL) {
            //如果已经满了
            if ($this->list->total() >= $this->capacity) {
                $this->list->delTail();
            }
        } else {
            //移除它
            $this->list->remove($key);
        }
        //把它添加到链头
        $this->list->addHead($key, $value);
        return self::SUCCESS;
    }

    //总数
    public function total()
    {
        return $this->list->total();
    }

    //打印
    public function print()
    {
        return $this->list;
    }
}

$FIFO = new FIFOCache(4);
for ($i = 0; $i < 5; $i++) {
    $FIFO->put($i, $i);
    echo $FIFO->print();
}
echo $FIFO->get(1);
echo PHP_EOL;
echo $FIFO->get(0);
echo PHP_EOL;
echo $FIFO->print();