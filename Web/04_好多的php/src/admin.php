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
