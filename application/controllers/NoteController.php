<?php
/**
 * Controler which handles all notes related requests
 * 
 * @category  NoteController
 * @package   NotesNotes
 * @copyright Copyright (C) 2009 - Present, Alexander A. Steshenko
 * @author    Alexander A. Steshenko <lcfsoft@gmail.com> {@link http://lcf.name} 
 * @license   New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */
class NoteController extends Zend_Controller_Action
{
    /**
     * Action contexts
     *
     * @var array
     */
    public $contexts = array(
        'list'   => array('json'),
        'new'    => array('json'),
        'save'   => array('json'),
        'delete' => array('json'),
    );

    /**
     * Initialization of controller
     *
     * @return void
     */
    function init ()
    {            
        // init json context for actions
        $this->getHelper('contextSwitch')->initContext('json');
    }
    
    /**
     * New note action
     *
     * @return void
     */
    public function newAction()
    {
        // Get request params
        $pageCode          = $this->getRequest()->getParam('page');
        $defaultNoteParams = $this->getInvokeArg('bootstrap')
                                  ->getOption('defaultnoteparams');
        $noteParams        = array_merge($defaultNoteParams, 
                                         $this->getRequest()->getParams());

        // Access the Model
        $note = NotesNotes_Service_PageNotes::getInstance()
            ->addNoteOnPage($pageCode, $noteParams);
        
        // Setup View
        $this->view->assign('note', $this->view->noteToArray($note));
    }
    
    /**
     * List notes on the page.
     *
     * @return void
     */
    public function listAction()
    {
        // Get request params
        $pageCode = $this->getRequest()->getParam('page');
        // Access the Model
        $notes = NotesNotes_Service_PageNotes::getInstance()
            ->listNotesOnPage($pageCode);
        
        // Setup View
        $this->view->assign('notes', $this->view->listNotes($notes));
    }
    
    /**
     * Delete note on the page
     * 
     * @return void
     */
    public function deleteAction()
    {
        // Get request params
        $pageCode = $this->getRequest()->getParam('page');
        $noteId   = $this->getRequest()->getParam('note');
        
        // Access the model
        NotesNotes_Service_PageNotes::getInstance()
            ->deleteNoteOnPage($pageCode, $noteId);
    }
    
    /**
     * Save note's data
     *
     * @return void
     */
    public function saveAction ()
    {        
        // Get request params
        $pageCode         = $this->getRequest()->getParam('page');
        $noteParams       = $this->getRequest()->getParams();
        $noteParams['id'] = $this->getRequest()->getParam('note');
        
        // Access the Model
        $note = NotesNotes_Service_PageNotes::getInstance()
            ->updateNoteOnPage($pageCode, $noteParams);
    }
}