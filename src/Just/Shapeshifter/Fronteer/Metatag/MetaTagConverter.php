<?php
namespace Just\Shapeshifter\Fronteer\Metatag;

class MetaTagConverter
{
	private $propertyFlags = ['og:', 'fb:'];

	public function convertToMeta ($property, $value)
	{
		if(empty($value)) {
			return false;
		}

		$metaKeyAttribute = ($this->propertyContainsFlags($property)) ? 'property' : 'name';
		return $this->metaHtmlAttribute($property, $value, $metaKeyAttribute);
	}

	/**
	 * @param $property
	 *
	 * @return int
	 */
	private function propertyContainsFlags ($property)
	{
		foreach($this->propertyFlags as $flag) {
			if(preg_match("/{$flag}/", $property)){
				return true;
			}
		}
		return false;
	}

	/**
	 * @param $property
	 * @param $value
	 * @param $metaKeyAttribute
	 *
	 * @return string
	 */
	private function metaHtmlAttribute ($property, $value, $metaKeyAttribute)
	{
		return '<meta ' . $metaKeyAttribute . '="' . $property . '" content="' . $value . '">';
	}
}
