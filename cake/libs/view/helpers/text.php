<?php
/* SVN FILE: $Id: text.php,v 1.1 2009-10-14 03:01:28 sigep Exp $ */
/**
 * Text Helper
 *
 * Text manipulations: Highlight, excerpt, truncate, strip of links, convert email addresses to mailto: links...
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2007, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2007, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.cake.libs.view.helpers
 * @since			CakePHP(tm) v 0.10.0.1076
 * @version			$Revision: 1.1 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2009-10-14 03:01:28 $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Included libraries.
 *
 */
if (!class_exists('Flay')) {
	 uses('flay');
}
if (!class_exists('HtmlHelper')) {
	 uses('view' . DS . 'helpers' . DS . 'html');
}
/**
 * Text helper library.
 *
 * Text manipulations: Highlight, excerpt, truncate, strip of links, convert email addresses to mailto: links...
 *
 * @package		cake
 * @subpackage	cake.cake.libs.view.helpers
 */
class TextHelper extends Helper{
/**
 * Highlights a given phrase in a text.
 *
 * @param string $text Text to search the phrase in
 * @param string $phrase The phrase that will be searched
 * @param string $highlighter The piece of html with that the phrase will be highlighted
 * @return string The highlighted text
 * @access public
 */
	 function highlight($text, $phrase, $highlighter = '<span class="highlight">\1</span>') {
		  if (empty($phrase))
				return $text;

		  if (is_array($phrase)) {
				$replace=array();
				$with=array();

				foreach($phrase as $key => $value) {
					 if (empty($key)) {
						  $key  =$value;
						  $value=$highlighter;
					 }

					 $replace[]='|(' . $key . ')|';
					 $with[]=empty($value) ? $highlighter : $value;
				}

				return preg_replace($replace, $with, $text);
		  } else {
				return preg_replace("|({$phrase})|i", $highlighter, $text);
		  }
	 }
/**
 * Strips given text of all links (<a href=....)
 *
 * @param string $text Text
 * @return string The text without links
 * @access public
 */
	 function stripLinks($text) {
		  return preg_replace('|<a.*>(.*)<\/a>|im', '\1', $text);
	 }
/**
 * Adds links (<a href=....) to a given text, by finding text that begins with
 * strings like http:// and ftp://.
 *
 * @param string $text Text to add links to
 * @param array $htmlOptions Array of HTML options.
 * @return string The text with links
 * @access public
 */
	 function autoLinkUrls($text, $htmlOptions = array()) {
		  $options='array(';

		  foreach($htmlOptions as $option => $value) {
				$options .= "'$option' => '$value', ";
		  }

		  $options .= ')';

		  $text = preg_replace_callback('#((?:http|https|ftp|nntp)://[^ <]+)#',
												create_function('$matches',
																	 '$Html = new HtmlHelper(); $Html->tags = $Html->loadConfig(); return $Html->link($matches[0], $matches[0],' . $options . ');'),
												$text);
		  return preg_replace_callback('#(?<!http://|https://|ftp://|nntp://)(www\.[^\n\%\ <]+[^<\n\%\,\.\ <])#',
												 create_function('$matches',
																	  '$Html = new HtmlHelper(); $Html->tags = $Html->loadConfig(); return $Html->link($matches[0], "http://" . $matches[0],' . $options . ');'),
												 $text);
	 }
/**
 * Adds email links (<a href="mailto:....) to a given text.
 *
 * @param string $text Text
 * @param array $htmlOptions Array of HTML options.
 * @return string The text with links
 * @access public
 */
	 function autoLinkEmails($text, $htmlOptions = array()) {
		  $options='array(';

		  foreach($htmlOptions as $option => $value) {
				$options .= "'$option' => '$value', ";
		  }

		  $options .= ')';

		  return preg_replace_callback(
						'#([_A-Za-z0-9+-]+(?:\.[_A-Za-z0-9+-]+)*@[A-Za-z0-9-]+(?:\.[A-Za-z0-9-]+)*)#',
						create_function('$matches',
											 '$Html = new HtmlHelper(); $Html->tags = $Html->loadConfig(); return $Html->linkEmail($matches[0], $matches[0],' . $options . ');'),
						$text);
	 }
/**
 * Convert all links and email adresses to HTML links.
 *
 * @param string $text Text
 * @param array $htmlOptions Array of HTML options.
 * @return string The text with links
 * @access public
 */
	 function autoLink($text, $htmlOptions = array()) {
		  return $this->autoLinkEmails($this->autoLinkUrls($text, $htmlOptions), $htmlOptions);
	 }
/**
 * Truncates text.
 *
 * Cuts a string to the length of $length and replaces the last characters
 * with the ending if the text is longer than length.
 *
 * @param string  $text	String to truncate.
 * @param integer $length Length of returned string, including ellipsis.
 * @param string  $ending Ending to be appended to the trimmed string.
 * @param boolean $exact If false, $test will not be cut mid-word
 * @return string Trimmed string.
 * @access public
 */
	 function truncate($text, $length, $ending = '...', $exact = true) {
		  if (strlen($text) <= $length) {
				return $text;
		  } else {
				$truncate=substr($text, 0, $length - strlen($ending));

				if (!$exact) {
					 $spacepos=strrpos($truncate, ' ');

					 if (isset($spacepos)) {
						  return substr($truncate, 0, $spacepos) . $ending;
					 }
				}

				return $truncate . $ending;
		  }
	 }
/**
 * Alias for truncate().
 *
 * @see TextHelper::truncate()
 * @return Text::truncate()
 * @access public
 */
	 function trim() {
		  $args=func_get_args();
		  return call_user_func_array(array(&$this,
					  "truncate"),       $args);
	 }
/**
 * Extracts an excerpt from the text surrounding the phrase with a number of characters on each side determined by radius.
 *
 * @param string $text String to search the phrase in
 * @param string $phrase Phrase that will be searched for
 * @param integer $radius The amount of characters that will be returned on each side of the founded phrase
 * @param string $ending Ending that will be appended
 * @return string
 * @access public
 */
	 function excerpt($text, $phrase, $radius = 100, $ending = "...") {
		  if (empty($text) or empty($phrase))
				return $this->truncate($text, $radius * 2, $ending);

		  if ($radius < strlen($phrase))
				$radius=strlen($phrase);

		  $pos     =strpos($text, $phrase);
		  $startPos=$pos <= $radius ? 0 : $pos - $radius;
		  $endPos  =$pos + strlen($phrase) + $radius >= strlen($text)
			  ? strlen($text) : $pos + strlen($phrase) + $radius;

		  $excerpt =substr($text, $startPos, $endPos - $startPos);

		  if ($startPos != 0)
				$excerpt=substr_replace($excerpt, $ending, 0, strlen($phrase));

		  if ($endPos != strlen($text))
				$excerpt=substr_replace($excerpt, $ending, -strlen($phrase));

		  return $excerpt;
	 }
/**
 * Text-to-html parser, similar to Textile or RedCloth, only with a little different syntax.
 *
 * @param string $text String to "flay"
 * @param boolean $allowHtml Set to true if if html is allowed
 * @return string "Flayed" text
 * @todo Change this. We need a real Textile parser.
 * @access public
 */
	 function flay($text, $allowHtml = false) {
		  return Flay::toHtml($text, false, $allowHtml);
	 }
}
?>