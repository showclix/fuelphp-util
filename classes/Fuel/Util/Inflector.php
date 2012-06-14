<?php

/**
 * Inflector library
 *
 * @package    Fuel\Common
 * @version    1.0.0
 * @license    MIT License
 * @copyright  2010 - 2012 Fuel Development Team
 */

namespace Fuel\Util;

class Inflector
{

	protected static $uncountableWords = array(
		'equipment', 'information', 'rice', 'money',
		'species', 'series', 'fish', 'meta'
	);

	protected static $pluralRules = array(
		'/^(ox)$/i'                 => '\1\2en',     // ox
		'/([m|l])ouse$/i'           => '\1ice',      // mouse, louse
		'/(matr|vert|ind)ix|ex$/i'  => '\1ices',     // matrix, vertex, index
		'/(x|ch|ss|sh)$/i'          => '\1es',       // search, switch, fix, box, process, address
		'/([^aeiouy]|qu)y$/i'       => '\1ies',      // query, ability, agency
		'/(hive)$/i'                => '\1s',        // archive, hive
		'/(?:([^f])fe|([lr])f)$/i'  => '\1\2ves',    // half, safe, wife
		'/sis$/i'                   => 'ses',        // basis, diagnosis
		'/([ti])um$/i'              => '\1a',        // datum, medium
		'/(p)erson$/i'              => '\1eople',    // person, salesperson
		'/(m)an$/i'                 => '\1en',       // man, woman, spokesman
		'/(c)hild$/i'               => '\1hildren',  // child
		'/(buffal|tomat)o$/i'       => '\1\2oes',    // buffalo, tomato
		'/(bu|campu)s$/i'           => '\1\2ses',    // bus, campus
		'/(alias|status|virus)$/i'  => '\1es',       // alias
		'/(octop)us$/i'             => '\1i',        // octopus
		'/(ax|cris|test)is$/i'      => '\1es',       // axis, crisis
		'/s$/'                     => 's',          // no change (compatibility)
		'/$/'                      => 's',
	);

	protected static $singularRules = array(
		'/(matr)ices$/i'         => '\1ix',
		'/(vert|ind)ices$/i'     => '\1ex',
		'/^(ox)en/i'             => '\1',
		'/(alias)es$/i'          => '\1',
		'/([octop|vir])i$/i'     => '\1us',
		'/(cris|ax|test)es$/i'   => '\1is',
		'/(shoe)s$/i'            => '\1',
		'/(o)es$/i'              => '\1',
		'/(bus|campus)es$/i'     => '\1',
		'/([m|l])ice$/i'         => '\1ouse',
		'/(x|ch|ss|sh)es$/i'     => '\1',
		'/(m)ovies$/i'           => '\1\2ovie',
		'/(s)eries$/i'           => '\1\2eries',
		'/([^aeiouy]|qu)ies$/i'  => '\1y',
		'/([lr])ves$/i'          => '\1f',
		'/(tive)s$/i'            => '\1',
		'/(hive)s$/i'            => '\1',
		'/([^f])ves$/i'          => '\1fe',
		'/(^analy)ses$/i'        => '\1sis',
		'/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '\1\2sis',
		'/([ti])a$/i'            => '\1um',
		'/(p)eople$/i'           => '\1\2erson',
		'/(m)en$/i'              => '\1an',
		'/(s)tatuses$/i'         => '\1\2tatus',
		'/(c)hildren$/i'         => '\1\2hild',
		'/(n)ews$/i'             => '\1\2ews',
		'/([^us])s$/i'           => '\1',
	);
	
	protected static $ascii = array(
		'/æ|ǽ/' => 'ae',
		'/œ/' => 'oe',
		'/À|Á|Â|Ã|Ä|Å|Ǻ|Ā|Ă|Ą|Ǎ|А/' => 'A',
		'/à|á|â|ã|ä|å|ǻ|ā|ă|ą|ǎ|ª|а/' => 'a',
		'/Б/' => 'B',
		'/б/' => 'b',
		'/Ç|Ć|Ĉ|Ċ|Č|Ц/' => 'C',
		'/ç|ć|ĉ|ċ|č|ц/' => 'c',
		'/Ð|Ď|Đ|Д/' => 'D',
		'/ð|ď|đ|д/' => 'd',
		'/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě|Е|Ё|Э/' => 'E',
		'/è|é|ê|ë|ē|ĕ|ė|ę|ě|е|ё|э/' => 'e',
		'/Ф/' => 'F',
		'/ƒ|ф/' => 'f',
		'/Ĝ|Ğ|Ġ|Ģ|Г/' => 'G',
		'/ĝ|ğ|ġ|ģ|г/' => 'g',
		'/Ĥ|Ħ|Х/' => 'H',
		'/ĥ|ħ|х/' => 'h',
		'/Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ|И/' => 'I',
		'/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı|и/' => 'i',
		'/Ĵ|Й/' => 'J',
		'/ĵ|й/' => 'j',
		'/Ķ|К/' => 'K',
		'/ķ|к/' => 'k',
		'/Ĺ|Ļ|Ľ|Ŀ|Ł|Л/' => 'L',
		'/ĺ|ļ|ľ|ŀ|ł|л/' => 'l',
		'/М/' => 'M',
		'/м/' => 'm',
		'/Ñ|Ń|Ņ|Ň|Н/' => 'N',
		'/ñ|ń|ņ|ň|ŉ|н/' => 'n',
		'/Ò|Ó|Ö|Ő|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ|О/' => 'O',
		'/ò|ó|ö|ő|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º|о/' => 'o',
		'/П/' => 'P',
		'/п/' => 'p',
		'/Ŕ|Ŗ|Ř|Р/' => 'R',
		'/ŕ|ŗ|ř|р/' => 'r',
		'/Ś|Ŝ|Ş|Š|С/' => 'S',
		'/ś|ŝ|ş|š|ſ|с/' => 's',
		'/Ţ|Ť|Ŧ|Т/' => 'T',
		'/ţ|ť|ŧ|т/' => 't',
		'/Ù|Ú|Ü|Ű|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ|У/' => 'U',
		'/ù|ú|ü|ű|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ|у/' => 'u',
		'/В/' => 'V',
		'/в/' => 'v',
		'/Ý|Ÿ|Ŷ|Ы/' => 'Y',
		'/ý|ÿ|ŷ|ы/' => 'y',
		'/Ŵ/' => 'W',
		'/ŵ/' => 'w',
		'/Ź|Ż|Ž|З/' => 'Z',
		'/ź|ż|ž|з/' => 'z',
		'/Æ|Ǽ/' => 'AE',
		'/ß/'=> 'ss',
		'/Ĳ/' => 'IJ',
		'/ĳ/' => 'ij',
		'/Œ/' => 'OE',
		'/Ч/' => 'Ch',
		'/ч/' => 'ch',
		'/Ю/' => 'Ju',
		'/ю/' => 'ju',
		'/Я/' => 'Ja',
		'/я/' => 'ja',
		'/Ш/' => 'Sh',
		'/ш/' => 'sh',
		'/Щ/' => 'Shch',
		'/щ/' => 'shch',
		'/Ж/' => 'Zh',
		'/ж/' => 'zh',
	);


	/**
	 * Add order suffix to numbers ex. 1st 2nd 3rd 4th 5th
	 *
	 * @param   int     the number to ordinalize
	 * @return  string  the ordinalized version of $number
	 * @link    http://snipplr.com/view/4627/a-function-to-add-a-prefix-to-numbers-ex-1st-2nd-3rd-4th-5th/
	 */
	public static function ordinalize($number)
	{
		if ( ! is_numeric($number))
		{
			return $number;
		}

		if (in_array(($number % 100), range(11, 13)))
		{
			return $number . 'th';
		}
		else
		{
			switch ($number % 10)
			{
				case 1:
					return $number . 'st';
					break;
				case 2:
					return $number . 'nd';
					break;
				case 3:
					return $number . 'rd';
					break;
				default:
					return $number . 'th';
					break;
			}
		}
	}

	/**
	 * Gets the plural version of the given word
	 *
	 * @param   string  the word to pluralize
	 * @return  string  the plural version of $word
	 */
	public static function pluralize($word)
	{
		$result = strval($word);

		if ( ! static::isCountable($result))
		{
			return $result;
		}

		foreach (static::$pluralRules as $rule => $replacement)
		{
			if (preg_match($rule, $result))
			{
				$result = preg_replace($rule, $replacement, $result);
				break;
			}
		}

		return $result;
	}

	/**
	 * Gets the singular version of the given word
	 *
	 * @param   string  the word to singularize
	 * @return  string  the singular version of $word
	 */
	public static function singularize($word)
	{
		$result = strval($word);

		if ( ! static::isCountable($result))
		{
			return $result;
		}

		foreach (static::$singularRules as $rule => $replacement)
		{
			if (preg_match($rule, $result))
			{
				$result = preg_replace($rule, $replacement, $result);
				break;
			}
		}

		return $result;
	}

	/**
	 * Takes a string that has words seperated by underscores and turns it into
	 * a CamelCased string.
	 *
	 * @param   string  the underscored word
	 * @return  string  the CamelCased version of $underscoredWord
	 */
	public static function camelize($underscoredWord)
	{
		return preg_replace('/(^|_)(.)/e', "strtoupper('\\2')", strval($underscoredWord));
	}

	/**
	 * Takes a CamelCased string and returns an underscore separated version.
	 *
	 * @param   string  the CamelCased word
	 * @return  string  an underscore separated version of $camelCasedWord
	 */
	public static function underscore($camelCasedWord)
	{
		return Str::lower(preg_replace('/([A-Z]+)([A-Z])/', '\1_\2', preg_replace('/([a-z\d])([A-Z])/', '\1_\2', strval($camelCasedWord))));
	}

	/**
	 * Translate string to 7-bit ASCII
	 * Only works with UTF-8.
	 *
	 * @param   string
	 * @return  string
	 */
	public static function ascii($str)
	{
		// Translate unicode characters to their simpler counterparts
		$str = preg_replace(array_keys(static::$ascii), array_values(static::$ascii), $str);

		// remove any left over non 7bit ASCII
		return preg_replace('/[^\x09\x0A\x0D\x20-\x7E]/', '', $str);
	}

	/**
	 * Converts your text to a URL-friendly title so it can be used in the URL.
	 * Only works with UTF8 input and and only outputs 7 bit ASCII characters.
	 *
	 * @param   string  the text
	 * @param   string  the separator (either - or _)
	 * @return  string  the new title
	 */
	public static function friendlyTitle($str, $sep = '-', $lowercase = false)
	{
		// Allow underscore, otherwise default to dash
		$sep = $sep === '_' ? '_' : '-';

		// Remove tags
		$str = Str::stripTags($str);

		// Decode all entities to their simpler forms
		$str = html_entity_decode($str, ENT_QUOTES, 'UTF-8');

		// Remove all quotes.
		$str = preg_replace("#[\"\']#", '', $str);

		// Only allow 7bit characters
		$str = static::ascii($str);

		// Strip unwanted characters
		$str = preg_replace("#[^a-z0-9]#i", $sep, $str);
		$str = preg_replace("#[/_|+ -]+#", $sep, $str);
		$str = trim($str, $sep);

		if ($lowercase === true)
		{
			$str = Str::lower($str);
		}

		return $str;
	}

	/**
	 * Turns an underscore or dash separated word and turns it into a human looking string.
	 *
	 * @param   string  the word
	 * @param   string  the separator (either _ or -)
	 * @param   bool    lowercare string and upper case first
	 * @return  string  the human version of given string
	 */
	public static function humanize($str, $sep = '_', $lowercase = true)
	{
		// Allow dash, otherwise default to underscore
		$sep = $sep != '-' ? '_' : $sep;

		if ($lowercase === true)
		{
			$str = Str::ucfirst($str);
		}

		return str_replace($sep, " ", strval($str));
	}

	/**
	 * Takes the namespace off the given class name.
	 *
	 * @param   string  the class name
	 * @return  string  the string without the namespace
	 */
	public static function denamespace($className)
	{
		$className = trim($className, '\\');
		if ($lastSeparator = strrpos($className, '\\'))
		{
			$className = substr($className, $lastSeparator + 1);
		}
		return $className;
	}

	/**
	 * Returns the namespace of the given class name.
	 *
	 * @param   string  $class_name  the class name
	 * @return  string  the string without the namespace
	 */
	public static function getNamespace($className)
	{
		$className = trim($className, '\\');
		if ($lastSeparator = strrpos($className, '\\'))
		{
			return substr($className, 0, $lastSeparator + 1);
		}
		return '';
	}

	/**
	 * Takes a class name and determines the table name.  The table name is a
	 * pluralized version of the class name.
	 *
	 * @param   string  the table name
	 * @return  string  the table name
	 */
	public static function tableize($className)
	{
		$className = static::denamespace($className);
		return Str::lower(static::pluralize(static::underscore($className)));
	}

	/**
	 * Takes an underscored classname and uppercases all letters after the underscores.
	 *
	 * @param   string  classname
	 * @param   string  separator
	 * @return  string
	 */
	public static function wordsToUpper($class, $sep = '_')
	{
		return str_replace(' ', $sep, ucwords(str_replace($sep, ' ', $class)));
	}

	/**
	 * Takes a table name and creates the class name.
	 *
	 * @param   string  the table name
	 * @param   bool    whether to singularize the table name or not
	 * @return  string  the class name
	 */
	public static function classify($name, $forceSingular = true)
	{
		$class = ($forceSingular) ? static::singularize($name) : $name;
		return static::wordsToUpper($class);
	}

	/**
	 * Gets the foreign key for a given class.
	 *
	 * @param   string  the class name
	 * @param   bool    $use_underscore	whether to use an underscore or not
	 * @return  string  the foreign key
	 */
	public static function foreignKey($className, $useUnderscore = true)
	{
		$class_name = static::denamespace(Str::lower($className));
		return static::underscore($className).($useUnderscore ? "_id" : "id");
	}

	/**
	 * Checks if the given word has a plural version.
	 *
	 * @param   string  the word to check
	 * @return  bool    if the word is countable
	 */
	public static function isCountable($word)
	{
		return ! (in_array(Str::lower(Strval($word)), static::$uncountableWords));
	}
}