<?php
/**
 * Validation object that represents one element that may belong to user input
 * 
 * @category  NotesNotes_Validate
 * @package   NotesNotes
 * @copyright Copyright (C) 2009 - Present, Alexander A. Steshenko
 * @author    Alexander A. Steshenko <lcfsoft@gmail.com> {@link http://lcf.name} 
 * @license   New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */
class NotesNotes_Validate_Element extends Zend_Form_Element implements Zend_Validate_Interface
{
    /**
     * Initialization of element.
     * 
     * @return void
     */
    public function init()
    {
        $this->setDisableLoadDefaultDecorators(true)
             ->setAutoInsertNotEmptyValidator(false)
             ->setAllowEmpty(true);
    }
}