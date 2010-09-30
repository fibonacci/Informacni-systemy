<?php
/**
 * 
 */
class newarticle extends website {
    private $inputs;
    private $articleID;

    public function  __construct() {
        parent::__construct();
        $this->Init();
        $this->Route();
    }

    protected function Init(){
        $this->SetVariable('title', 'Nový článek');
        $this->SetVariable('options', $this->GetHTMLAuthors());
        $this->inputs = array('titleValue', 'tagsValue', 'textValue');
        $this->clearVariables = array_merge($this->inputs, array('warning'));
        parent::Init();
    }

    private function Route(){
        $submit = 'newArticleSend';

        if(isset ($_POST[$submit])){
            if($this->CheckData($this->inputs)){
                if($this->SaveToDatabase()){
                    $this->SetVariable('warning', '<p class="ok">Článek byl uložen.<p>');
                } else {
                    $this->SetVariable('warning', '<p class="error">Článek se stejným názvem již existuje.<p>');
                    $this->SavePostedData($this->inputs, $submit);
                }
            } else {
                $this->SetVariable('warning', '<p class="error">Nebyla vyplněna všechna data.</p>');
                $this->SavePostedData($this->inputs, $submit);
            }          
        }
    }

    private function GetHTMLAuthors(){
        $authors = $this->GetAuthors();
        $html = '';
        if($authors){
            foreach ($authors as $autor) {
                $html .= '<option value="' . $autor['ID']  . '">' . $autor['name'] . '</option>';
            }
        } else {
            $html = '<option>žádný autor</option>';
        }

        return $html;
    }

    private function GetAuthors(){
        $sql = 'SELECT * FROM `authors`';
        return dibi::query($sql);
    }

    private function SaveToDatabase(){
        $this->SaveArticle();
        $this->SaveTags();
        return true;
    }

    private function SaveArticle(){
        $sql = 'INSERT INTO `articles`';
        $content = array(
            'title' => $_POST['titleValue'],
            'body' => $_POST['textValue'],
            'authorID' => $_POST['authorValue']
        );

        try {
            dibi::query($sql, $content);
            $this->articleID = mysql_insert_id();
            return true;
        } catch (Exception $ex){
            return false;
        }
    }

    private function SaveTags(){
        $tags = $this->GetTags();
        $sql = "";

        foreach ($tags as $tag) {
            try {
                dibi::query('INSERT INTO `tags` (name) values (%s)', $tag);
            } catch (Exception $e) {
                
            }
        }

        $tagsID = $this->GetTagsWithID($tags);
        foreach ($tagsID as $tagID) {
            dibi::query('INSERT INTO `tagsconnect` values(%i, %i)', $tagID, $this->articleID);
        }
    }

    private function GetTagsWithID($tagsNames){
        $sql = 'SELECT ID FROM `tags` WHERE name IN';
        $sql .= "('" . implode("', '", $tagsNames) . "')";
        $res = dibi::fetchAll($sql);
        return $res;
    }

    private function GetTags(){
        $tags = explode(',', $_POST['tagsValue']);
        for($i = 0; $i < count($tags); $i++){
            $tags[$i] = trim($tags[$i]);
        }
        return $tags;
    }
}
?>
