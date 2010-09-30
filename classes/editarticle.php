<?php
/**
 * 
 */
class editarticle extends website {
    public function  __construct() {
        parent::__construct();
        $this->Init();
        $this->Route();
    }

    protected function Init(){
        $this->SetVariable('title', 'Editace článku');
        $this->SetArticleInfo();
        $this->inputs = array('titleValue', 'tagsValue', 'textValue');
        $this->clearVariables = array('warning');
        parent::Init();
    }

    private function Route(){
        $submit = 'editArticleSend';

        if(isset ($_POST[$submit])){
            if($this->CheckData($this->inputs)){
                if($this->SaveToDatabase()){
                    $this->SetVariable('warning', '<p class="ok">Článek byl upraven.<p>');
                } else {
                    $this->SetVariable('warning', '<p class="error">Článek se nepodařilo upravit.<p>');
                    $this->SavePostedData($this->inputs, $submit);
                }
            } else {
                $this->SetVariable('warning', '<p class="error">Nebyla vyplněna všechna data.</p>');
                $this->SavePostedData($this->inputs, $submit);
            }

            $this->SetArticleInfo();
        }
    }

    private function SaveToDatabase(){
        $sql = 'UPDATE `articles` SET';
        $values = array(
            'title' => $_POST['titleValue'],
            'body' => $_POST['textValue'],
            'authorID' => $_POST['authorValue']
        );
        $where = 'WHERE ID = %i';

        try {
            dibi::query($sql, $values, $where, $_GET['id']);
            $this->RemoveArticleTags();
            $this->SaveTags();
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    private function RemoveArticleTags(){
        $sql = 'DELETE FROM `tagsconnect` WHERE articleID = %i';
        dibi::query($sql, $_GET['id']);
    }

    private function SetArticleInfo(){
        $sql = 'SELECT * FROM `articles` WHERE ID = %i';
        $article = dibi::fetch($sql, $_GET['id']);
        if($article){
            $this->SetVariable('titleValue', $article['title']);
            $this->SetVariable('textValue', $article['body']);
            $this->SetVariable('tagsValue', implode(', ', $this->GetArtTags()));
            $this->SetVariable('options', $this->GetHTMLAuthors());
        } else {
            $this->SetVariable('warning', '<p>Autor nebyl nalezen.</p>');
        }
    }

    private function GetArtTags(){
        $sql = 'SELECT tags.name FROM `tagsconnect`
            LEFT JOIN `tags`
            ON tags.ID = tagsconnect.tagID 
            WHERE tagsconnect.articleID = %i';

        $res = dibi::fetchAll($sql, $_GET['id']);
        $arr = array();

        foreach ($res as $value) {
            $arr[] = $value['name'];
        }
        return $arr;
    }

    private function GetHTMLAuthors(){
        $sql = 'SELECT authorID FROM `articles` WHERE ID = %i';
        $authorID = dibi::fetch($sql, $_GET['id'])->authorID;

        $authors = $this->GetAuthors();
        $html = '';
        if($authors){
            foreach ($authors as $autor) {
                $checked = $autor->ID == $authorID ? 'selected' : '';
                $html .= '<option ' . $checked . ' value="' . $autor['ID']  . '">' . $autor['name'] . '</option>';
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

    //FUJ
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
            dibi::query('INSERT INTO `tagsconnect` values(%i, %i)', $tagID, $_GET['id']);
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
