<?php
/**
 * Implementation of data mapper pattern
 * Moves Note objects between application logic and the datasource
 * 
 * @category  NotesNotes_Model
 * @package   NotesNotes
 * @copyright Copyright (C) 2009 - Present, Alexander A. Steshenko
 * @author    Alexander A. Steshenko <lcfsoft@gmail.com> {@link http://lcf.name} 
 * @license   New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */
class NotesNotes_Model_NotesMapper
{
    /**
     * Table gateway object instance
     * 
     * @var Zend_Db_Table_Abstract
     */
    protected $_dbTable;
    
    /**
     * Specify Zend_Db_Table instance to use for data operations
     * 
     * @param  Zend_Db_Table_Abstract $dbTable 
     * @return NotesNotes_Model_NotesMapper
     */
    public function setDbTable($dbTable)
    {
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new NotesNotes_Model_Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    /**
     * Get registered Zend_Db_Table instance
     *
     * Lazy loads db table object if no instance registered
     * 
     * @return Zend_Db_Table_Abstract
     */
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable(new NotesNotes_Model_DbTable_Notes());
        }
        return $this->_dbTable;
    }
    
    /**
     * Create new Note instance based on data passed
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
     * @param  array $noteData note data
     * @return NotesNotes_Model_Note new Note object
     */
    protected function _createNoteFromData($noteData)
    {
        $note = new NotesNotes_Model_Note();
        if (isset($noteData['id'])) {
            $note->setId($noteData['id']);
        }
        if (isset($noteData['title'])) {
            $note->setTitle($noteData['title']);
        }
        if (isset($noteData['text'])) {
            $note->setText($noteData['text']);
        }
        if (isset($noteData['left'])) {
            $note->setLeft($noteData['left']);
        }
        if (isset($noteData['top'])) {
            $note->setTop($noteData['top']);
        }
        if (isset($noteData['width'])) {
            $note->setWidth($noteData['width']);
        }
        if (isset($noteData['height'])) {
            $note->setHeight($noteData['height']);
        }
        if (isset($noteData['color'])) {
            $note->setColor(new NotesNotes_Model_Color($noteData['color']));
        }

        return $note;
    }
    
    /**
     * Saves the Note object passed in the database
     * 
     * @param NotesNotes_Model_Note $note
     * @return void
     */
    public function save(NotesNotes_Model_Note $note)
    {
        if ($note->getPage() === null) {
            throw new NotesNotes_Model_Exception('Note can\'t be saved in the database ' 
                . 'without being attached to a page');
        }
        
        $data = array( 
            'title'   => $note->getTitle(),
            'text'    => $note->getText(),
            'left'    => $note->getLeft(),
            'top'     => $note->getTop(),
            'width'   => $note->getWidth(),
            'height'  => $note->getHeight(),
            'color'   => $note->getColor()->getHexadecimalString(),
            'page_id' => $note->getPage()->getId(),
        );

        if ($note->getId() === null) {  
            // Inserting the record in the table
            $noteId = $this->getDbTable()->insert($data);
            // Storing new note id in the object instance
            $note->setId($noteId);
        } else {
            // Updating the record in the database
            $this->getDbTable()->update(
                $data, array('id = ?' => (integer) $note->getId())
            );
        }
    }
    
    /**
     * Removes object passed from the database
     * 
     * @param NotesNotes_Model_Note $note
     * @return void
     */
    public function delete(NotesNotes_Model_Note $note)
    {
        if ($note->getId() === null) {       
            throw new NotesNotes_Model_Exception('Note can\'t be removed from the database ' 
                . 'without id property being set');
        }
        
        $this->getDbTable()->delete(array('id = ?' => (integer) $note->getId()));
    }
    
    /**
     * Search for the Note object with specified id
     * 
     * @param mixed $id
     * @return NotesNotes_Model_Note
     */
    public function findOneById($id)
    {
        $rowset = $this->getDbTable()->find($id);
        if (count($rowset) == 0) {
            // Note wasn't found
            return null;
        }
        $row = $rowset->current();

        return $this->_createNoteFromData($row->toArray());
    }
    
    /**
     * Search for all notes that are attached to the page
     * with id specified
     * 
     * @param mixed $pageId
     * @return array
     */
    public function findAllByPageId($pageId)
    {
        $rowset = $this->getDbTable()->fetchAll(array('page_id = ?' => (integer) $pageId));
        $notes  = array();
        foreach ($rowset as $row) {
            $notes[] = $this->_createNoteFromData($row->toArray());
        }
        return $notes;
    }
}