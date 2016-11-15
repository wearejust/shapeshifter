<?php

namespace Just\Shapeshifter\Attributes;

use Croppa;

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

        foreach ($files as $path) {
            $file = pathinfo($path);
            $this->existing[$file['basename']] = asset(Croppa::url($path, null, 41));
        }

        if (!$this->existing) {
            $this->existing = [''];
        }
    }
}
