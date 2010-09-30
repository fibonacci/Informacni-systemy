<?php
class controller{
    public function  __construct() {
        if(isset ($_GET['page'])){
            $page = $_GET['page'];

            if(class_exists($page)){
                $website = new $page();
            } else {
                $website = new NotFound();
            }
        } else {
            $website = new HomePage();
        }

        echo $website->GetContent();
    }
}
?>
