<?php
/**
 * All identifiable entities should implement this interface
 * 
 * @category  NotesNotes_Model
 * @package   NotesNotes
 * @copyright Copyright (C) 2009 - Present, Alexander A. Steshenko
 * @author    Alexander A. Steshenko <lcfsoft@gmail.com> {@link http://lcf.name} 
 * @license   New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */
interface NotesNotes_Model_Identifiable
{
    /**
     * Returns entity id
     * 
     * @return mixed $id
     */
    public function getId ();
}