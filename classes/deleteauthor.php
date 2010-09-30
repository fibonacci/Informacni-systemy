<?php
/**
 * 
 */
class deleteauthor extends website {
    public function  __construct() {
        parent::__construct();
        $this->Init();
        $this->Route();
    }

    protected function Init(){
        $this->SetVariable('title', 'Autor smazán');
        parent::Init();
    }

    private function Route(){
        if(isset($_GET['id'])){
            $sql = 'DELETE FROM `authors` WHERE id = %i';
            try{
                dibi::query($sql, $_GET['id']);
                $this->SetVariable('warning', '<p>Autor byl smazán.</p>');
            } catch (Exception $ex){
                $war = 'Autor nebyl smazán.';
                $this->SetVariable('warning', '<p>' . $war . '</p>');
                $this->SetVariable('title', $war);
            }
        }
    }
}
?>
