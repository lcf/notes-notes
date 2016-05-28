<?php
/**
 * Notes Table Data Gateway object
 * 
 * @category  NotesNotes_Model_DbTable
 * @package   NotesNotes
 * @copyright Copyright (C) 2009 - Present, Alexander A. Steshenko
 * @author    Alexander A. Steshenko <lcfsoft@gmail.com> {@link http://lcf.name} 
 * @license   New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */
class NotesNotes_Model_DbTable_Notes extends Zend_Db_Table_Abstract
{
    /**
     * Name of the table with notes in the database
     * 
     * @var string
     */
    protected $_name = 'notes';
}