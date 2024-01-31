<?php

namespace Tony\Mixed\Lib;

/**
 * 链表结点类
 */
class LinkedNode
{
    // 结点key
    public $key;
    // 结点value
    public $value;
    // 前置结点
    public $prev = null;
    // 后继结点
    public $next = null;

    public function __construct($key, $value, $next=null, $prev=null)
    {
        $this->key = $key;
        $this->value = $value;
        $this->next = $next;
        $this->prev = $prev;
    }

    /**
     * 翻转链表
     */
    public function reverse(): LinkedNode
    {
        $cur = $this;
        $next = null;
        $prev = null;
        while ( $cur != null ) {
            $next = $cur->next;
            $cur->next = $prev;
            // $cur->prev = $next;
            $prev = $cur;
            $cur = $next;
        }
        return $prev;
    }

    /**
     * 返回中间节点: 利用双指针, 快慢节点, 可返回任意位置的节点
     */
    public function middleNode(): LinkedNode
    {
        $head = $this;
        $slow = $head; // 慢指针一次一步
        $fast = $head;  // 快指针一次两步
        while ( $fast != null && $fast->next != null ) {
            $slow = $slow->next;
            $fast = $fast->next->next;
        }
        return $slow;
    }

    /**
     * 删除指定属性的节点: 按key或value查找节点并删除, 返回删除后的链表
     */
    public function deleteNodeByProperty(mixed $v, string $field='key'): LinkedNode
    {
        $head = $this;
        if ( $head->$field == $v ) return $head->next;
        $prev = $head;
        $cur = $head->next;
        
        while ( $cur != null && $cur->$field != $v ) {
            $prev = $cur;
            $cur = $cur->next;
        }
        if ( $cur != null ) $prev->next = $cur->next;

        return $head;
    }

    /**
     * 删除链表元素, 返回链表
     */
    public function removeElements(mixed $v): LinkedNode
    {
        $head = $this;
        // 哨兵节点
        $sentinel = new LinkedNode(uniqid(), 0);
        $sentinel->next = $head;

        $prev = $sentinel;
        $cur = $head;
        
        while ( $cur != null ) {
            if ( $cur->value == $v ) $prev->next = $cur->next;
            else $prev = $cur;
            $cur = $cur->next;
        }

        return $sentinel->next;
    }

    /**
     * 删除指定节点
     */
    public function deleteNode(LinkedNode $node): LinkedNode
    {
        // 删掉的节点变成其下一个节点
        $node->key = $node->next->key;
        $node->value = $node->next->value;
        $node->next = $node->next->next;

        return $this;
    }

    /**
     * 判断链表是否有环: 双指针, 有重合则有环
     */
    public function hasCycle(): bool
    {
        $head = $this;
        if ( $head == null || $head->next == null )
            return false;
        
        $slow = $head;
        $fast = $head->next;

        while ( $slow != $fast ) {
            if ( $fast == null || $fast->next == null ) return false; // 快指针走到尽头了, 说明无环
            $slow = $slow->next;  // 慢指针一次走一步
            $fast = $fast->next->next;  // 快指针一次走两步
        }
        return true;
    }

    /**
     * 合并两个有序链表: 合并后保持有序
     */
    public static function merge(LinkedNode $node1, LinkedNode $node2): LinkedNode
    {
        $prehead = new LinkedNode(uniqid(), -1);
        $prev = $prehead;

        while ( $node1 != null && $node2 != null ) {
            if ( $node1->value <= $node2->value ) {
                $prev->next = $node1;
                $node1 = $node1->next;
            } else {
                $prev->next = $node2;
                $node2 = $node2->next;
            }
            $prev = $prev->next;
        }
        $prev->next = $node1 == null ? $node2 : $node1;

        return $prehead->next;
    }

}