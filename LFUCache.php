<?php
require_once 'DoubleLinkList.php';

/**
 * Class LFUCache
 * 最不经常使用算法
 * 1.淘汰缓存时，把使用频率最小的淘汰
 * 2.同频率节点按FIFO算法淘汰
 */
class LFUCache
{

    const FAIL = false;
    const SUCCESS = true;
    public $capacity;
    //key：key，value: node
    public $map;
    //key: 频率, value: 频率对应的双向链表
    public $freq_map;
    //数量
    public $size;

    public function __construct($capacity)
    {
        $this->map = [];
        $this->capacity = $capacity;
        $this->freq_map = [];
        $this->size = 0;
    }

    //更新
    public function update($node)
    {
        //把它移除
        $this->freq_map[$node->freq]->remove($node->key);
        //如果这个频率数组的链表为空
        if ($this->freq_map[$node->freq]->total() === 0) {
            unset($this->freq_map[$node->freq]);
        }

        //节点的频率加1
        $node->freq += 1;
        //判断有没有这个频率数组
        if (!isset($this->freq_map[$node->freq])) {
            //新建一个频率的链表
            $this->freq_map[$node->freq] = new DoubleLinkList();
        }

        //添加到这个频率数组的链表头部
        $this->freq_map[$node->freq]->addHead($node->key, $node->value, $node->freq);
    }

    //获取
    public function get($key)
    {
        //如果存在
        if (isset($this->map[$key])) {
            $node = $this->map[$key];
            //更新
            $this->update($node);
            return $node->value;
        } else {
            return self::FAIL;
        }
    }

    //放入
    public function put($key, $value)
    {
        if ($this->capacity === 0) {
            return self::FAIL;
        }

        //如果存在
        if (isset($this->map[$key])) {
            $node = $this->map[$key];
            $node->value = $value;
            //更新
            $this->update($node);
        } else {
            //如果满了
            if ($this->capacity === $this->size) {
                //按key进行升序排序
                ksort($this->freq_map, SORT_NUMERIC);
                //获取freq_map第一个的值
                $freq_map = current($this->freq_map);
                //获取$freq
                $freq = key($this->freq_map);

                $last_node = $freq_map->getLast();
                //移除尾节点
                $freq_map->delTail();
                //如果这个频率数组的链表为空
                if ($this->freq_map[$freq]->total() === 0) {
                    unset($this->freq_map[$freq]);
                }
                //移除(移除的是上面移除的节点的key)
                unset($this->map[$last_node->key]);
                $this->size -= 1;
            }

            $freq = 1;
            $node = new Node($key, $value, $freq);
            $this->map[$key] = $node;
            if (!isset($this->freq_map[$node->freq])) {
                $this->freq_map[$node->freq] = new DoubleLinkList();
            }
            $this->freq_map[$node->freq]->addHead($key, $value, $freq);
            $this->size += 1;
        }
        return self::SUCCESS;
    }

    //打印
    public function print()
    {
        foreach ($this->freq_map as $key => $value) {
            echo "freq为{$key}:" . $value;
        }
        echo '------------------------' . PHP_EOL;
    }
}

$LFU = new LFUCache(2);
for ($i = 0; $i < 5; $i++) {
    $LFU->put($i, $i);
    echo $LFU->print();
}
$LFU->get(3);
echo $LFU->print();
$LFU->get(4);
echo $LFU->print();
$LFU->get(4);
echo $LFU->print();
$LFU->get(1);
echo $LFU->print();
$LFU->put(1, 1);
echo $LFU->print();