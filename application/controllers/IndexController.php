<?php
/**
 * Index controller
 * 
 * @category  IndexController
 * @package   NotesNotes
 * @copyright Copyright (C) 2009 - Present, Alexander A. Steshenko
 * @author    Alexander A. Steshenko <lcfsoft@gmail.com> {@link http://lcf.name} 
 * @license   New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */
class IndexController extends Zend_Controller_Action
{
    /**
     * Index action. This action doesn't render anything, it just generate
     * new page code, create page if neccessary and redirect to page action.
     *
     * @return void
     */
    public function indexAction()
    {
        // Get request params
        $pageCode = $this->getRequest()->getCookie('page');
        $initialNoteParams = $this->getInvokeArg('bootstrap')
                                  ->getOption('initialnoteparams');
        
        // Access the model
        $page = NotesNotes_Service_Pages::getInstance()->createPageIfNotExist($pageCode);
        

        
        // Set up the View
        return $this->getHelper('redirector')->gotoRouteAndExit(
            array('page' => $page->getCode()), 'page-index'
        );
    }
    
    /**
     * Redirect to external address action
     * 
     * @return void
     */
    public function redirectAction()
    {
        // Get request params
        $link = $this->getRequest()->getParam('link', '/');
        
        // Redirect to external page
        return $this->getHelper('redirector')->gotoUrlAndExit(urldecode($link));
    }
}
