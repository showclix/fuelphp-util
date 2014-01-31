<?php

/**
 * String library
 *
 * @package    Fuel\Common
 * @version    1.0.0
 * @license    MIT License
 * @copyright  2010 - 2012 Fuel Development Team
 */

namespace Fuel\Util;

class Str
{
	/**
	 * Truncates a string to the given length.  It will optionally preserve
	 * HTML tags if $is_html is set to true.
	 *
	 * @param   string  $string        the string to truncate
	 * @param   int     $limit         the number of characters to truncate too
	 * @param   string  $continuation  the string to use to denote it was truncated
	 * @param   bool    $is_html       whether the string has HTML
	 * @return  string  the truncated string
	 */
	public static function truncate($string, $limit, $continuation = '...', $is_html = false)
	{
		$offset = 0;
		$tags = array();

		if ($is_html)
		{
			// Handle special characters.
			preg_match_all('/&[a-z]+;/i', strip_tags($string), $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);

			foreach ($matches as $match)
			{
				if ($match[0][1] >= $limit)
				{
					break;
				}
				$limit += (static::length($match[0][0]) - 1);
			}

			// Handle all the html tags.
			preg_match_all('/<[^>]+>([^<]*)/', $string, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);

			foreach ($matches as $match)
			{
				if($match[0][1] - $offset >= $limit)
				{
					break;
				}

				$tag = static::sub(strtok($match[0][0], " \t\n\r\0\x0B>"), 1);

				if($tag[0] != '/')
				{
					$tags[] = $tag;
				}
				elseif (end($tags) == static::sub($tag, 1))
				{
					array_pop($tags);
				}

				$offset += $match[1][1] - $match[0][1];
			}
		}

		$newString = static::sub($string, 0, $limit = min(static::length($string),  $limit + $offset));
		$newString .= (static::length($string) > $limit ? $continuation : '');
		$newString .= (count($tags = array_reverse($tags)) ? '</'.implode('></',$tags).'>' : '');

		return $newString;
	}

	/**
	 * Add's _1 to a string or increment the ending number to allow _2, _3, etc
	 *
	 * @param   string  $str  required
	 * @return  string
	 */
	public static function increment($str, $first = 1, $separator = '_')
	{
		preg_match('/(.+)'.$separator.'([0-9]+)$/', $str, $match);

		return isset($match[2]) ? $match[1].$separator.($match[2] + 1) : $str.$separator.$first;
	}

	/**
	 * Checks wether a string has a precific beginning.
	 *
	 * @param   string   $str          string to check
	 * @param   string   $start        beginning to check for
	 * @param   boolean  $ignore_case  wether to ignore the case
	 * @return  boolean  wether a string starts with a specified beginning
	 */
	public static function startsWith($str, $start, $ignoreCase = false)
	{
		return (bool) preg_match('/^'.preg_quote($start, '/').'/m'.($ignoreCase ? 'i' : ''), $str);
	}

	/**
	 * Checks wether a string has a precific ending.
	 *
	 * @param   string   $str          string to check
	 * @param   string   $end          ending to check for
	 * @param   boolean  $ignore_case  wether to ignore the case
	 * @return  boolean  wether a string ends with a specified ending
	 */
	public static function endsWith($str, $end, $ignoreCase = false)
	{
		return (bool) preg_match('/'.preg_quote($end, '/').'$/m'.($ignoreCase ? 'i' : ''), $str);
	}

	/**
	 * substr
	 *
	 * @param   string    $str       required
	 * @param   int       $start     required
	 * @param   int|null  $length
	 * @param   string    $encoding  default UTF-8
	 * @return  string
	 */
	public static function sub($str, $start, $length = null, $encoding = null)
	{
		$encoding or $encoding = function_exists('mb_strlen') ? mb_internal_encoding() : null;

		// substr functions don't parse null correctly
		$length = is_null($length) ? (function_exists('mb_substr') ? mb_strlen($str, $encoding) : strlen($str)) - $start : $length;

		return function_exists('mb_substr')
			? mb_substr($str, $start, $length, $encoding)
			: substr($str, $start, $length);
	}

	/**
	 * strlen
	 *
	 * @param   string  $str       required
	 * @param   string  $encoding  default UTF-8
	 * @return  int
	 */
	public static function length($str, $encoding = null)
	{
		$encoding or $encoding = function_exists('mb_strlen') ? mb_internal_encoding() : null;

		return function_exists('mb_strlen')
			? mb_strlen($str, $encoding)
			: strlen($str);
	}

	/**
	 * lower
	 *
	 * @param   string  $str       required
	 * @param   string  $encoding  default UTF-8
	 * @return  string
	 */
	public static function lower($str, $encoding = null)
	{
		$encoding or $encoding = function_exists('mb_strlen') ? mb_internal_encoding() : null;

		return function_exists('mb_strtolower')
			? mb_strtolower($str, $encoding)
			: strtolower($str);
	}

	/**
	 * upper
	 *
	 * @param   string  $str       required
	 * @param   string  $encoding  default UTF-8
	 * @return  string
	 */
	public static function upper($str, $encoding = null)
	{
		$encoding or $encoding = function_exists('mb_strlen') ? mb_internal_encoding() : null;

		return function_exists('mb_strtoupper')
			? mb_strtoupper($str, $encoding)
			: strtoupper($str);
	}

	/**
	 * lcfirst
	 *
	 * Does not strtoupper first
	 *
	 * @param   string  $str       required
	 * @param   string  $encoding  default UTF-8
	 * @return  string
	 */
	public static function lcfirst($str, $encoding = null)
	{
		$encoding or $encoding = function_exists('mb_strlen') ? mb_internal_encoding() : null;

		return function_exists('mb_strtolower')
			? mb_strtolower(mb_substr($str, 0, 1, $encoding), $encoding).
				mb_substr($str, 1, mb_strlen($str, $encoding), $encoding)
			: lcfirst($str);
	}

	/**
	 * ucfirst
	 *
	 * Does not strtolower first
	 *
	 * @param   string $str       required
	 * @param   string $encoding  default UTF-8
	 * @return   string
	 */
	public static function ucfirst($str, $encoding = null)
	{
		$encoding or $encoding = function_exists('mb_strlen') ? mb_internal_encoding() : null;

		return function_exists('mb_strtoupper')
			? mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
				mb_substr($str, 1, mb_strlen($str, $encoding), $encoding)
			: ucfirst($str);
	}

	/**
	 * ucwords
	 *
	 * First strtolower then ucwords
	 *
	 * ucwords normally doesn't strtolower first
	 * but MB_CASE_TITLE does, so ucwords now too
	 *
	 * @param   string   $str       required
	 * @param   string   $encoding  default UTF-8
	 * @return  string
	 */
	public static function ucwords($str, $encoding = null)
	{
		$encoding or $encoding = function_exists('mb_strlen') ? mb_internal_encoding() : null;

		return function_exists('mb_convert_case')
			? mb_convert_case($str, MB_CASE_TITLE, $encoding)
			: ucwords(strtolower($str));
	}

	/**
	  * Creates a random string of characters
	  *
	  * @param   string  the type of string
	  * @param   int     the number of characters
	  * @return  string  the random string
	  */
	public static function random($type = 'alnum', $length = 16, $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
	{
		switch($type)
		{
			case 'basic':
				return mt_rand();
				break;

			default:
			case 'alnum':
			case 'numeric':
			case 'nozero':
			case 'alpha':
			case 'distinct':
			case 'hexdec':
			case 'pool':
				switch ($type)
				{
					case 'alpha':
						$pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						break;

					default:
					case 'alnum':
						$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						break;

					case 'numeric':
						$pool = '0123456789';
						break;

					case 'nozero':
						$pool = '123456789';
						break;

					case 'distinct':
						$pool = '2345679ACDEFHJKLMNPRSTUVWXYZ';
						break;

					case 'hexdec':
						$pool = '0123456789abcdef';
						break;

					case 'pool':
						break;
				}

				$str = '';
				for ($i=0; $i < $length; $i++)
				{
					$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
				}
				return $str;
				break;

			case 'unique':
				return md5(uniqid(mt_rand()));
				break;

			case 'sha1' :
				return sha1(uniqid(mt_rand(), true));
				break;
		}
	}

	/**
	 * Returns a closure that will alternate between the args which to return.
	 * If you call the closure with false as the arg it will return the value without
	 * alternating the next time.
	 *
	 * @return  Closure
	 */
	public static function alternator()
	{
		// the args are the values to alternate
		$args = func_get_args();

		return function ($next = true) use ($args)
		{
			static $i = 0;
			return $args[($next ? $i++ : $i) % count($args)];
		};
	}

	/**
	 * Parse the params from a string using strtr()
	 *
	 * @param   string  string to parse
	 * @param   array   params to str_replace
	 * @return  string
	 */
	public static function tr($string, $array = array())
	{
		if (is_string($string))
		{
			$tr_arr = array();

			foreach ($array as $from => $to)
			{
				substr($from, 0, 1) !== ':' and $from = ':'.$from;
				$tr_arr[$from] = $to;
			}
			unset($array);

			return strtr($string, $tr_arr);
		}
		else
		{
			return $string;
		}
	}

	/**
	 * Strip html tags from a string or array of strings.
	 *
	 * @param   mixed  string or array to strip
	 * @return  mixed  string or array based on input
	 */
	public static function stripTags($value)
	{
		if ( ! is_array($value))
		{
			$value = filter_var($value, FILTER_SANITIZE_STRING);
		}
		else
		{
			foreach ($value as $k => $v)
			{
				$value[$k] = static::stripTags($v);
			}
		}

		return $value;
	}

	/**
	* Returns a list in a human readable form
	* IE: Coffee, tea and soda
	*
	* @param   array  a list of objects to print
	* @param   string the conjunction to join the array with(and, or)
	* @param   bool   whether or not to print a serial comma(, and)
	*
	*/
	public static function readableList($list,$conjunction = 'and', $serial_comma=true)
    {
        if(!is_array($list))
        {
        	return $list;
        }

        if(empty($list))
        {
        	return '';
        }

        $lastItem = array_pop($list);

        if (!empty($list)) {
            return trim(implode(', ', $list)) . ((!$serial_comma || count($list)==1)?" ${conjunction} ":", ${conjunction} ") . $lastItem;
        } else {
            return $lastItem;
        }
    }
}
