<?php
/**
 * Controler which handles all requests related with pages managment
 * 
 * @category  PageController
 * @package   NotesNotes
 * @copyright Copyright (C) 2009 - Present, Alexander A. Steshenko
 * @author    Alexander A. Steshenko <lcfsoft@gmail.com> {@link http://lcf.name} 
 * @license   New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */
class PageController extends Zend_Controller_Action
{
    /**
     * Delete page action. 
     * Delete page and all its notes and forwards to new page action
     *
     * @return void
     */
    public function deleteAction()
    {
        // Get request params
        $pageCode = $this->getRequest()->getParam('page');
        
        // Access the Model
        NotesNotes_Service_Pages::getInstance()
            ->deletePageByCode($pageCode);
        
        // Forward to new page action to create a new page
        return $this->_forward('new');
    }
    
    public function newAction()
    {
        // Access the Model
        $page = NotesNotes_Service_Pages::getInstance()
            ->createPageWithInitialNote();
        
        // Set up View
        return $this->getHelper('redirector')->gotoRouteAndExit(
            array('page' => $page->getCode()), 'page-index'
        );
    }
    
    /**
     * Render page html
     *
     * @return void
     */
    public function indexAction()
    {
        // Get request params
        $pageCode = $this->getRequest()->getParam('page');

        // Access the Model
        if (($page = NotesNotes_Service_Pages::getInstance()
            ->getPageByCode($pageCode)) === null) {
            // If page does not exist, force creation of a new one
            return $this->_forward('new');
        }
        $pageCookieLifetime = NotesNotes_Service_Pages::getInstance()
            ->getCookieLifetime();
        
        // Setting page cookie header
        $expires = new Zend_Date();
        $expires->addSecond($pageCookieLifetime);
        $this->getResponse()->setHeader('Set-Cookie',
            'page=' . $page->getCode() . '; '
            . 'expires=' . $expires->get(Zend_Date::COOKIE) . '; '
            . 'path=/'
        );

        $this->view->assign('page', $page->getCode());
    }
}