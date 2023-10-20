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