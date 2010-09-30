<?php
/**
 * 
 */
class deletearticle extends website {
    public function  __construct() {
        parent::__construct();
        $this->Init();
        $this->Route();
    }

    protected function Init(){
        $this->SetVariable('title', 'Článek smazán');
        $this->clearVariables = array('warning');
        parent::Init();
    }

    private function Route(){
        if(isset($_GET['id'])){
            $sql = 'DELETE FROM `articles` WHERE id = %i';
            try{
                dibi::query($sql, $_GET['id']);
                $this->SetVariable('warning', '<p>Článek byl smazán.</p>');
            } catch (Exception $ex){
                $war = 'Článek nebyl smazán.';
                $this->SetVariable('warning', '<p>' . $war . '</p>');
                $this->SetVariable('title', $war);
            }
        }
    }
}
?>
