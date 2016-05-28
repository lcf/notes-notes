<?php
/**
 * Controler which handles all requests which caused errors
 * 
 * @category  ErrorController
 * @package   NotesNotes
 * @copyright Copyright (C) 2009 - Present, Alexander A. Steshenko
 * @author    Alexander A. Steshenko <lcfsoft@gmail.com> {@link http://lcf.name} 
 * @license   New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */
class ErrorController extends Zend_Controller_Action
{
    const SYSTEM_ERROR_MESSAGE = 'System error';
    
    /**
     * Error action
     * 
     * @return void
     */
    public function errorAction()
    {
        // Get request params
        $errors            = $this->getRequest()->getParam('error_handler');
        $exception         = $errors['exception'];
        $bootstrap         = $this->getInvokeArg('bootstrap');
        $environment       = $bootstrap->getEnvironment(); 
        $errorEmailOptions = $bootstrap->getOption('erroremail');
        $sendErrorEmail    = $bootstrap->getOption('senderroremail');
         
        // Access the model 
        if ($sendErrorEmail) {
            // Send mail to admin with exception details if it is set so
            $message = 'Exception message: ' 
                     . $exception->getMessage() . "\n"
                     . 'Trace: ' . "\n" 
                     . $exception->getTraceAsString();

            $mail = new Zend_Mail('utf-8');
            $mail->setBodyText($message)
                 ->addTo($errorEmailOptions['email'])
                 ->setSubject($errorEmailOptions['subject'])
                 ->setFrom($errorEmailOptions['from'],
                           $errorEmailOptions['fromname'])
                 ->send();
        }
        
        if ($environment == 'development') {
            // If it is development environment - just dump the exception to let
            // the developer take care of it
            $this->getHelper('layout')->disableLayout();
            $this->getHelper('viewRenderer')->setNoRender();
            Zend_Debug::dump($exception->getMessage(), 'Exception was thrown: ');
            if ($exception instanceof NotesNotes_Service_Exception && $exception->hasChainedException()) {
                Zend_Debug::dump($exception->getChainedException()->getMessage(), 'Chained exception: ');
            }
            return;
        }

        // Dependantly on the current context
        if ($this->getHelper('contextSwitch')->getCurrentContext() == 'json') {
            // Switch the renderer off
            $this->getHelper('viewRenderer')->setNoRender();
        } else {
            // Or, change layout to static pages layout for showing error.phtml 
            $this->getHelper('layout')->setLayout('static-pages-layout');
        }
        
        switch (get_class($exception)) {
            case 'Zend_Controller_Dispatcher_Exception':
                // This could happen in case if wrong url was entered manually
                return $this->_redirect('/');
                break;
            case 'NotesNotes_Service_Exception':
                // Service layer exception
                $errorMessage = $exception->getMessage();
                break;
            default:
                // Something very unexpected has happened, 
                // return system error to the user
                $errorMessage = self::SYSTEM_ERROR_MESSAGE;
                break;
        }

        // Set up the view
        $this->view->assign('error', $errorMessage);
    }
}
