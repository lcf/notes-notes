<?php
/**
 * Service layer that contains business logic related to
 * control over notes on a page
 * 
 * @category  NotesNotes_Service
 * @package   NotesNotes
 * @copyright Copyright (C) 2009 - Present, Alexander A. Steshenko
 * @author    Alexander A. Steshenko <lcfsoft@gmail.com> {@link http://lcf.name} 
 * @license   New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */
class NotesNotes_Service_PageNotes
{
    /**
     * Singleton instance
     *
     * @var NotesNotes_Service_PageNotes
     */
    protected static $_instance = null;
    
    /**
     * Pages data mapper
     * 
     * @var NotesNotes_Model_PagesMapper
     */
    protected $_pagesMapper = null;

    /**
     * Notes data mapper
     * 
     * @var NotesNotes_Model_NotesMapper
     */
    protected $_notesMapper = null;
    
    /**
     * Initial note params (on that is created on every 
     * new page)
     * 
     * @var array
     */
    protected $_initialNoteParams = null;
    
    /**
     * Object that validates new note params
     * 
     * @var NotesNotes_Validate_NoteCreate
     */
    protected $_noteCreateValidator = null;
    
    /**
     * Object that validates params when updating a note
     * 
     * @var NotesNotes_Validate_NoteUpdate
     */
    protected $_noteUpdateValidator = null;
    
    /**
     * Enforce singleton; Constructor
     *
     * Instantiate using {@link getInstance()}; 
     *
     * @return void
     */
    protected function __construct()
    {
    }

    /**
     * Enforce singleton; disallow cloning
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Singleton instance
     *
     * @return NotesNotes_Service_PageNotes
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Returns pages data mapper
     * 
     * @return NotesNotes_Model_PagesMapper
     */
    public function getPagesMapper()
    {
        if ($this->_pagesMapper === null) {
            $this->setPagesMapper(new NotesNotes_Model_PagesMapper());
        }
        
        return $this->_pagesMapper;
    }
    
    /**
     * Sets pages data mapper
     * 
     * @param NotesNotes_Model_PagesMapper $pagesMapper
     */
    public function setPagesMapper(NotesNotes_Model_PagesMapper $pagesMapper)
    {
        $this->_pagesMapper = $pagesMapper;
    }
    
    /**
     * Returns notes data mapper
     * 
     * @return NotesNotes_Model_NotesMapper
     */
    public function getNotesMapper()
    {
        if ($this->_notesMapper === null) {
            $this->setNotesMapper(new NotesNotes_Model_NotesMapper());
        }
        
        return $this->_notesMapper;
    }
    
    /**
     * Sets notes data mapper
     * 
     * @param NotesNotes_Model_NotesMapper $notesMapper
     * @return void
     */
    public function setNotesMapper(NotesNotes_Model_NotesMapper $notesMapper)
    {
        $this->_notesMapper = $notesMapper;
    }
    
    /**
     * Returns validator for create note parameters
     * 
     * @return Zend_Validate_Interface
     */
    public function getNoteCreateValidator()
    {
        if ($this->_noteCreateValidator === null) {
            $this->setNoteCreateValidator(new NotesNotes_Validate_NoteCreate());
        }
        
        return $this->_noteCreateValidator;
    }
    
    /**
     * Returns validator for update note parameters
     * 
     * @return Zend_Validate_Interface
     */
    public function getNoteUpdateValidator()
    {
        if ($this->_noteUpdateValidator === null) {
            $this->setNoteUpdateValidator(new NotesNotes_Validate_NoteUpdate());
        }
        
        return $this->_noteUpdateValidator;
    }
    
    /**
     * Sets validator object for note create params
     * 
     * @param Zend_Validate_Interface $validator
     * @return void
     */
    public function setNoteCreateValidator(Zend_Validate_Interface $validator)
    {
        $this->_noteCreateValidator = $validator;
    }
    
    /**
     * Sets validator object for note update params
     * 
     * @param Zend_Validate_Interface $validator
     * @return void
     */
    public function setNoteUpdateValidator(Zend_Validate_Interface $validator)
    {
        $this->_noteUpdateValidator = $validator;
    }
    
    /**
     * Remove note on the page
     * 
     * @param string $pageCode Page code
     * @param mixed $noteId Identifier of the note
     * @return void
     */
    public function deleteNoteOnPage($pageCode, $noteId)
    {
        try {
            // Retrieve page object from the data store with all its notes
            $page = $this->getPagesMapper()->findOneByCode($pageCode);
            if ($page === null) {
                // Page does not exist  (was removed or never existed)
                throw new NotesNotes_Service_Exception('Page with code ' . $pageCode
                    . ' does not exist');
            }
            
            // Ask the page to detach the note and retrieve the note object itself 
            $note = $page->detachNoteById($noteId);
            if ($note === null) {
                // Note does not exist on the page, or was removed before, return null
                return null;
            }

            // Remove note object from the data store
            $this->getNotesMapper()->delete($note);
        } catch (Exception $exception) {
            if ($exception instanceof NotesNotes_Service_Exception) {
                throw $exception;
            } else {
                // Wrap model exception into service exception
                throw new NotesNotes_Service_Exception(
                    'Can\'t delete page with code' . $pageCode, null, $exception);
            }
        }
    }
    
    /**
     * Returns sets of notes on page defined by code
     * 
     * @param string $pageCode
     * @return array
     */
    public function listNotesOnPage($pageCode)
    {
        try {
            // Retrieve page object from the data store with all its notes
            $page = $this->getPagesMapper()->findOneByCode($pageCode);
            if ($page === null) {
                // Page does not exist  (was removed or never existed)
                throw new NotesNotes_Service_Exception('Page with code ' . $pageCode
                    . ' does not exist');
            }
            
            return $page->getNotes();
        } catch (Exception $exception) {
            if ($exception instanceof NotesNotes_Service_Exception) {
                throw $exception;
            } else {
                // Wrap model exception into service exception
                throw new NotesNotes_Service_Exception(
                    'Can\'t list notes on page with code' . $pageCode, null, $exception);
            }
        }
    }
    
    /**
     * Creates Zend_View instance for internal usage
     * 
     * @return Zend_View
     */
    protected function _getView()
    {
        return new Zend_View(array('scriptPath' => APPLICATION_PATH . '/views/scripts/'));
    }
    
    /**
     * Renders view script with hello note text
     * 
     * @param string $pageCode page code that may be used in the view script
     * @return string result text of hello note
     */
    protected function _renderInitialNoteText($pageCode)
    {
        return $this->_getView()
            ->assign('page', $pageCode)
            ->render('initial-note.phtml');
    }
    
    /**
     * Sets parameters for initial note
     * 
     * @param array $initialNoteParams
     * @return void
     */
    public function setInitialNoteParams($initialNoteParams)
    {
        $this->_initialNoteParams = $initialNoteParams;
    }
    
    /**
     * Returns initial note params
     * 
     * @return array
     */
    protected function getInitialNoteParams()
    {
        if ($this->_initialNoteParams === null) {
            throw new NotesNotes_Service_Exception('Inital note params haven\'t been set');
        }
        return $this->_initialNoteParams;
    }
    
    /**
     * Adds initial note on page defined by code
     * 
     * @param string $pageCode
     * @return NotesNotes_Model_Note
     */
    public function addInitialNoteOnPage($pageCode)
    {
        $initialNoteParams = $this->getInitialNoteParams();
        
        if (!isset($initialNoteParams['text'])) {
            $initialNoteParams['text'] = $this->_renderInitialNoteText($pageCode);
        }        
        
        return $this->addNoteOnPage($pageCode, $initialNoteParams); 
    }
    
    /**
     * Create new note based on data passed on the page specified by code
     * 
     * Allowed options for $noteData array are:
     * - title  Note's title
     * - text   Text content of the note 
     * - left   Left position of the note on the page
     * - top    Top position of the note
     * - width  Width of the note
     * - height Height of the note
     * - color  hexadecimal color string like FFAA66
     * 
     * 
     * @param string $pageCode Page code
     * @param array $noteData Data passed by user needed to create new note
     * @return NotesNotes_Model_Note New note object
     */
    public function addNoteOnPage($pageCode, $noteData)
    {
        try {
            // Retrieve page object from the data store with all its notes
            $page = $this->getPagesMapper()->findOneByCode($pageCode);
            if ($page === null) {
                // Page does not exist  (was removed or never existed)
                throw new NotesNotes_Service_Exception('Page with code ' . $pageCode
                    . ' does not exist');
            }

            $validator = $this->getNoteCreateValidator();
            if ($validator->isValid($noteData)) {
                $options = array(
                    'title'  => $validator->getValue('title'),
                    'text'   => $validator->getValue('text'),
                    'left'   => $validator->getValue('left'),
                    'top'    => $validator->getValue('top'),
                    'height' => $validator->getValue('height'),
                    'width'  => $validator->getValue('width'),
                    'color'  => new NotesNotes_Model_Color(
                        $validator->getValue('color')
                    ),
                );
            } else {
                // In our application invalidity of new note params is actually
                // an exceptional situation, so we throw one
                throw new NotesNotes_Service_Exception('Invalid note params');
            }
            
            // Create new note object
            $note = new NotesNotes_Model_Note($options);
            // Attach it to the page
            $page->addNote($note);
            // Save note in the data store
            $this->getNotesMapper()->save($note);
            // Return new note object itself
            return $note;
        } catch (Exception $exception) {
            if ($exception instanceof NotesNotes_Service_Exception) {
                throw $exception;
            } else {
                // Wrap model exception into service exception
                throw new NotesNotes_Service_Exception(
                    'Can\'t add note on page with code' . $pageCode, null, $exception);
            }
        }
    }
    
    
    /**
     * Updated a note based on data passed on the page specified by code
     * 
     * Allowed options for $noteData array are:
     * - id     Note's identifier, this options is required
     * - title  Note's title
     * - text   Text content of the note 
     * - left   Left position of the note on the page
     * - top    Top position of the note
     * - width  Width of the note
     * - height Height of the note
     * - color  hexadecimal color string like FFAA66
     * 
     * @param string $pageCode Page code
     * @param array $noteData Data passed by user needed to update the note
     * @return NotesNotes_Model_Note New note object
     */
    public function updateNoteOnPage($pageCode, $noteData)
    {
        try {
            // Retrieve page object from the data store with all its notes
            $page = $this->getPagesMapper()->findOneByCode($pageCode);
            if ($page === null) {
                // Page does not exist  (was removed or never existed)
                throw new NotesNotes_Service_Exception('Page with code ' . $pageCode
                    . ' does not exist');
            }

            $validator = $this->getNoteUpdateValidator();
            if ($validator->isValid($noteData)) {
                
                $noteId = $validator->getValue('id');
                $note = $page->getNoteById($noteId);
                if ($note === null) {
                    // Note does not exist on the page
                    throw new NotesNotes_Service_Exception('Page with code ' . $pageCode
                        . ' does not contain note with id ' . $noteId);
                }
                
                // Update note object properties
                if (($titleValue = $validator->getValue('title')) !== null) {
                    $note->setTitle($titleValue);
                }
                if (($textValue = $validator->getValue('text')) !== null) {
                    $note->setText($textValue);
                }
                if (($leftValue = $validator->getValue('left')) !== null) {
                    $note->setLeft($leftValue);
                }
                if (($topValue = $validator->getValue('top')) !== null) {
                    $note->setTop($topValue);
                }
                if (($widthValue = $validator->getValue('width')) !== null) {
                    $note->setWidth($widthValue);
                }
                if (($heightValue = $validator->getValue('height')) !== null) {
                    $note->setHeight($heightValue);
                }
                if (($colorValue = $validator->getValue('color')) !== null) {
                    $note->setColor(new NotesNotes_Model_Color($colorValue));
                }
            } else {
                // In our application invalidity of note params is actually
                // an exceptional situation, so we throw one
                throw new NotesNotes_Service_Exception('Invalid note params');
            }
            
            // Save note in the data store
            $this->getNotesMapper()->save($note);
            // Return note object itself
            return $note;
        } catch (Exception $exception) {
            if ($exception instanceof NotesNotes_Service_Exception) {
                throw $exception;
            } else {
                // Wrap model exception into service exception
                throw new NotesNotes_Service_Exception(
                    'Can\'t update note on page with code' . $pageCode, null, $exception);
            }
        }
    }
}