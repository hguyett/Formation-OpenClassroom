<?php
/**
 * A news have an ID for the database storage, an author, a title, a content, a creation date and an edition date.
 * Code skeleton generated by dia-uml2php5 plugin
 * written by KDO kdo@zpmag.com
 */

class News
{

	/**
	 * ID number.
	 * @var int
	 * @access protected
	 */
	protected  $id;

	/**
	 * Author of the news.
	 * @var String
	 * @access protected
	 */
	protected  $author;

	/**
	 * Title of the news.
	 * @var String
	 * @access protected
	 */
	protected  $title;

	/**
	 * Content of the news.
	 * @var String
	 * @access protected
	 */
	protected  $content;

	/**
	 * Date indicating when the news has been added.
	 * @var DateTime
	 * @access protected
	 */
	protected  $dateAdded;

	/**
	 * Date indicating when the news has been edited for the last time.
	 * @var DateTime
	 * @access protected
	 */
	protected  $dateEdited = null;


	/**
	 * @access public
	 * @param array $dataArray Associative array containing properties values.
	 */

	public function __construct($dataArray = null)
    {
        if (isset ($dataArray)) {
            $this->hydrate($dataArray);
        }
	}


	/**
	 * Hydrate the object.
	 * @access public
	 * @param array $dataArray Associative array containing properties values.
	 */

	public function hydrate($dataArray)
    {
        foreach ($dataArray as $property => $value) {
            $setterMethod = 'set' . ucfirst($property);
            if (method_exists($this, $setterMethod)) {
                $this->$setterMethod($value);
            }
        }
	}


    /**
     * Return true if the news have not an ID. Else, return false.
     * @return bool
     */
    public function isNew() : bool
    {
        return empty($this->id);
    }


    /**
     * Return true if the news has an author, a title and a content. Else, retourn false.
     * @return bool
     */
    public function isValid() : bool
    {
        return !(empty($this->author) or empty($this->title) or empty($this->content));
    }


	/**
	 * Return the ID of the news.
	 * @access public
	 * @return int
	 */

	public function getId() : int
    {
        return $this->id;
	}


	/**
	 * Return the author of the news.
	 * @access public
	 * @return String
	 */

	public function getAuthor() : String
    {
        return $this->author;
	}


	/**
	 * Return the title of the news.
	 * @access public
	 * @return String
	 */

	public function getTitle() : String
    {
        return $this->title;
	}


	/**
	 * Return the content of the news.
	 * @access public
	 * @return String
	 */

	public function getContent() : String
    {
        return $this->content;
	}


	/**
	 * Return the creation date of the news.
	 * @access public
	 * @return DateTime
	 */

	public function getDateAdded() : DateTime
    {
        return $this->dateAdded;
	}


	/**
	 * Return the last edition date of the news or null if not set.
	 * @access public
	 * @return mixed DateTime or null if not set.
	 */

	public function getDateEdited()
    {
        return $this->dateEdited;
	}


	/**
	 * Set the ID.
	 * @access private
	 * @param int $id ID to set to the news.
	 */

	public function setId(int $id)
    {
        $this->id = $id;
	}


	/**
	 * Author to set to the news.
	 * @access public
	 * @param String $author Author to set to the news.
	 */

	public function setAuthor(String $author)
    {
        $this->author = $author;
	}


	/**
	 * Set the Title.
	 * @access public
	 * @param String $title Title to set to the news.
	 */

	public function setTitle(String $title)
    {
        $this->title = $title;
	}


	/**
	 * Set the content.
	 * @access public
	 * @param String $content Content to set to the news.
	 */

	public function setContent(String $content)
    {
        $this->content = $content;
	}


	/**
	 * Set the creation date.
	 * @access public
	 * @param DateTime $dateAdded Creation date to set to the news.
	 */

	public function setDateAdded(DateTime $dateAdded)
    {
        $this->dateAdded = $dateAdded;
	}


	/**
	 * Set the last edition date.
	 * @access public
	 * @param DateTime $dateEdited Edition date to set to the news.
	 */

	public function setDateEdited(DateTime $dateEdited = null)
    {
        $this->dateEdited = $dateEdited;
	}
    
}