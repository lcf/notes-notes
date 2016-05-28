<?php
/**
 * View helper that convert a domain Note object
 * to a format that user needs to have in the response
 * 
 * @category  NotesNotes_View_Helper
 * @package   NotesNotes
 * @copyright Copyright (C) 2009 - Present, Alexander A. Steshenko
 * @author    Alexander A. Steshenko <lcfsoft@gmail.com> {@link http://lcf.name} 
 * @license   New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */
class NotesNotes_View_Helper_NoteToArray extends Zend_View_Helper_Abstract
{
    /**
     * Strategy pattern
     * 
     * @param NotesNotes_Model_Note $note Note domain object
     * @return array note date
     */
    public function noteToArray(NotesNotes_Model_Note $note)
    {
        return array(
            'id'      => $note->getId(),
            'title'   => $note->getTitle(),
            'text'    => $note->getText(),
            'left'    => $note->getLeft(),
            'top'     => $note->getTop(),
            'width'   => $note->getWidth(),
            'height'  => $note->getHeight(),
            'color'   => $note->getColor()->getHexadecimalString(),
        );
    }
}