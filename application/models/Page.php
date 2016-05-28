<?php
/**
 * Model that represents page object
 * implements identifiable interface
 * 
 * @category  NotesNotes_Model
 * @package   NotesNotes
 * @copyright Copyright (C) 2009 - Present, Alexander A. Steshenko
 * @author    Alexander A. Steshenko <lcfsoft@gmail.com> {@link http://lcf.name} 
 * @license   New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */
class NotesNotes_Model_Page implements NotesNotes_Model_Identifiable
{
    /**
     * Page id
     * 
     * @var mixed
     */    
    protected $_id = null;
    
    /**
     * Page code
     * 
     * @var string
     */
    protected $_code = null;

    /**
     * Notes
     * array of NotesNotes_Model_Note objects
     * 
     * @var array
     */
    protected $_notes = array();
    
    /**
     * Constructor
     * 
     * @param string $code
     * @param array $notes
     * @return void
     */
    public function __construct($code, array $notes = array())
    {
        $this->setCode($code);
        $this->addNotes($notes);
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
     * Returns page code
     * 
     * @return string
     */
    public function getCode()
    {
        return $this->_code;
    }

    /**
     * Sets page code
     * 
     * @param string $code
     * @return NotesNotes_Model_Page
     */
    public function setCode($code)
    {
        $this->_code = (string) $code;
        return $this;
    }
    
    /**
     * Adds set of notes
     * 
     * @param array $notes
     * @return void
     */
    public function addNotes(array $notes)
    {
        foreach ($notes as $note) {
            $this->addNote($note);
        }
    }
    
    /**
     * Returns notes on the page
     * 
     * @return array Array of NotesNotes_Model_Note objects
     */
    public function getNotes()
    {
        return $this->_notes;
    }
    
    /**
     * Adds a note to the page
     *
     * @param NotesNotes_Model_Note $note
     * @return void
     */
    public function addNote(NotesNotes_Model_Note $note)
    {
        // Firstly, set page object into note
        $note->setPage($this);
        
        // Add note to the notes array
        $this->_notes[] = $note;
    }
    
    /**
     * Dettach note from the page this note is attached to
     * and return note itself 
     *
     * @param mixed $noteId
     * @return NotesNotes_Model_Note
     */
    public function detachNoteById($noteId)
    {
        foreach ($this->_notes as $key => $note) {
            if ($note->getId() == $noteId) {
                // Note is found, remove it from the page
                unset($this->_notes[$key]);
                // set page to null that means that note is dettached 
                $note->setPage(null);
                // and return note itself
                return $note; 
            }
        }
        
        // Note wasn't found during cycle, return null
        return null;
    }
    
    /**
     * Check id property for all notes
     * and returns first matching.
     *  
     * Returns null if note with id specified 
     * does not exist on this page
     *
     * @param mixed $noteId
     * @return NotesNotes_Model_Note
     */
    public function getNoteById($noteId)
    {
        foreach ($this->_notes as $note) {
            if ($note->getId() == $noteId) {
                // Note is found, return it
                return $note; 
            }
        }
        
        // Note wasn't found during checking, return null
        return null;
    }
}