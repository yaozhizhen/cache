<?php

require 'DoubleLinkList.php';

/**
 * Class LRUCache
 * 最近最少使用算法
 * 1.每次使用时把使用的节点放到链表最前面
 * 2.淘汰缓存时，把链表尾部的节点淘汰
 */
class LRUCache
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
            //移除它
            $this->list->remove($key);
            //把它添加到链头
            $this->list->addHead($key, $node->value);
            return $node->value;
        }
    }

    //放入
    public function put($key, $value)
    {
        if ($this->capacity === 0) {
            return self::FAIL;
        }
        //如果没有
        if ($this->list->search($key) === self::FAIL) {
            //判断满了没有
            if ($this->list->total() >= $this->capacity) {
                $this->list->delTail();
            }
        } else {
            //把它移除
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

$LRU = new LRUCache(4);
for ($i = 0; $i < 5; $i++) {
    $LRU->put($i, $i);
    echo $LRU->print();
}
echo $LRU->get(1);
echo PHP_EOL;
echo $LRU->get(0);
echo PHP_EOL;
echo $LRU->print();