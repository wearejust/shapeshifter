<?php  namespace Just\Shapeshifter\Exceptions;

use Exception;

class AttributeErrorException extends Exception
{

	function __construct ($exception)
	{
		echo '<div style="position:absolute; z-index:999999; right: 30px; bottom:30px; background:#e81a27; font-family:arial, sans-serif; color:#fff; padding:20px;">';
		echo '<strong>Message:</strong> '. $exception->getMessage().'<br/>';
		echo '<strong>File:</strong> '. $exception->getFile().'<br/>';
		echo '<strong>Line:</strong> '. $exception->getLine();
		echo '</div>';

		die();
	}
}