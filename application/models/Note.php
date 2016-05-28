<?php
/**
 * Model that represents note object
 * implements identifiable interface
 * 
 * @category  NotesNotes_Model
 * @package   NotesNotes
 * @copyright Copyright (C) 2009 - Present, Alexander A. Steshenko
 * @author    Alexander A. Steshenko <lcfsoft@gmail.com> {@link http://lcf.name} 
 * @license   New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */
class NotesNotes_Model_Note implements NotesNotes_Model_Identifiable
{
    /**
     * Entity identifier
     * 
     * @var mixed
     */
    protected $_id = null;
    
    /**
     * Note's title
     * 
     * @var string
     */
    protected $_title = null;
    
    /**
     * Note's text
     * 
     * @var string
     */
    protected $_text = null;
    
    /**
     * Page object the note is attached to
     * 
     * @var NotesNotes_Model_Page
     */
    protected $_page = null;
    
    /**
     * Object that represents color of the note's title
     * 
     * @var NotesNotes_Model_Color
     */
    protected $_color = null;
    
    /**
     * Widht of the note
     * 
     * @var integer
     */
    protected $_width = null;
    
    /**
     * Height of the note
     * 
     * @var integer
     */
    protected $_height = null;
    
    /**
     * Top position of the note
     * 
     * @var integer
     */
    protected $_top = null;
    
    /**
     * Left position of the note
     * 
     * @var integer
     */
    protected $_left = null;
    
    /**
     * Sets note properties based on options array passed
     * Allowed options are:
     * - id     Note identifier
     * - title  Note's title
     * - text   Text content of the note 
     * - left   Left position of the note on the page
     * - top    Top position of the note
     * - width  Width of the note
     * - height Height of the note
     * - color  NotesNotes_Model_Color object
     * 
     * @param array $options Array of note properties
     * @return void
     */
    public function __construct(array $options = null)
    {
        if ($options !== null) {
            $this->setOptions($options);
        }
    }
    
    /**
     * Returns entity id
     * 
     * @return mixed $id
     */
    public function getId ()
    {
        return $this->_id;
    }

    /**
     * Sets entity id
     * 
     * @param mixed $id
     * @return NotesNotes_Model_Note
     */
    public function setId ($id)
    {
        $this->_id = $id;
        return $this;
    }
    
    /**
     * Returns title of the note
     *
     * @return string
     */
    public function getTitle ()
    {
        return $this->_title;
    }

    /**
     * Returns text of the note
     *
     * @return string
     */
    public function getText ()
    {
        return $this->_text;
    }

    /**
     * Returns page object the not is attached to
     *
     * @return NotesNotes_Model_Page
     */
    public function getPage ()
    {
        return $this->_page;
    }

    /**
     * Returns left value
     *
     * @return integer
     */
    public function getLeft ()
    {
        return $this->_left;
    }

    /**
     * Returns top value
     *
     * @return integer
     */
    public function getTop ()
    {
        return $this->_top;
    }

    /**
     * Returns width value
     *
     * @return integer
     */
    public function getWidth ()
    {
        return $this->_width;
    }

    /**
     * Returns height value
     *
     * @return integer
     */
    public function getHeight ()
    {
        return $this->_height;
    }

    /**
     * Returns color object
     *
     * @return NotesNotes_Model_Color
     */
    public function getColor ()
    {
        return $this->_color;
    }

    /**
     * Sets title of the note
     *
     * @param string $title
     * @return NotesNotes_Model_Page
     */
    public function setTitle ($title)
    {
        $this->_title = (string) $title;
        return $this;
    }

    /**
     * Sets text of the note
     *
     * @param string $text
     * @return NotesNotes_Model_Page
     */
    public function setText ($text)
    {
        $this->_text = (string) $text;
        return $this;
    }

    /**
     * Sets page object
     *
     * @param NotesNotes_Model_Page $page
     * @return NotesNotes_Model_Page
     */
    public function setPage (NotesNotes_Model_Page $page = null)
    {
        $this->_page = $page;
        return $this;
    }

    /**
     * Sets left
     *
     * @param integer $left
     * @return NotesNotes_Model_Page
     */
    public function setLeft ($left)
    {
        $this->_left = (integer) $left;
        return $this;
    }

    /**
     * Sets top value
     *
     * @param integer $top
     * @return NotesNotes_Model_Page
     */
    public function setTop ($top)
    {
        $this->_top = (integer) $top;
        return $this;
    }

    /**
     * Sets width
     *
     * @param integer $width
     * @return NotesNotes_Model_Page
     */
    public function setWidth ($width)
    {
        $this->_width = (integer) $width;
        return $this;
    }

    /**
     * Sets height value
     *
     * @param integer $height
     * @return NotesNotes_Model_Page
     */
    public function setHeight ($height)
    {
        $this->_height = (integer) $height;
        return $this;
    }

    /**
     * Sets color object
     *
     * @param NotesNotes_Model_Color $color
     * @return NotesNotes_Model_Page
     */
    public function setColor (NotesNotes_Model_Color $color = null)
    {
        $this->_color = $color;
        return $this;
    }
    
    /**
     * Dettach note from the page this note is attached to
     * and return note itself 
     *
     * @return NotesNotes_Model_Note
     */
    public function removeFromPage()
    {
        if ($this->getPage() !== null) {
            $this->getPage()->detachNoteById($this->getId());
        }
        
        return $this;
    }
    
    /**
     * Sets note properties based on options array passed
     * Allowed options are:
     * - id     Note identifier
     * - title  Note's title
     * - text   Text content of the note 
     * - left   Left position of the note on the page
     * - top    Top position of the note
     * - width  Width of the note
     * - height Height of the note
     * - color  NotesNotes_Model_Color object
     * 
     * @param array $options Array of note properties
     * @return NotesNotes_Model_Note
     */
    public function setOptions(array $options)
    {
        foreach ($options as $option => $value) {
            switch ($option) {
                case 'id':
                    $this->setId($value);
                    break;
                case 'title':
                    $this->setTitle($value);
                    break;
                case 'text':
                    $this->setText($value);
                    break;
                case 'left':
                    $this->setLeft($value);
                    break;
                case 'top':
                    $this->setTop($value);
                    break;
                case 'width':
                    $this->setWidth($value);
                    break;
                case 'height':
                    $this->setHeight($value);
                    break;
                case 'color':
                    $this->setColor($value);
                    break;
                default:
                    break;
            }
        }

        return $this;
    }
}