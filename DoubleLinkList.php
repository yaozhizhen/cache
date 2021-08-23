<?php

require_once 'Node.php';

class DoubleLinkList
{
    //返回失败的值
    const FAIL = false;
    //返回成功的值
    const SUCCESS = true;
    //容量
    public $capacity;
    //头部
    public $head = null;
    //尾部
    public $tail = null;
    //大小
    public $size = 0;

    public function __construct($capacity = 0xffffffff)
    {
        $this->capacity = $capacity;
    }

    //判断链表是否为空
    public function isEmpty()
    {
        return $this->size === 0;
    }

    //判断链表是否满了
    public function isFull()
    {
        return $this->size === $this->capacity;
    }

    //返回链表的大小
    public function total()
    {
        return $this->size;
    }

    //节点
    public function node($key, $value, $freq = 0)
    {
        return new Node($key, $value, $freq);
    }

    //查找
    public function search($key)
    {
        $head = $this->head;
        //如果不为null且不等于key
        while (!is_null($head) && $head->key !== $key) {
            $head = $head->next;
        }

        if (is_null($head)) {
            return self::FAIL;
        }
        return $head;
    }

    //头部新增
    public function addHead($key, $value, $freq = 0)
    {
        if ($this->isFull()) {
            return self::FAIL;
        }

        $node = $this->node($key, $value, $freq);
        //如果链表为空
        if ($this->isEmpty()) {
            $this->head = $node;
            $this->tail = $node;
            $this->head->pre = null;
            $this->head->next = null;
        } else {
            $node->next = $this->head;
            $this->head->pre = $node;
            //把头部移到该节点
            $this->head = $node;
        }

        $this->size += 1;
        return self::SUCCESS;

    }

    //尾部新增
    public function addTail($key, $value, $freq = 0)
    {
        if ($this->isFull()) {
            return self::FAIL;
        }

        $node = $this->node($key, $value, $freq);
        //如果链表为空
        if ($this->isEmpty()) {
            $this->head = $node;
            $this->tail = $node;
            $this->tail->pre = null;
            $this->tail->next = null;
        } else {
            $node->pre = $this->tail;
            $this->tail->next = $node;
            //把尾部移到该节点
            $this->tail = $node;
        }

        $this->size += 1;
        return self::SUCCESS;
    }

    //删除头部
    public function delHead()
    {
        if ($this->isEmpty()) {
            return self::FAIL;
        }

        //如果只有一个节点
        if (is_null($this->head->next)) {
            $this->tail = $this->head = null;
        } else {
            $this->head->next->pre = null;
            //把头部移到该节点
            $this->head = $this->head->next;
        }

        $this->size -= 1;
        return self::SUCCESS;
    }

    //删除尾部
    public function delTail()
    {
        if ($this->isEmpty()) {
            return self::FAIL;
        }
        //如果只有一个节点
        if (is_null($this->tail->pre)) {
            $this->tail = $this->head = null;
        } else {
            $this->tail->pre->next = null;
            //把尾部移到该节点
            $this->tail = $this->tail->pre;
        }
        $this->size -= 1;
        return self::SUCCESS;
    }

    //某个节点后面插入（注意只有一个节点的情况）
    public function addAfter($search, $key, $value, $freq = 0)
    {
        if ($this->isFull()) {
            return self::FAIL;
        }
        $node = $this->node($key, $value, $freq);
        $head = $this->search($search);
        if ($head === self::FAIL) {
            return self::FAIL;
        }

        //只有一个节点的情况
        if ($this->size === 1) {
            $this->addTail($key, $value, $freq);
        } else {
            $temp = $head->next;
            $head->next = $node;
            $node->pre = $head;
            $node->next = $temp;
            $temp->pre = $node;
            $this->size += 1;
        }
        return self::SUCCESS;
    }

    //删除任意节点（注意被删除的位置和链表大小）
    public function remove($key = null)
    {
        //如果为null，则默认删除尾部节点
        if (is_null($key)) {
            return $this->delTail();
        }

        $head = $this->search($key);
        if ($head === self::FAIL) {
            return self::FAIL;
        }

        //头部
        if (is_null($head->pre) && !is_null($head->next)) {
            $this->delHead();
            //尾部
        } elseif (!is_null($head->pre) && is_null($head->next)) {
            $this->delTail();
            //只有一个节点
        } elseif (is_null($head->pre) && is_null($head->next)) {
            $this->head = $this->tail = null;
            $this->size -= 1;
        } else {
            $head->pre->next = $head->next;
            $head->next->pre = $head->pre;
            $this->size -= 1;
        }
        
        return self::SUCCESS;
    }

    //获取头节点
    public function getFirst()
    {
        return $this->head;
    }

    //获取尾节点
    public function getLast()
    {
        return $this->tail;
    }

    //清空列表
    public function clear()
    {
        $this->head = $this->tail = null;
        $this->size = 0;
    }

    //打印
    public function print()
    {
        $head = $this->head;
        $str = '';
        //遍历
        while (!is_null($head)) {
            $str .= (string)$head . "=>";
            $head = $head->next;
        }

        if ($str) {
            return rtrim($str, '=>') . "，总共{$this->size}个" . PHP_EOL;
        } else {
            return "没有任何节点，总共{$this->size}个" . PHP_EOL;
        }
    }

    public function __toString()
    {
        return $this->print();
    }
}
/*
$link = new DoubleLinkList(5);

$link->addHead(1, 1);
echo $link->print();
$link->addHead(2, 2);
echo $link;
$link->addHead(3, 3);
echo $link;
$search = $link->search(1);
echo $search === DoubleLinkList::FAIL ? -1 : $search->value; echo PHP_EOL;
$search = $link->search(0);
echo $search === DoubleLinkList::FAIL ? -1 : $search->value; echo PHP_EOL;
$link->delHead();
echo $link;
$link->delHead();
echo $link;
$link->delHead();
echo $link;
$link->addTail(4, 4);
echo $link;
$link->addTail(5, 5);
echo $link;
$link->addTail(6, 6);
echo $link;
$link->delTail();
echo $link;
$link->delTail();
echo $link;
$link->delTail();
echo $link;
$link->addTail(6, 6);
echo $link;
$link->addTail(7, 7);
echo $link;
$link->remove(6);
echo $link;
$link->remove(999);
echo $link;
$link->remove(7);
echo $link;

$link->addTail(7, 7);
echo $link;
$link->addAfter(7, 8,8);
echo $link;
$link->addAfter(7, 9,9);
echo $link;
$link->clear();
echo $link;
$link->addTail(7, 7);
echo $link;
$link->addTail(8, 8);
echo $link;
echo $link->getFirst();
echo PHP_EOL;
echo $link->getLast();
*/
