<?php
/**
 * 
 */
class homepage extends website {
    public function  __construct() {
        parent::__construct();
        $this->Init();
        $this->Route();
    }

    protected function Init(){
        $this->SetVariable('title', 'Informační systémy');
        parent::Init();
    }

    private function Route(){
        if(isset($_GET['ar'])){
            $ar = $this->ShowArticle();
        } else if(isset($_GET['author'])) {
            $ar = $this->ShowAllAuthorsArticle();
        } else if(isset($_GET['tag'])) {
            $ar = $this->ShowAllTagsArticles();
        } else {
            $ar = $this->ShowAllArticles();
        }

        $this->SetVariable('articles', $ar);
    }

    private function ShowAllTagsArticles(){
        $sql = 'SELECT `tagsconnect.articleID` FROM `tagsconnect`
            LEFT JOIN `tags`
            ON tagsconnect.tagID = tags.ID
            WHERE tags.name = %s';
        $articlesIDSQL = dibi::fetchAll($sql, $_GET['tag']);
        $articlesID = $this->GetArticlesID($articlesIDSQL, 'articleID');
        return $this->ShowAllArticles('articles.ID IN', $this->ImplodeArrayToSQL($articlesID));
    }

    private function ShowAllAuthorsArticle(){
        return $this->ShowAllArticles('articles.authorID = %i', $_GET['author']);
    }

    private function GetSQLArticle($where = ''){
        $sql = 'SELECT
            articles.authorID as authorID,
            articles.title as title,
            articles.body as body,
            articles.ID as ID,
            authors.name as author FROM `articles`
            LEFT JOIN `authors`
            ON articles.authorID = authors.ID';

        if($where){
            $sql .= ' WHERE ' . $where;
        }

        return $sql;
    }

    private function ShowArticle(){
        $sql = $this->GetSQLArticle('articles.ID = %i');
        $article = dibi::fetchAll($sql, $_GET['ar']);
        $tags = $this->GetTagsOfArticles(array($_GET['ar']));
        $tags = $tags[$_GET['ar']];
        $this->SetVariable('title', $article[0]->title);
        return $this->GetArticle($article[0], $tags);
    }

    private function ShowAllArticles($where = '', $ID = ''){
        $sql = $this->GetSQLArticle($where);
        $articles = dibi::fetchAll($sql, $ID, ' ORDER BY articles.ID DESC');
        $articlesID = $this->GetArticlesID($articles);
        $tags = $this->GetTagsOfArticles($articlesID);
        return $this->getArticles($articles, $tags);
    }

    private function GetArticlesID($articles, $ID = 'ID'){
        
        $articlesID = array();

        foreach ($articles as $article) {
            $articlesID[] = $article[$ID];
        }

        return $articlesID;
    }

    private function getArticles($articles, $tags){
        $html = '';

        foreach ($articles as $article) {
            $html .= $this->GetArticle($article, $tags[$article['ID']]);
        }

        return $html;
    }

    private function GetArticle($article, $tags){
        $html = '<h2><a href="?ar=' . $article['ID'] . '">' . $article['title'] . '</a></h2>';
        $html .= '<div class="author">Autor: <a href="?author=' . $article['authorID'] . '">' . $article['author'] . '</a>.</div>';
        $html .= '<div class="article">';
        $html .= nl2br(htmlspecialchars($article['body']));
        $html .= '</div>';

        if($tags){
            $html .= '<div class="tags">Štíky: ';
            foreach ($tags as $tag) {
                $html .= '<a href="?tag=' . $tag . '">' . $tag . '</a>, ';
            }
            $html .= '</div>';
        }

        return $html;
    }

    private function GetTagsOfArticles($articlesID){
        $implodeTags = $this->ImplodeArrayToSQL($articlesID);

        $sql = 'SELECT * FROM `tags`
            LEFT JOIN `tagsconnect`
            ON tags.ID = tagsconnect.tagID
            WHERE articleID in ' . $implodeTags;

        $tagsSQL = dibi::fetchAll($sql);
        $tags = array();

        foreach ($tagsSQL as $value) {
            $tags[$value['articleID']][] = $value['name'];
        }

        return $tags;
    }

    private function ImplodeArrayToSQL($array){
        return "('" . implode("', '", $array) . "')";
    }
}
?>
