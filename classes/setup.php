<?php
/**
 * 
 */
class setup{
    public static function createTables(){
        $authors = '
            CREATE TABLE IF NOT EXISTS `authors`(
                ID INT UNSIGNED AUTO_INCREMENT,
                name VARCHAR(255) UNIQUE,
                email VARCHAR(255) UNIQUE,
                PRIMARY KEY (ID)
            )
            ';

        $articles = '
            CREATE TABLE IF NOT EXISTS `articles`(
                ID INT UNSIGNED AUTO_INCREMENT,
                title VARCHAR(255) UNIQUE,
                body TEXT,
                authorID INT,
                PRIMARY KEY (ID),
                FOREIGN KEY (authorID) REFERENCES authors(ID)
            )
            ';

        $tags = '
            CREATE TABLE IF NOT EXISTS `tags`(
                ID INT UNSIGNED AUTO_INCREMENT,
                name VARCHAR(50) UNIQUE,
                PRIMARY KEY (ID)
            )
            ';

        $tagsConnect = '
            CREATE TABLE IF NOT EXISTS `tagsconnect`(
                tagID INT UNSIGNED,
                articleID INT UNSIGNED,
                PRIMARY KEY (tagID, articleID),
                FOREIGN KEY (tagID) REFERENCES tags(ID),
                FOREIGN KEY (articleID) REFERENCES articles(ID)
            )
            ';

        dibi::query($authors);
        dibi::query($articles);
        dibi::query($tags);
        dibi::query($tagsConnect);
    }
}
?>
