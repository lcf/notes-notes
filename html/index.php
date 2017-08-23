<?php
/**
 * Entry point
 * 
 * @package   NotesNotes
 * @copyright Copyright (C) 2009 - Present, Alexander A. Steshenko
 * @author    Alexander A. Steshenko <lcfsoft@gmail.com> {@link http://lcf.name} 
 * @license   New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV') 
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

require_once APPLICATION_PATH . '/../vendor/autoload.php';

/** Zend_Application */
require_once 'Zend/Application.php';  


// Create application, bootstrap, and run
$configs = array(
    APPLICATION_PATH . '/configs/application.ini',
);
if (file_exists(APPLICATION_PATH . '/configs/application.local.ini')) {
    $configs[] = APPLICATION_PATH . '/configs/application.local.ini';
}
$application = new Zend_Application(
    APPLICATION_ENV,
    array('config' => $configs)
);

$application->bootstrap();
$application->run();    
