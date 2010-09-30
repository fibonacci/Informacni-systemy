<?php
/**
 * 
 */
class admin extends website {
    private $subpage;

    public function  __construct() {
        parent::__construct();

        $this->Route();
    }

    private function Route(){
        if(isset ($_GET['section']) && $_GET['section']){
            $section = $_GET['section'];
        } else {
            $section = 'articles';
        }

        

        if(class_exists($section)){
            $this->subpage = new $section();
        }
    }

    public function GetContent(){
        return $this->subpage->GetContent();
    }
}
?>
