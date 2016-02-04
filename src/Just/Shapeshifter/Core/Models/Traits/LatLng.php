<?php

namespace Just\Shapeshifter\Core\Models\Traits;

use InvalidArgumentException;

trait LatLng
{
    /**
     * @var string
     */
    private $latLongField = 'latlng';

    /**
     * @param string|null $field
     * @return string
     */
    public function getLatitude($field = null)
    {
        return $this->splitLatLong($field, 0);
    }

    /**
     * @param string|null $field
     * @return string
     */
    public function getLongitude($field = null)
    {
        return $this->splitLatLong($field, 1);
    }

    /**
     * @param string|null $field
     * @param int $index
     * @return array
     */
    private function splitLatLong($field, $index)
    {
        $field = $field ?: $this->latLongField;
        $explode = explode(';', $this->{$field});

        if (! array_key_exists($index, $explode) || count($explode) !== 2) {
            throw new InvalidArgumentException(sprintf('Invalid latlong string given on field [%s]', $field));
        }

        return $explode[$index];
    }
}