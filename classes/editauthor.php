<?php
/**
 * 
 */
class editauthor extends website {
    public function  __construct() {
        parent::__construct();
        $this->Init();
        $this->Route();
    }

    protected function Init(){
        $this->SetVariable('title', 'Editace autora');
        $this->SetAuthorInfo();
        $this->clearVariables = array('warning');
        parent::Init();
    }

    private function SetAuthorInfo(){
        $sql = 'SELECT * FROM `authors` WHERE ID = %i';
        $author = dibi::fetch($sql, $_GET['id']);
        if($author){
            $this->SetVariable('nameValue', $author['name']);
            $this->SetVariable('emailValue', $author['email']);
        } else {
            $this->SetVariable('warning', '<p>Autor nebyl nalezen.</p>');
        }
    }

    private function Route(){
        if(isset($_POST['editAuthorSend']) && isset($_GET['id'])){
            if($this->CheckData(array('nameValue', 'emailValue'))){
                $sql = 'UPDATE `authors` SET';
                $values = array(
                    'name' => $_POST['nameValue'],
                    'email' => $_POST['emailValue']
                );
                try{
                    dibi::query($sql, $values, 'WHERE ID=%i', $_GET['id']);
                    $this->SetVariable('warning', '<p>Autor byl upraven.</p>');
                    $this->SetAuthorInfo();
                } catch (Exception $ex){
                    $war = 'Autor nebyl upraven.';
                    $this->SetVariable('warning', '<p>' . $war . '</p>');
                    $this->SetVariable('title', $war);
                }
            }
            
        }
    }
}
?>
