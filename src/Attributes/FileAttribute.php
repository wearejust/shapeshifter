<?php

namespace Just\Shapeshifter\Attributes;

use Gregwar\Image\Image;
use Html;
use Illuminate\Database\Eloquent\Model;
use Input;
use Just\Shapeshifter\Exceptions\DirDoesNotExistException;
use Just\Shapeshifter\Exceptions\DirNotWritableException;

class FileAttribute extends Attribute implements iAttributeInterface
{
    /**
     * Directory (absolute) where the file has to be stored
     *
     * @var string
     */
    protected $absoluteStorageDir;

    /**
     * Directory (relative from public_path() ) where the file has to be stored
     *
     * @var string
     */
    protected $relativeStorageDir;

    /**
     * Maximum file width
     *
     * @var integer
     */
    protected $maxWidth = 1920;

    /**
     * Maximum file height
     *
     * @var integer
     */
    protected $maxHeight = 1080;

    /**
     * Maximum file size
     *
     * @var integer
     */
    protected $maxSize = 3145728;

    /**
     * @var array relative files in same dir
     */
    protected $relatives = [];

    /**
     * @param string $name       The name of the attribute.
     * @param string $storageDir Storage dir.
     * @param array  $flags      Flags.
     *
     * @throws \Just\Shapeshifter\Exceptions\InvalidArgumentException
     */
    public function __construct($name, $storageDir, $flags = [])
    {
        foreach (['maxWidth', 'maxHeight', 'maxSize'] as $option) {
            if (isset($flags[$option])) {
                $this->{$option} = $flags[$option];
                unset($flags[$option]);
            }
        }

        $this->name  = $name;
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
     * @access public
     *
     * @return mixed Value.
     */
    public function setAttributeValue($value, $oldValue = null)
    {
        if ($existing = Input::get($this->name . '_existing')) {
            $this->value = $existing;
        } else {
            $this->value = $value ?: $oldValue;
        }

        if ($deletes = Input::get('delete-image')) {
            if (array_search($this->name, $deletes) !== false) {
                $this->deleteFile($this->value);

                return '';
            }
        }

        $this->moveUploadedFile();

        return true;
    }

    /**
     * getDisplayValue
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return mixed Value.
     */
    public function getDisplayValue(Model $model)
    {
        $absPath = rtrim($this->absoluteStorageDir, '/') . '/' . $model->{$this->name};
        $relPath = rtrim($this->relativeStorageDir, '/') . '/' . $model->{$this->name};

        if ($this->hasFlag('force')) {
            return $model->{$this->name};
        }

        if (!is_file($absPath)) {
            return $model->{$this->name} ? __('form.file.doesntexist') : '';
        }

        if ((bool) getimagesize($absPath)) {
            $src = Image::open($absPath)->resize(null, 100)->inline();
            return "<img style='max-height:100px;' src='{$src}'>";
        }

        return Html::link($relPath, $model->{$this->name}, ['target' => '_blank']);
    }

    /**
     * Deletes the file
     *
     * @param mixed $file Description.
     *
     * @access private
     *
     * @return mixed Value.
     */
    private function deleteFile($file)
    {
        $file = $this->absoluteStorageDir . '/' . $file;

        if (file_exists($file)) {
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
    protected function checkDirectory()
    {
        if (!is_dir($this->absoluteStorageDir)) {
            throw new DirDoesNotExistException("Directory '{$this->absoluteStorageDir}' doesnt exists");
        }

        if (!is_writable($this->absoluteStorageDir)) {
            if (!@chmod($this->absoluteStorageDir, 0777)) {
                throw new DirNotWritableException("Directory '{$this->absoluteStorageDir}' is not writable");
            }
        }
    }

    /**
     * Moves the file to an certain location
     *
     * @access private
     *
     * @return mixed Value.
     */
    protected function moveUploadedFile()
    {
        if (Input::hasFile($this->name)) {
            $file      = Input::file($this->name);
            $extension = '.' . $file->getClientOriginalExtension();

            $filename = Input::file($this->name)->getClientOriginalName();
            $filename = Str::slug(str_replace($extension, '', $filename));

            Input::file($this->name)->move($this->absoluteStorageDir, ($filename . $extension));

            $this->value = $filename . $extension;
        }

        return '';
    }

    /**
     * Get files in same dir
     */
    protected function getFilesSameDirectory()
    {
        $files    = (array) glob($this->absoluteStorageDir . '*');
        $files    = array_filter($files, 'is_file');
        $existing = [];

        foreach ($files as $file) {
            $file                        = pathinfo($file);
            $existing[$file['basename']] = $file['basename'];
        }

        $this->relatives = ['' => __('form.file.choose')] + $existing;
    }

    /**
     * @param $storageDir
     *
     * @return string
     */
    protected function getRelativePath($storageDir)
    {
        return '/' . trim($storageDir, '/') . '/';
    }

    /**
     * @return string
     */
    protected function getAbsolutePath()
    {
        return public_path() . '/' . $this->relativeStorageDir;
    }
}
