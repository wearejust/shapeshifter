<?php
namespace Just\Shapeshifter\Fronteer\Metatag;

use Just\Shapeshifter\Core\Models\Settings;

class MetaTagGenerator
{
	private $metaTags = [];

	function __construct ()
	{
		$this->resolveSettings();
	}

	public function addMeta ($property, $value)
	{
		$converter        = new MetaTagConverter;
		$this->metaTags[] = $converter->convertToMeta($property, $value);
	}

	/**
	 * @return mixed
	 */
	public function getMetaTags ()
	{
		return $this->metaTags;
	}

	private function resolveSettings ()
	{
		foreach(\Config::get('shapeshifter::metatags.available_tags') as $tag) {
			if(!array_key_exists($tag,$this->getMetaTags())) {
				$this->getSettingsFromDatabase($tag);
			}
		}
	}

	/**
	 * @param $tag
	 */
	private function getSettingsFromDatabase ($tag)
	{
		$settingItem = Settings::where('key', '=', $tag)->first();
		if ($settingItem)
		{
			$this->addMeta($settingItem->key, $settingItem->value);
		}
	}

}
