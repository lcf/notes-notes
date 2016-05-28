<?php
/**
 * Application bootstrap class
 * 
 * @category  scripts
 * @package   NotesNotes
 * @copyright Copyright (C) 2009 - Present, Alexander A. Steshenko
 * @author    Alexander A. Steshenko <lcfsoft@gmail.com> {@link http://lcf.name} 
 * @license   New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */
class NotesNotes_Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    
    /**
     * Bootstrap autoloader for application resources
     * 
     * @return Zend_Application_Module_Autoloader
     */
    protected function _initAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'NotesNotes_',
            'basePath'  => APPLICATION_PATH
        ));
        $autoloader->addResourceType('validator', 'validators', 'Validate');
        
        return $autoloader;
    }

    /**
     * Bootstrap the view doctype
     * 
     * @return void
     */
    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }

    /**
     * Bootstrap page notes service
     * 
     * @return void
     */
    protected function _initPageNotesService()
    {
        // Set initial note params
        NotesNotes_Service_PageNotes::getInstance()
            ->setInitialNoteParams($this->getOption('initialnoteparams'));
    }
    
    /**
     * Bootstrap the Response
     * 
     * @return void
     */    
    protected function _initResponse()
    {
        $this->bootstrap('frontController');
        $frontController = $this->getResource('frontController');                

        $response = new Zend_Controller_Response_Http();
        $response->setHeader("Cache-control", "no-cache");

        $frontController->setResponse($response);
    }    
}