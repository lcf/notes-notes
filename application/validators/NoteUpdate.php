<?php
/**
 * Object that validates user input while updating a note
 * 
 * @category  NotesNotes_Validate
 * @package   NotesNotes
 * @copyright Copyright (C) 2009 - Present, Alexander A. Steshenko
 * @author    Alexander A. Steshenko <lcfsoft@gmail.com> {@link http://lcf.name} 
 * @license   New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */
class NotesNotes_Validate_NoteUpdate extends Zend_Form implements Zend_Validate_Interface
{
    /**
     * Initialization of validator elements set
     * 
     * @return void
     */
    public function init()
    {
        $id = new NotesNotes_Validate_Element('id');
        $id->setRequired(true);
        
        $title = new NotesNotes_Validate_Element('title');
        $title->setRequired(false);
        
        $text = new NotesNotes_Validate_Element('text');

        $left = new NotesNotes_Validate_Element('left');
        $left->setRequired(false)
             ->addValidator(new Zend_Validate_Between(0, 5000));
              
        $top = new NotesNotes_Validate_Element('top');
        $top->setRequired(false)
            ->addValidator(new Zend_Validate_Between(0, 5000));        
        
        $width = new NotesNotes_Validate_Element('width');
        $width->setRequired(false)
              ->addValidator(new Zend_Validate_Between(150, 5000));
        
        $height = new NotesNotes_Validate_Element('height');
        $height->setRequired(false)
               ->addValidator(new Zend_Validate_Between(150, 5000));
              
        $color = new NotesNotes_Validate_Element('color');
        $color->setRequired(false)
              ->addValidator(new Zend_Validate_Regex('/^[a-f0-9]{6}$/i'));

        $this->addElements(array(
            $id, $title, $text, $left, 
            $top, $width, $height, $color
        ));
    }
}