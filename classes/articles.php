<?php
/**
 * 
 */
class articles extends website {
    public function  __construct() {
        parent::__construct();
        $this->Init();
    }

    protected function Init(){
        $this->SetVariable('title', 'Přehled všech článků');
        $this->SetVariable('articles', $this->GetHTMLArticles());
        parent::Init();
    }

    private function GetAllArticles(){
        $sql = 'SELECT * FROM articles';
        $res = dibi::fetchAll($sql);
        return $res;
    }

    private function GetHTMLArticles(){
        $articles = $this->GetAllArticles();
        if($articles){
            $html = '<table>';
            $html .= '<thead><tr><th>Název článku</th><th>Autor</th></tr></thead>';

            foreach ($articles as $article) {
                $html .= '<tr class="fadeOut_' . $article->ID . '">';
                $html .= '<td><a href="?ar=' . $article->ID . '">' . $article->title . '</td>';
                $html .= '<td>' . $this->GetAuthorName($article->authorID) . '</td>';
                $html .= '<td><a href="?page=admin&amp;section=editarticle&amp;id=' . $article->ID . '">editovat</a></td>';
                $html .= '<td class="delete"><a href="?page=admin&amp;section=deletearticle&amp;id=' . $article->ID . '" class="' . $article->ID . '">smazat</a></td>';
                $html .= '</tr>';
            }

            $html .= '</table>';
        } else {
            $html = '<p>Nebyly nalezeny žádné články.</p>';
        }
        return $html;
    }

    private function GetAuthorName($ID){
        $sql = 'SELECT name FROM `authors` WHERE ID = %i';
        $res = dibi::fetch($sql, $ID)->name;
        if($res) {
            return $res;
        } else {
            return 'neznámý';
        }
    }
}
?>
