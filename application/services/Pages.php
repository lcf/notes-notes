<?php
/**
 * Service layer that contains business logic related to
 * pages managment
 * 
 * @category  NotesNotes_Service
 * @package   NotesNotes
 * @copyright Copyright (C) 2009 - Present, Alexander A. Steshenko
 * @author    Alexander A. Steshenko <lcfsoft@gmail.com> {@link http://lcf.name} 
 * @license   New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */
class NotesNotes_Service_Pages
{
    /**
     * Singleton instance
     *
     * @var NotesNotes_Service_Pages
     */
    protected static $_instance = null;

    /**
     * Page cooke lifetime in seconds
     * 
     * @var integer
     */
    protected $_cookieLifetime = 157680000;
    
    /**
     * Pages data mapper
     * 
     * @var NotesNotes_Model_PagesMapper
     */
    protected $_pagesMapper = null;
    
    /**
     * Enforce singleton; Constructor
     *
     * Instantiate using {@link getInstance()}; 
     *
     * @return void
     */
    protected function __construct()
    {
    }

    /**
     * Enforce singleton; disallow cloning
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Singleton instance
     *
     * @return NotesNotes_Service_Pages
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Returns pages data mapper
     * 
     * @return NotesNotes_Model_PagesMapper
     */
    public function getPagesMapper()
    {
        if ($this->_pagesMapper === null) {
            $this->setPagesMapper(new NotesNotes_Model_PagesMapper());
        }
        
        return $this->_pagesMapper;
    }
    
    /**
     * Sets pages data mapper
     * 
     * @param $pagesMapper
     * @return void
     */
    public function setPagesMapper(NotesNotes_Model_PagesMapper $pagesMapper)
    {
        $this->_pagesMapper = $pagesMapper;
    }
    
    /**
     * Sets page cookie lifetime
     * 
     * @param integer $seconds Seconds for page cookie to live
     */
    public function setCookieLifetime($seconds)
    {
        $this->_cookieLifetime = $seconds;
    }
    
    /**
     * Returns page cookie lifetime
     * 
     * @return integer
     */
    public function getCookieLifetime()
    {
        return $this->_cookieLifetime;
    }

    /**
     * Create new Page object, saves it in the database and returns
     * 
     * @return NotesNotes_Model_Page New page domain object
     */
    public function createPage()
    {
        try {
            $pageCode = $this->_generatePageCode();
            $page = new NotesNotes_Model_Page($pageCode);
            // Save new page object in the database
            $this->getPagesMapper()->save($page);
    
            return $page;
        } catch (Exception $exception) {
            if ($exception instanceof NotesNotes_Service_Exception) {
                throw $exception;
            } else {
                // Wrap model exception into service exception
                throw new NotesNotes_Service_Exception(
                    'Can\'t create page', null, $exception);
            }
        }
    }
    
    /**
     * Creates page with initial code and return page object
     * 
     * @return NotesNotes_Model_Page
     */
    public function createPageWithInitialNote()
    {
        try {
            $page = $this->createPage();
            // Add initial note on the page
            NotesNotes_Service_PageNotes::getInstance()
                ->addInitialNoteOnPage($page->getCode());
                
            return $page;
        } catch (Exception $exception) {
            if ($exception instanceof NotesNotes_Service_Exception) { 
                throw $exception;
            } else {
                // Wrap model exception into service exception
                throw new NotesNotes_Service_Exception(
                    'Can\'t create page with initial code', null, $exception);
            }
        }
    }
    
    /**
     * If page already exists in the database - returns it, if not
     * creates new Page object, saves it in the database and returns
     * 
     * @param string $pageCode code of the page
     * @param boolean $addInitialNoteIfNew if page wasn't found and being created,
     *                                        initial note will be added to it if this param is true
     * @return NotesNotes_Model_Page New page domain object
     */
    public function createPageIfNotExist($pageCode, $addInitialNoteIfNew = true)
    {
        try {
            // If page code is null (wasn't previously set) or the page does not exist in the database
            if ($pageCode === null || ($page = $this->getPagesMapper()->findOneByCode($pageCode)) === null) {
                // Create new page with or without initial note
                return $addInitialNoteIfNew
                    ? $this->createPageWithInitialNote()
                    : $this->createPage();
            }
            
            // If page object was found in the data source, return it
            return $page;
        } catch (Exception $exception) {
            if ($exception instanceof NotesNotes_Service_Exception) {
                throw $exception;
            } else {
                // Wrap model exception into service exception
                throw new NotesNotes_Service_Exception(
                    'Can\'t create page with initial code on it' . $pageCode, null, $exception);
            }
        }
    }
    
    /**
     * Returns page object by page code
     * 
     * @param string $pageCode
     * @return NotesNotes_Model_Page
     */
    public function getPageByCode($pageCode)
    {
        try {
            return $this->getPagesMapper()->findOneByCode($pageCode);
        } catch (Exception $exception) {
            if ($exception instanceof NotesNotes_Service_Exception) {
                throw $exception;
            } else {
                // Wrap model exception into service exception
                throw new NotesNotes_Service_Exception(
                    'Can\'t retrieve page with code' . $pageCode, null, $exception);
            }
        }
    }
    
    /**
     * Removes page by its code
     * 
     * @param string $pageCode
     * @return void
     */
    public function deletePageByCode($pageCode)
    {
        try {
            $page = $this->getPagesMapper()->findOneByCode($pageCode);
            if ($page === null) {
                // Page does not exist  (was removed or never existed)
                throw new NotesNotes_Service_Exception('Page with code ' . $pageCode
                    . ' does not exist');
            }
            
            $this->getPagesMapper()->delete($page);
        } catch (Exception $exception) {
            if ($exception instanceof NotesNotes_Service_Exception) {
                throw $exception;
            } else {
                // Wrap model exception into service exception
                throw new NotesNotes_Service_Exception(
                    'Can\'t delete page with code' . $pageCode, null, $exception);
            }
        }
    }
    
    /**
     * New page code generation
     *
     * @return string
     */
    protected function _generatePageCode()
    {
        do { 
            $pageCode = md5(uniqid(mt_rand(), true));
        } while ($this->getPagesMapper()->findOneByCode($pageCode) !== null); // Just in case...
        
        return $pageCode;
    }
}