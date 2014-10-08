<?php

namespace spec\Just\Shapeshifter\Fronteer\Metatag;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MetaTagGeneratorSpec extends ObjectBehavior
{
	function it_is_initializable ()
	{
		$this->shouldHaveType('Just\Shapeshifter\Fronteer\MetaTagGenerator');
	}

	function it_adds_meta_tags_to_an_array ()
	{
		$this->addMeta('author', 'wearejust.com');
		$this->addMeta('fb:title', 'wearejust');
		$this->addMeta('og:title', 'wearejustcom');
		$this->getMetaTags()->shouldReturn([
												0 => '<meta name="author" content="wearejust.com">',
												1 => '<meta property="fb:title" content="wearejust">',
												2 => '<meta property="og:title" content="wearejustcom">'
		                                      ]);
	}
}
