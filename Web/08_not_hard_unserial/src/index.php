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
