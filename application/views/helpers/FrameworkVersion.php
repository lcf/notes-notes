<?php
/**
 * View helper that renderns partial script with Zend Framework version 
 * which is currently in use
 * 
 * @category  NotesNotes_View_Helper
 * @package   NotesNotes
 * @copyright Copyright (C) 2009 - Present, Alexander A. Steshenko
 * @author    Alexander A. Steshenko <lcfsoft@gmail.com> {@link http://lcf.name} 
 * @license   New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */
class NotesNotes_View_Helper_FrameworkVersion extends Zend_View_Helper_Abstract
{
    public function frameworkVersion()
    {
        return $this->view->partial('framework-version.phtml', 
                                    array('version' => Zend_Version::VERSION));
    }
}