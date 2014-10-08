<?php

namespace spec\Just\Shapeshifter\Fronteer\Metatag;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MetaTagConverterSpec extends ObjectBehavior
{
	function it_is_initializable ()
	{
		$this->shouldHaveType('Just\Shapeshifter\Fronteer\MetaTagConverter');
	}

	function it_returns_an_meta_tag ()
	{
		$property = 'viewport';
		$value    = 'website';
		$this->convertToMeta($property, $value)->shouldReturn('<meta name="' . $property . '" content="' . $value . '">');
	}

	function it_returns_an_og_property_meta_based_on_the_property()
	{
		$property = 'og:type';
		$value    = 'website';
		$this->convertToMeta($property, $value)->shouldReturn('<meta property="' . $property . '" content="' . $value . '">');
	}

	function it_returns_an_fb_property_meta_based_on_the_property()
	{
		$property = 'fb:type';
		$value    = 'website';
		$this->convertToMeta($property, $value)->shouldReturn('<meta property="' . $property . '" content="' . $value . '">');
	}

	function it_returns_false_when_the_value_is_empty()
	{
		$property = 'og:type';
		$value    = '';
		$this->convertToMeta($property, $value)->shouldReturn(false);
	}

}
