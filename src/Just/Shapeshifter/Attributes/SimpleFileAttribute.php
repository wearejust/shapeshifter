<?php namespace Just\Shapeshifter\Attributes;

use Gregwar\Image\Image;
use HTML;
use Str;
use Input;
use Just\Shapeshifter\Exceptions\DirDoesNotExistException;
use Just\Shapeshifter\Exceptions\DirNotWritableException;

/**
* FileAttribute
*
* @uses     Attribute
*
* @category Category
* @package  Package
* @author   JUST BV
* @link     http://wearejust.com/
*/
class SimpleFileAttribute extends Attribute implements iAttributeInterface
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
    public $relatives = array();

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
     * Sets the attribute value tot he name of the file.
     * If the input has an attribute delete-image, that file
     * is deleted.
     *
     * @param $value
     * @param $oldValue
     * @internal param mixed $val Description.
     *
     * @access public
     * @return mixed Value.
     */
    public function setAttributeValue($value, $oldValue = null)
    {
        if ($existing = Input::get($this->name . '_existing'))
        {
            $this->value = $existing;
        }
        else
        {
            $this->value = $value ?: $oldValue;
        }

        if ($deletes = Input::get('delete-image'))
        {
            if (array_search($this->name, $deletes) !== false)
            {
                $this->deleteFile( $this->value );
                return '';
            }
        }

        $this->moveUploadedFile();

        return true;
    }

    /**
     * @return mixed
     */
    public function getEditValue()
    {
        //return "dree";
        $this->value = $this->getDisplayValue();

        return $this->value;
    }


    /**
     * getDisplayValue
     * 
     * @access public
     * @return mixed Value.
     */
    public function getDisplayValue()
    {
        if ( ! $this->value ) return null;

        //die( public_path() ."/media/uploads/". $this->value );

        if (!file_exists(public_path() ."/media/uploads/". $this->value) )
        {
            //return __('form.file.doesntexist' . " : " .$this->value);
        }

        if ($this->hasFlag('force'))
        {
            return $this->value;
        }
        return "<a target='_blank' href='".$this->relativeStorageDir.strip_tags($this->value)."'>" . strip_tags($this->value). "</a>";

        //return '<a href="'.$this->relativeStorageDir.$this->value.'">'.$this->value.'</a>';
        //return HTML::link($this->relativeStorageDir . $this->value, $this->value, array('target' => '_blank'));
    }

    /**
     * Deletes the file
     * 
     * @param mixed $file Description.
     *
     * @access private
     * @return mixed Value.
     */
    private function deleteFile($file)
    {
        $file = $this->absoluteStorageDir .DIRECTORY_SEPARATOR. $file;

        if (file_exists($file))
        {
            unlink($file);
        }

        $this->value = '';
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
     * Moves the file to an certain location
     *
     * @access private
     * @return mixed Value.
     */
    private function moveUploadedFile()
    {
        if ( Input::hasFile($this->name) ) {
            $file = Input::file($this->name);
            $extension = '.' . $file->getClientOriginalExtension();

            $filename = Input::file($this->name)->getClientOriginalName();
            $filename = Str::slug(str_replace($extension, '', $filename));


            if(file_exists($this->absoluteStorageDir.$filename. $extension)){
                $new_name = "";
                $teller = 1;
                $base_name = str_replace($extension, "", $filename);
                while($new_name == "") {


                    if(file_exists($this->absoluteStorageDir.$base_name."_".$teller.$extension)){

                        //echo("bestond al".$base_name);
                        $teller ++;

                    }
                    else {
                        //echo "bestaat nog niet: ".$this->absoluteStorageDir.$base_name."_".$teller.$extension;
                        move_uploaded_file(Input::file($this->name)->getRealPath(), $this->absoluteStorageDir .$base_name."_".$teller . $extension);
                        if($teller>1) {
                            $this->value = $base_name . "_" . ($teller - 1) . $extension;
                        }
                        else {
                            $this->value = $base_name . $extension;
                        }
                        return;

                       $new_name =  $base_name;
                    }

                    //echo ('check voor ' . $this->absoluteStorageDir.$base_name."_".$teller.$extension);


                }
                $filename = $new_name."_".$teller;
            }
            else {
                //die("bestond nog niet, wordt ".$filename);
            }


            //die("nu naam bedacht, ".$filename);



            //Input::file($this->name)->move($this->absoluteStorageDir, ($filename . $extension));
            //die("move " . Input::file($this->name)->getRealPath() . " Naar " . $this->absoluteStorageDir. "/" .$filename . $extension );
            move_uploaded_file(Input::file($this->name)->getRealPath(), $this->absoluteStorageDir .$filename . $extension);

            if(isset($new_name)) {
                //$filename = $new_name."_".$teller-1;
            }


            $this->value = $filename . $extension;
            return "";
        }
        //die('uiteindelijk geworden' . $this->value);
        return "";
    }

    /**
     * Get files in same dir
     */
    private function getFilesSameDirectory()
    {
        $files = (array)glob($this->absoluteStorageDir . "*");
        $files = array_filter($files, 'is_file');
        $existing = array();

        foreach ($files as $file)
        {
            $file = pathinfo($file);
            $existing[$file['basename']] = $file['basename'];
        }

        $this->relatives = array('' => __('form.file.choose')) + $existing;
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
