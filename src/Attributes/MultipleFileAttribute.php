<?php

namespace Just\Shapeshifter\Attributes;

use Gregwar\Image\Image;

class MultipleFileAttribute extends FileAttribute implements iAttributeInterface
{
    /**
     * @var array relative files in same dir
     */
    protected $existing = [];

    /**
     * Get files in same dir
     */
    protected function getFilesSameDirectory()
    {
        $files = (array) glob($this->absoluteStorageDir . '*');
        $files = array_filter($files, 'is_file');

        foreach ($files as $file) {
            $file = pathinfo($file);
            $path = $this->relativeStorageDir . $file['basename'];
            if (substr($path, 0, 1) == '/' || substr($path, 0, 1) == '\\') {
                $path = substr($path, 1);
            }
            $this->existing[$file['basename']] = '/' . Image::open($path)->zoomCrop(100, 100)->jpeg();
        }

        if (!$this->existing) {
            $this->existing = [''];
        }
    }

    /**
     * @param      $value
     * @param null $width
     * @param null $height
     *
     * @return string
     */
    public function getCrop($value, $width = null, $height = null)
    {
        return '/' . Image::open($this->getAbsolutePath() . $value)->cropResize($width, $height)->jpeg();
    }
}
