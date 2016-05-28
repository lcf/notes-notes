<?php
/**
 * NotesNotes service layer exception
 * 
 * @category  NotesNotes_Service
 * @package   NotesNotes
 * @copyright Copyright (C) 2009 - Present, Alexander A. Steshenko
 * @author    Alexander A. Steshenko <lcfsoft@gmail.com> {@link http://lcf.name} 
 * @license   New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */
class NotesNotes_Service_Exception extends Exception
{
    /**
     * Chained exception
     * 
     * @var Exception
     */
    protected $_chainedException = null;

    /**
     * Constructor
     * 
     * @param string $message
     * @param string|int $code
     * @param Exception $chainedException
     */
    public function __construct($message = null, $code = null, Exception $chainedException = null)
    {
        $this->message = $message;
        $this->code = $code;
        $this->_chainedException = $chainedException;
    }

    /**
     * Check if this general exception has a specific exception nested inside.
     * 
     * @return boolean
     */
    public function hasChainedException()
    {
        return ($this->_chainedException !== null);
    }

    /**
     * Returns exception nested inside
     * 
     * @return Exception|null
     */
    public function getChainedException()
    {
        return $this->_chainedException;
    }
}