<?php
/**
 * Implementation of data mapper pattern
 * Moves Page objects between application logic and the datasource
 * 
 * @category  NotesNotes_Model
 * @package   NotesNotes
 * @copyright Copyright (C) 2009 - Present, Alexander A. Steshenko
 * @author    Alexander A. Steshenko <lcfsoft@gmail.com> {@link http://lcf.name} 
 * @license   New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */
class NotesNotes_Model_PagesMapper
{
    /**
     * Notes objects mapper
     * 
     * @var NotesNotes_Model_NotesMapper
     */
    protected $_notesMapper = null;
    
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
     * @return NotesNotes_Model_PagesMapper
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
     * Get registered gateway object instance
     *
     * Lazy loads db table object if no instance registered
     * 
     * @return Zend_Db_Table_Abstract
     */
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable(new NotesNotes_Model_DbTable_Pages());
        }
        return $this->_dbTable;
    }
    
    /**
     * Sets notes mapper
     * 
     * @param NotesNotes_Model_NotesMapper $notesMapper
     * @return void
     */
    public function setNotesMapper($notesMapper)
    {
        $this->_notesMapper = $notesMapper;
    }
    
    /**
     * Returns notes mapper
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
     * Removes page object passed from the database
     * 
     * @param NotesNotes_Model_Page $page
     * @return void
     */
    public function delete(NotesNotes_Model_Page $page)
    {
        if ($page->getId() === null) {           
            throw new NotesNotes_Model_Exception('Page can\'t be removed from the database ' 
                . 'without id property being set');
        }
        
        $this->getDbTable()->delete(array('id = ?' => (integer) $page->getId()));
    }
    
    /**
     * Saves page object passed in the database
     * 
     * @param $page
     * @return void
     */
    public function save(NotesNotes_Model_Page $page)
    {
        $data = array(
            'code' => $page->getCode(),
        );

        if ($page->getId() === null) {  
            // Inserting the record in the table
            $pageId =$this->getDbTable()->insert($data);
            // Storing new page id in the object instance
            $page->setId($pageId);
        } else {
            // Updating the record in the database
            $this->getDbTable()->update(
                $data, array('id = ?' => (integer) $page->getId())
            );
        }
    }
    /**
     * Search for a page object with code specified
     * in the database and returns it
     * 
     * @param string $code
     * @return NotesNotes_Model_Page
     */
    public function findOneByCode($code)
    {
        $rowset = $this->getDbTable()->fetchAll(array('code = ?' => (string) $code));
        if (count($rowset) == 0) {
            // Page does not exist 
            return null;
        }
        $row = $rowset->current();

        $page = new NotesNotes_Model_Page($row->code);
        $page->setId($row->id);
             
        // load notes for the page
        $notes = $this->getNotesMapper()->findAllByPageId($page->getId());
        // add notes objects to the page
        $page->addNotes($notes);
             
        return $page;
    }
}