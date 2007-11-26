<?php /*********************************************************************
 *
 *   Copyright : (C) 2006 Nicolas Grekas. All rights reserved.
 *   Email     : p@tchwork.org
 *   License   : http://www.gnu.org/licenses/lgpl.txt GNU/LGPL
 *
 *   This library is free software; you can redistribute it and/or
 *   modify it under the terms of the GNU Lesser General Public
 *   License as published by the Free Software Foundation; either
 *   version 3 of the License, or (at your option) any later version.
 *
 ***************************************************************************/


/*
 * Partial mbstring implementation in pure PHP
 *
 * All functions introduced in PHP 5.2.0:

mb_stripos  - Finds position of first occurrence of a string within another, case insensitive
mb_stristr  - Finds first occurrence of a string within another, case insensitive
mb_strrchr  - Finds the last occurrence of a character in a string within another
mb_strrichr - Finds the last occurrence of a character in a string within another, case insensitive
mb_strripos - Finds position of last occurrence of a string within another, case insensitive
mb_strstr   - Finds first occurrence of a string within another
mb_strrpos  - Find position of last occurrence of a string in a string

 */

class utf8_mbstring_520
{
	static function stripos($haystack, $needle, $offset = 0, $encoding = null)
	{
		return mb_strpos(mb_strtolower($haystack, $encoding), mb_strtolower($needle, $encoding), $offset, $encoding);
	}

	static function stristr($haystack, $needle, $part = false, $encoding = null)
	{
		$pos = self::stripos($haystack, $needle, $encoding);
		return false === $pos ? false : ($part ? mb_substr($haystack, 0, $pos, $encoding) : mb_substr($haystack, $pos, null, $encoding));
	}

	static function strrchr($haystack, $needle, $part = false, $encoding = null)
	{
		$pos = self::strrpos($haystack, $needle, 0, $encoding);
		return false === $pos ? false : ($part ? mb_substr($haystack, 0, $pos, $encoding) : mb_substr($haystack, $pos, null, $encoding));
	}

	static function strrichr($haystack, $needle, $part = false, $encoding = null)
	{
		$pos = self::strripos($haystack, $needle, $encoding);
		return false === $pos ? false : ($part ? mb_substr($haystack, 0, $pos, $encoding) : mb_substr($haystack, $pos, null, $encoding));
	}

	static function strripos($haystack, $needle, $offset = 0, $encoding = null)
	{
		return self::strrpos(mb_strtolower($haystack, $encoding), mb_strtolower($needle, $encoding), $offset, $encoding);
	}

	static function strstr($haystack, $needle, $part = false, $encoding = null)
	{
		$pos = strpos($haystack, $needle);
		return false === $pos ? false : ($part ? substr($haystack, 0, $pos) : substr($haystack, $pos));
	}

	static function strrpos($haystack, $needle, $offset = 0, $encoding = null)
	{
		if (null === $encoding && $offset != (int) $offset)
		{
			$encoding = $offset;
			$offset = 0;
		}
		else if ($offset = (int) $offset) $haystack = mb_substr($haystack, $offset);

		$pos = utf8_mbstring_500_strrpos($haystack, $needle, $encoding);

		return false !== $pos ? $offset + $pos : false;
	}
}

#>>> Below is only for patchwork
return ;
#<<<

if (function_exists('mb_strrpos')) {}
else if (function_exists('iconv_strrpos'))
{
	function mb_strrpos($haystack, $needle, $encoding)
	{
		return iconv_strrpos($haystack, $needle, $encoding);
	}
}
else
{
	function mb_strrpos($haystack, $needle, $encoding)
	{
		$needle = mb_substr($needle, 0, 1);
		$pos = strpos(strrev($haystack), strrev($needle));
		return false === $pos ? false : mb_strlen($pos ? substr($haystack, 0, -$pos) : $haystack);
	}
}