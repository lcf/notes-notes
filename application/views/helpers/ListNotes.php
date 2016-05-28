<?php
/**
 * View helper that convert list of domain Note objects
 * to a format that user needs to have in the response
 * 
 * @category  NotesNotes_View_Helper
 * @package   NotesNotes
 * @copyright Copyright (C) 2009 - Present, Alexander A. Steshenko
 * @author    Alexander A. Steshenko <lcfsoft@gmail.com> {@link http://lcf.name} 
 * @license   New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */
class NotesNotes_View_Helper_ListNotes extends Zend_View_Helper_Abstract
{
    /**
     * Strategy pattern
     * 
     * @param array $notes array of NotesNotes_Model_Note objects
     * @return array array with notes data
     */
    public function listNotes(array $notes)
    {
        $notesList = array();
        foreach ($notes as $note) {
            $notesList[] = $this->view->noteToArray($note);
        }
        
        return $notesList;
    }
}