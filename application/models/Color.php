<?php
/**
 * Model that represents color
 * 
 * @category  NotesNotes_Model
 * @package   NotesNotes
 * @copyright Copyright (C) 2009 - Present, Alexander A. Steshenko
 * @author    Alexander A. Steshenko <lcfsoft@gmail.com> {@link http://lcf.name} 
 * @license   New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */
class NotesNotes_Model_Color
{
    /**
     * Value of red light
     * 
     * @var integer
     */
    protected $_red = null;
    
    /**
     * Value of green color
     * 
     * @var integer
     */
    protected $_green = null;
    
    /**
     * Value of blue color
     * 
     * @var integer
     */
    protected $_blue = null;
    
    /**
     * Constructor
     * accepts two types of sets of initial parametes
     * First parameter may be both hexadecimal string
     * which represents the color like 'FFFFFF'
     * or decimal integer which means exactly red
     * light value.
     * 
     * @param integer|string $red decimal red light value or hexadecimal
     * @param integer $green
     * @param integer $blue
     * @return void
     */
    public function __construct($red, $green = null, $blue = null)
    {
        if (is_string($red) && strlen($red) == 6) {
            // a hexadecimal string passed
            $this->setHexadecimalString($red);
        } else {
            $this->setRed($red)
                 ->setGreen($green)
                 ->setBlue($blue);
        }
    }

    /**
     * Sets Red ligth value
     * 
     * @param integer $red red light value
     * @return NotesNotes_Model_Color object itself, fluent interface
     */
    public function setRed($red)
    {
        $this->_red = $red;
        return $this;
    }
    
    /**
     * Sets Green ligth value
     * 
     * @param integer $green green light value
     * @return NotesNotes_Model_Color object itself, fluent interface
     */
    public function setGreen($green)
    {
        $this->_green = $green;
        return $this;
    }
    
    /**
     * Sets Blue ligth value
     * 
     * @param integer $blue blue light value
     * @return NotesNotes_Model_Color object itself, fluent interface
     */
    public function setBlue($blue)
    {
        $this->_blue = $blue;
        return $this;
    }
    
    /**
     * Converts object to string, alias to getHexadecimalString() method
     * 
     * @returns string hexadecimal representation of the color 
     */
    public function __toString()
    {
        return $this->getHexadecimalString();
    }
    
    /**
     * Returns red light value
     * 
     * @return integer red light value
     */
    public function getRed()
    {
        return $this->_red;
    }
    
    /**
     * Returns green light value
     * 
     * @return integer green light value
     */
    public function getGreen()
    {
        return $this->_green;
    }
    
    /**
     * Returns blue light value
     * 
     * @return integer blue light value
     */
    public function getBlue()
    {
        return $this->_blue;
    }
    
    /**
     * Composes hexadecimal string representation of the color
     * 
     * @return string hexadecimal color string
     */
    public function getHexadecimalString()
    {
        return sprintf('%02X%02X%02X', $this->getRed(), $this->getGreen(), $this->getBlue());
    }
    
    /**
     * Sets object properties based on hexadecimal string passed
     * 
     * @param string $hexadecimalString hexadecimal string color representation like F1A302
     * @return NotesNotes_Model_Color object itself, fluent interface
     */
    public function setHexadecimalString($hexadecimalString)
    {
        $this->setRed(hexdec($hexadecimalString{0} . $hexadecimalString{1}));
        $this->setGreen(hexdec($hexadecimalString{2} . $hexadecimalString{3}));
        $this->setBlue(hexdec($hexadecimalString{4} . $hexadecimalString{5}));
        
        return $this;
    }
}