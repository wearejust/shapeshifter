<?php namespace Just\Shapeshifter\Fronteer;

/**
 * FronteerHelper.php
 * @author John in 't Hout
 * @date 08-10-14
 * @time 13:57
 * @copyright Just
 */
class FronteerHelper {

	/**
	 * @var Metatag\MetaTagGenerator
	 */
	private $metaTagGenerator;

	function __construct ( Metatag\MetaTagGenerator $metaTagGenerator)
	{
		$this->metaTagGenerator = $metaTagGenerator;
	}

	/**
	 * Returns an html section with all the meta tags defined previously.
	 */
	public  function getMetaTags()
	{
		$this->metaTagGenerator->getMetaTags();
	}

	public function setMetaAttr($property, $value) {
		$this->metaTagGenerator->addMeta($property, $value);
	}
} 