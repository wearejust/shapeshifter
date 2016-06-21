<?php

namespace Just\Shapeshifter\Attributes;

use Illuminate\Database\Eloquent\Model;
use Input;

class SimpleFileAttribute extends FileAttribute implements iAttributeInterface
{
    /**
     * getDisplayValue
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return mixed Value.
     */
    public function getDisplayValue(Model $model)
    {
        if ($this->hasFlag('force')) {
            return $model->{$this->name};
        }

        return "<a target='_blank' href='" . $this->relativeStorageDir . strip_tags($model->{$this->name}) . "'>" . strip_tags($model->{$this->name})  . '</a>';
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
            $filename = str_slug(str_replace($extension, '', $filename));

            if (file_exists($this->absoluteStorageDir . $filename . $extension)) {
                $new_name  = '';
                $teller    = 1;
                $base_name = str_replace($extension, '', $filename);
                while ($new_name == '') {
                    if (file_exists($this->absoluteStorageDir . $base_name . '_' . $teller . $extension)) {
                        $teller ++;
                    } else {
                        move_uploaded_file(Input::file($this->name)->getRealPath(), $this->absoluteStorageDir . $base_name . '_' . $teller . $extension);
                        if ($teller > 1) {
                            $this->value = $base_name . '_' . ($teller - 1) . $extension;
                        } else {
                            $this->value = $base_name . $extension;
                        }

                        return;

                        $new_name =  $base_name;
                    }
                }
                $filename = $new_name . '_' . $teller;
            }

            move_uploaded_file(Input::file($this->name)->getRealPath(), $this->absoluteStorageDir . $filename . $extension);

            $this->value = $filename . $extension;

            return '';
        }

        return '';
    }
}
