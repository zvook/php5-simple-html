<?php

/**
 * @author S.C. Chen <me578022@gmail.com>
 * @author Dmitry Klyukin <zvookbox@gmail.com>
 */

namespace SimpleHtml;

use SimpleHtml\SimpleHtmlDom\simple_html_dom;
use SimpleHtml\SimpleHtmlDom\simple_html_dom_node;

define('HDOM_TYPE_ELEMENT', 1);
define('HDOM_TYPE_COMMENT', 2);
define('HDOM_TYPE_TEXT', 3);
define('HDOM_TYPE_ENDTAG', 4);
define('HDOM_TYPE_ROOT', 5);
define('HDOM_TYPE_UNKNOWN', 6);
define('HDOM_QUOTE_DOUBLE', 0);
define('HDOM_QUOTE_SINGLE', 1);
define('HDOM_QUOTE_NO', 3);
define('HDOM_INFO_BEGIN', 0);
define('HDOM_INFO_END', 1);
define('HDOM_INFO_QUOTE', 2);
define('HDOM_INFO_SPACE', 3);
define('HDOM_INFO_TEXT', 4);
define('HDOM_INFO_INNER', 5);
define('HDOM_INFO_OUTER', 6);
define('HDOM_INFO_ENDSPACE', 7);
define('DEFAULT_TARGET_CHARSET', 'UTF-8');
define('DEFAULT_BR_TEXT', "\r\n");
define('DEFAULT_SPAN_TEXT', " ");

/**
 * Class Dom
 *
 * @package SimpleHtml
 */
class Dom
{
    /**
     * @var int
     */
    public static $maxFileSize = 600000;

    /**
     * @param $url
     * @param bool|false $use_include_path
     * @param null $context
     * @param bool|true $lowercase
     * @param bool|true $forceTagsClosed
     * @param string $target_charset
     * @param bool|true $stripRN
     * @param string $defaultBRText
     * @param string $defaultSpanText
     * @return simple_html_dom
     * @throws \Exception
     */
    public static function file_get_html($url, $use_include_path = false, $context = null, $lowercase = true, $forceTagsClosed = true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN = true, $defaultBRText = DEFAULT_BR_TEXT, $defaultSpanText = DEFAULT_SPAN_TEXT)
    {
        // We DO force the tags to be terminated.
        $dom = new simple_html_dom(null, $lowercase, $forceTagsClosed, $target_charset, $stripRN, $defaultBRText, $defaultSpanText);
        // For sourceforge users: uncomment the next line and comment the retreive_url_contents line 2 lines down if it is not already done.
        $contents = file_get_contents($url, $use_include_path, $context);
        // Paperg - use our own mechanism for getting the contents as we want to control the timeout.
        //$contents = retrieve_url_contents($url);
        if (empty($contents) || strlen($contents) > self::$maxFileSize) {
            throw new \Exception('File is empty or too large');
        }
        // The second parameter can force the selectors to all be lowercase.
        $dom->load($contents, $lowercase, $stripRN);
        return $dom;
    }

    /**
     * @param $str
     * @param bool|true $lowercase
     * @param bool|true $forceTagsClosed
     * @param string $target_charset
     * @param bool|true $stripRN
     * @param string $defaultBRText
     * @param string $defaultSpanText
     * @return simple_html_dom
     * @throws \Exception
     */
    public static function str_get_html($str, $lowercase = true, $forceTagsClosed = true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN = true, $defaultBRText = DEFAULT_BR_TEXT, $defaultSpanText = DEFAULT_SPAN_TEXT)
    {
        $dom = new simple_html_dom(null, $lowercase, $forceTagsClosed, $target_charset, $stripRN, $defaultBRText, $defaultSpanText);
        if (empty($str) || strlen($str) > self::$maxFileSize) {
            $dom->clear();
            throw new \Exception('HTML string is empty or too large');
        }
        $dom->load($str, $lowercase, $stripRN);
        return $dom;
    }

    /**
     * @param simple_html_dom_node $node
     */
    public static function dump_html_tree(simple_html_dom_node $node)
    {
        $node->dump($node);
    }
}