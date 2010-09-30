<?php
/**
 * 
 */
class authors extends website {
    private $inputs;
    
    public function  __construct() {
        parent::__construct();
        $this->Init();
        $this->Route();
    }

    protected function Init(){
        $this->SetVariable('title', 'Seznam autorů');
        $this->inputs = array('nameValue', 'emailValue');
        $this->clearVariables = array_merge($this->inputs, array('warning'));
        parent::Init();
    }

    private function Route(){
        $submit = 'newAuthorSend';

        if(isset ($_POST[$submit])){
            if($this->CheckData($this->inputs)){
                if($this->SaveToDatabase()){
                    $this->SetVariable('warning', '<p class="ok">Autor byl uložen.<p>');
                } else {
                    $this->SetVariable('warning', '<p class="error">Autor se stejným jménem již existuje.<p>');
                    $this->SavePostedData($this->inputs, $submit);
                }
            } else {
                $this->SetVariable('warning', '<p class="error">Nebyla vyplněna všechna data.</p>');
                $this->SavePostedData($this->inputs, $submit);
            }
        }

        $this->SetVariable('authors', $this->GetHTMLAuthors());
    }

    private function SaveToDatabase(){
        $sql = 'INSERT INTO `authors`';
        $values = array(
            'name' => $_POST['nameValue'],
            'email' => $_POST['emailValue']
        );
        
        try{
            dibi::query($sql, $values);
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    private function GetAuthors(){
        $sql = 'SELECT * FROM `authors`';
        return dibi::fetchAll($sql);
    }

    private function GetHTMLAuthors(){
        $authors = $this->GetAuthors();

        if($authors){
            $html = '<div id="authors"><table>';
            $html .= '<thead><tr><th>Jméno autora</th><th>Email</th></tr></thead>';

            foreach ($authors as $author) {
                
                $html .= '<tr class="fadeOut_' . $author->ID . '">';
                $html .= '<td>' . $author->name . '</td>';
                $html .= '<td>' . $author->email . '</td>';
                $html .= '<td><a href="?page=admin&amp;section=editauthor&amp;id=' . $author->ID . '" class="edit">editovat</a></td>';
                $html .= '<td class="delete"><a href="?page=admin&amp;section=deleteauthor&amp;id=' . $author->ID . '" class="' . $author->ID . '">smazat</a></td>';
                $html .= '</tr>';
            }

            $html .= '</table></div>';
        } else {
            $html = '<p>Nebyly nalezeni žádní autoři.</p>';
        }
        
        return $html;
    }
}
?>
