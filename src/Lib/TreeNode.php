<?php

namespace Tony\Mixed\Lib;

/**
 * 二叉树结点类
 */
class TreeNode
{
    // 结点的值
    public $val = null;
    
    // 结点的左子树
    public $left = null;
    
    // 结点的右子树
    public $right = null;
    
    public function __construct($value, $left=null, $right=null) 
    {
        $this->val = $value;
        $this->left = $left;
        $this->right = $right;
    }
}