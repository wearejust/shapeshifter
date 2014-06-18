<?php namespace Just\Shapeshifter\Attributes;

use Gregwar\Image\Image;
use HTML;
use Input;
use Just\Shapeshifter\Exceptions\DirDoesNotExistException;
use Just\Shapeshifter\Exceptions\DirNotWritableException;

/**
* MultipleFileAttribute
*
* @uses     Attribute
*
* @category Category
* @package  Package
* @author   JUST BV
* @link     http://wearejust.com/
*/
class MultipleFileAttribute extends Attribute implements iAttributeInterface
{
    /**
     * Directory (absolute) where the file has to be stored
     *
     * @var mixed
     *
     * @access public
     */
    public $absoluteStorageDir;

    /**
     * Directory (relative from public_path() ) where the file has to be stored
     *
     * @var mixed
     *
     * @access public
     */
    public $relativeStorageDir;

    /**
     * @var array relative files in same dir
     */
    public $existing = array();

    /**
     * __construct
     *
     * @param string $name The name of the attribute.
     * @param string $storageDir Storage dir.
     * @param array $flags Flags.
     *
     * @throws \Just\Shapeshifter\Exceptions\InvalidArgumentException
     * @access public
     * @return mixed Value.
     */
    public function __construct($name, $storageDir, $flags = array() )
    {
        $this->name = $name;
        $this->flags = $flags;

        $this->relativeStorageDir = $this->getRelativePath($storageDir);
        $this->absoluteStorageDir = $this->getAbsolutePath();

        $this->checkDirectory();
        $this->getFilesSameDirectory();
    }

    /**
     * Checks the existance of the directory and checks the permissions
     *
     * @throws \Just\Shapeshifter\Exceptions\DirDoesNotExistException
     * @throws \Just\Shapeshifter\Exceptions\DirNotWritableException
     *
     * @return void
     */
    private function checkDirectory()
    {
        if ( ! is_dir($this->absoluteStorageDir))
        {
            throw new DirDoesNotExistException("Directory '{$this->absoluteStorageDir}' doesnt exists");
        }

        if ( ! is_writable($this->absoluteStorageDir))
        {
            if ( ! @chmod($this->absoluteStorageDir, 0777) )
            {
                throw new DirNotWritableException("Directory '{$this->absoluteStorageDir}' is not writable");
            }
        }
    }

    /**
     * Get files in same dir
     */
    private function getFilesSameDirectory()
    {
        $files = (array)glob($this->absoluteStorageDir . "*");
        $files = array_filter($files, 'is_file');

        foreach ($files as $file)
        {
            $file = pathinfo($file);
            $this->existing[$file['basename']] = $this->relativeStorageDir . $file['basename'];
        }

        if (!$this->existing) {
        	$this->existing = array('');
        }
    }

    /**
     * @param $storageDir
     * @return string
     */
    private function getRelativePath($storageDir)
    {
        return DIRECTORY_SEPARATOR . trim($storageDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }
    /**
     * @return string
     */
    private function getAbsolutePath()
    {
        return public_path() . DIRECTORY_SEPARATOR . $this->relativeStorageDir;
    }
}

?>
