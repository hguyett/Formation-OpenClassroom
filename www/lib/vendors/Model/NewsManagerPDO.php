<?php
namespace Model;
use Entity\News;
use PDO;
use DateTime;
use DateTimeZone;
use RuntimeException;
use NotFoundException;

/**
 * Manage News in the database using PDO objects.
 */
class NewsManagerPDO extends NewsManager
{

    /**
     * Establish the connection to the MySQL database.
     * @param PDO PDO connection to the MySQL database.
     */
    public function setDao(PDO $dao)
    {
        $this->dao = $dao;
    }


    /**
     * Add a news in the database. Return true if operation succeed.
     * @access protected
     * @param News $news News to add.
     * @return bool
     */
    protected function add(News $news) : bool
    {
        try {
            $query = $this->dao->prepare('INSERT INTO news (author, title, content, dateAdded) VALUES (:author, :title, :content, NOW())');
            $query->bindValue(':author', $news->getAuthor(), PDO::PARAM_STR);
            $query->bindValue(':title', $news->getTitle(), PDO::PARAM_STR);
            $query->bindValue(':content', $news->getContent(), PDO::PARAM_STR);
            return $query->execute();
        } catch (PDOException $e) {
            return false;
        }
    }


    /**
     * Return a news from the database.
     * @access public
     * @param  int  $id of the news to return.
     * @return News
     * @throws NotFoundException If no data is found, get() throws a NotFoundException (404).
     */
    public function get(int $id) : News
    {
        $query = $this->dao->prepare('SELECT * FROM news WHERE id = :id');
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $newsData = $query->fetch();
        $query->closeCursor();
        if ($newsData) {
            $news = $this->arrayToNews($newsData);
        } else {
            throw new NotFoundException();
        }
        return $news;
    }


    /**
     * Return a list of news from the database ordered by decreasing creation date.
     * @access public
     * @param  int  $numberOfNews  Number of news to return.
     * @param  int  $startPosition Define at which position to start selecting news.
     * @return array Array of news.
     */
    public function getList(int $numberOfNews = null, ?int $startPosition = null) : array
    {
        /**
         * @var PDOStatement $query
         */
        $query = null;
        $queryString = 'SELECT * FROM news ORDER BY dateAdded DESC';

        switch (func_num_args()) {
            case 0 :
                $query = $this->dao->prepare($queryString);
                break;

            case 1 :
                $queryString .= ' LIMIT :limitNumber';
                $query = $this->dao->prepare($queryString);
                $query->bindValue(':limitNumber', $numberOfNews, PDO::PARAM_INT);
                break;

            case 2 :
                $queryString .= ' LIMIT :firstLimitNumber, :secondLimitNumber';
                $query = $this->dao->prepare($queryString);
                $query->bindValue(':firstLimitNumber', $startPosition, PDO::PARAM_INT);
                $query->bindValue(':secondLimitNumber', $numberOfNews, PDO::PARAM_INT);
                break;

            default:
                throw new RuntimeException("Too many arguments in PDONewsManager getList()");
                break;
            }

        $query->execute();
        $newsArrayData = $query;
        $newsArrayData->setFetchMode(PDO::FETCH_ASSOC);
        $newsArray = $newsArrayData->fetchAll();
        $newsArrayData->closeCursor();
        foreach ($newsArray as $news => &$values) {
            $values = $this->arrayToNews($values);
        }
        return $newsArray;
    }


    /**
     * Update a news in the database. Return true if operation succeed.
     * @access protected
     * @param  News    $news News to update.
     * @return bool
     */
    protected function update(News  $news) : bool
    {
        // try {
            $query = $this->dao->prepare('UPDATE news SET author = :author, title = :title, content =:content, dateEdited = NOW() WHERE id = :id');
            $query->bindValue(':id', $news->getId(), PDO::PARAM_INT);
            $query->bindValue(':author', $news->getAuthor(), PDO::PARAM_STR);
            $query->bindValue(':title', $news->getTitle(), PDO::PARAM_STR);
            $query->bindValue(':content', $news->getContent(), PDO::PARAM_STR);
            return $query->execute();
        // } catch (PDOException $e) {
        //     return false;
        // }
    }


    /**
     * Delete a news in the database. Return true if operation succeed.
     * @access public
     * @param  News    $news News to delete.
     * @return bool
     */
    public function delete(News  $news) : bool
    {
        try {
            $query = $this->dao->prepare('DELETE FROM news WHERE id = :id');
            $query->bindValue(':id', $news->getId(), PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            return false;
        }
    }


    /**
     * Save (add or update) a news in the database. Return true if operation succeed.
     * @param  News  $news News to save.
     * @return bool
     */
    public function save(News $news) : bool
    {
        if ($news->isValid()) {
            return ($news->isNew()) ? $this->add($news) : $this->update($news);
        }
    }

    /**
     * Return a News built with data cointained in the array. arrayToNews will also transform String formatted date into DateTime objects.
     * @param  array $newsData
     * @return News
     */
    protected function arrayToNews(array $newsData) : News
    {
        if (isset($newsData['dateAdded']) and is_string($newsData['dateAdded'])) {
            $newsData['dateAdded'] = new DateTime($newsData['dateAdded'], new DateTimeZone('Europe/Brussels'));
        }
        if (isset($newsData['dateEdited']) and is_string($newsData['dateEdited'])) {
            $newsData['dateEdited'] = new DateTime($newsData['dateEdited'], new DateTimeZone('Europe/Brussels'));
        }

        return new News($newsData);
    }
}