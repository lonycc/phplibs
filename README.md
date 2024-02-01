### 制作 composer 包

**0x01 初始化composer.json**

`composer init`

> 填写包的各类信息，包括name、description、keywords、type、version、autoload、authors、license、require等。

composer.json 架构可参考 [点击](https://docs.phpcomposer.com/04-schema.html)

**0x02 编写代码文件**

以本项目为例，在`src`目录下，创建两个子目录`Lib`和`Test`，分别放核心功能类库代码和测试代码。

以 `src/Lib/TreeNode.php` 为例，代码如下：

```
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
```

注意到`namespace`为`Tony\Mixed\Lib`，这是因为`composer.json`里的`authoload.psr-4`，有设置命名空间对应的代码路径映射关系：`"Tony\\Mixed\\" => "src/"`。

**0x03 推送到github仓库**

```
git init
git remote add origin git@github.com:lonycc/phplibs.git
git commit -am "first commit"
git push -u origin main
```

**0x04 提交到packagist.org**

首先需要注册一个账号，邮箱激活以后，登录账号，在这个地址 `https://packagist.org/packages/submit` 把github仓库地址复制进去，点击提交。不出意外，就能提交成功了。如有意外，比如名字和别已有的包重复啦，类似啦，那就改一下，更新到github，再提交到packagist。


**0x05 使用**

在你的项目里： `composer require tooooony/aes_rsa_tree_linked_node`、
然后在你的代码里这样引用：

```
use Tony\Mixed\Lib\AES;

$key = bin2hex(random_bytes(8));
$iv = bin2hex(random_bytes(8));
$msg = 'this is your message';

$aes = new AES($key, $iv);
$encrypted = $aes->encrypt($msg);

$decrypted = $aes->decrypt($encrypted);

if ( $decrypted == $msg ) {
	echo "aes works";
} else {
	echo "aes sucks";
}
```