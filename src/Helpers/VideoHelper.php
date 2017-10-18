<?php

namespace Just\Shapeshifter\Helpers;

class VideoHelper
{
    /**
     * @param string $url
     * @param int    $width
     * @param int    $height
     *
     * @return string
     */
    public static function preview($url, $width = 160, $height = 90)
    {
        $tag     = "<div class='video'><iframe style='margin:0;' src='%s' width='{$width}' height='{$height}'></iframe></div>";
        $vimeoID = static::getVimeoID($url);

        if ($vimeoID) {
            return sprintf($tag,
                "//player.vimeo.com/video/{$vimeoID}?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff"
            );
        }

        $youtubeID = static::getYoutubeID($url);
        if ($youtubeID) {
            return sprintf($tag,
                "//youtube.com/embed/{$youtubeID}?modestbranding=1&showinfo=0&controls=0&rel=0&autohide=1"
            );
        }

        return '';
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    public static function getYoutubeID($url)
    {
        $pattern = "/^(?:http(?:s)?:\/\/)?(?:www.)?(?:m.)?(?:youtu.be\/|youtube.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/";

        preg_match($pattern, $url, $matches);

        return isset($matches[1]) ? $matches[1] : false;
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    public static function getVimeoID($url)
    {
        $regex = '~
		# Match Vimeo link and embed code
		(?:<iframe [^>]*src=")?         # If iframe match up to first quote of src
		(?:                             # Group vimeo url
				https?:\/\/             # Either http or https
				(?:[\w]+\.)*            # Optional subdomains
				vimeo\.com              # Match vimeo.com
				(?:[\/\w]*\/videos?)?   # Optional video sub directory this handles groups links also
				\/                      # Slash before Id
				([0-9]+)                # $1: VIDEO_ID is numeric
				[^\s]*                  # Not a space
		)                               # End group
		"?                              # Match end quote if part of src
		(?:[^>]*></iframe>)?            # Match the end of the iframe
		(?:<p>.*</p>)?                  # Match any title information stuff
		~ix';

        preg_match($regex, $url, $matches);

        return isset($matches[1]) ? $matches[1] : false;
    }
}
