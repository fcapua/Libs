<?php
class StringHelper
{

    public static function varSinParametro($var, $parametro)
    {
    	$var = '&'.urldecode($var);
    	$return = preg_replace('/[\&\?]+'.$parametro.'=[^\&]*/','', $var);
    	return substr($return,1); #saco el & que le puse al inicio
    }


    public static function jsstring($value)
    {
    	$value = str_replace("\r","\n",$value); // mac > linux
    	$value = str_replace("\n\n","\n",$value); // windows > linux
    	$value = str_replace("\n"," ",$value); // linux > " "
    	$value = str_replace("'",'\'',$value);

    	return $value;
    }


    public static function emailValido( $email = '' )
    {
    	return preg_match( "/^
    	[\d\w\/+!=#|$?%{^&}*`'~-]
    	[\d\w\/\.+!=#|$?%{^&}*`'~-]*@
    	[A-Z0-9]
    	[A-Z0-9.-]{1,61}
    	[A-Z0-9]\.
    	[A-Z]{2,6}$/ix", $email );
    }

    public static function camelize($string, $pascalCase = false)
    {
        $string = str_replace(array('-', '_'), ' ', $string);
        $string = ucwords($string);
        $string = str_replace(' ', '', $string);

        // if(!$pascalCase) {
//
            // return lcfirst($string);
        // } 

        return $string;
    }

	/**
     * Translates a camel case string into a string with underscores (e.g. firstName -&gt; first_name)
     * @param    string   $str    String in camel case format
     * @return    string            $str Translated into underscore format
     */
    public static function uncamelize($str)
    {
        $str[0] = strtolower($str[0]);
        $func = create_function('$c', 'return "_" . strtolower($c[1]);');
        return preg_replace_callback('/([A-Z0-9])/', $func, $str);
    }

    /**
     *
     * Checks if the string matches any of the regular expressions. Returns true if matches, otherwise returns false.
     * @param string $string
     * @param mixed $regexs
     *
     * @return bool @matches
     */
    public static function matchRegexs($string, $regexs=array())
    {
        foreach($regexs as $regex){
            if (preg_match($regex, $string)){
                return true;
            }
        }

        return false;
    }

    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        $start  = $length * -1; //negative

        return (substr($haystack, $start) === $needle);
    }

    public static function cleanXss($val)
    {
        $val = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $val);
        $search = 'abcdefghijklmnopqrstuvwxyz';
        $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $search .= '1234567890!@#$%^&*()';
        $search .= '~`";:?+/={}[]-_|\'\\';
        for ($i = 0; $i < strlen($search); $i++) {
            $val = preg_replace('/(&#[x|X]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
            $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
        }
        $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
        $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
        $ra = array_merge($ra1, $ra2);
        $found = true;
        while ($found == true) {
            $val_before = $val;
            for ($i = 0; $i < sizeof($ra); $i++) {
                $pattern = '/';
                for ($j = 0; $j < strlen($ra[$i]); $j++) {
                    if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[x|X]0{0,8}([9][a][b]);?)?';
                    $pattern .= '|(&#0{0,8}([9][10][13]);?)?';
                    $pattern .= ')?';
                    }
                    $pattern .= $ra[$i][$j];
                }
                $pattern .= '/i';
                $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2);
                $val = preg_replace($pattern, $replacement, $val);
                if ($val_before == $val) {
                    $found = false;
                }
            }
        }
        return $val;
    }

    public static function BBCode($text)
    {
        $text = self::cleanXss(strip_tags($text,'<br><br /><p><span><b><i><a><font>'));
        $a = array(
            "/\[subTitulo\](.*?)\[\/subTitulo\]/is",
            "/\[nota\](.*?)\[\/nota\]/is",
            "/\[i\](.*?)\[\/i\]/is",
            "/\[b\](.*?)\[\/b\]/is",
            "/\[ul\](.*?)\[\/ul\]/is",
            "/\[li\](.*?)\[\/li\]/is",
            "/\[img\](.*?)\[\/img\]/is",
            "/\[color=(.*?)\](.*?)\[\/color\]/is",
            "/\[color= (.*?)\](.*?)\[\/color\]/is",
            "/\[url=(.*?)\](.*?)\[\/url\]/is",
            "/\[size=(.*?)\](.*?)\[\/size\]/is"
        );
        $b = array(
            "<span class=\"subTitulo\">$1</span>",
            "<span class=\"nota\">$1</span>",
            "<i>$1</i>",
            "<b>$1</b>",
            "<ul>$1</ul>",
            "<li>$1</li>",
            "<img src=\"$1\" />",
            '<font color="$1">$2</font>',
            '<font color="$1">$2</font>',
            "<a href=\"$1\" target=\"_blank\">$2</a>",
            "<span style=\"font-size:$1\">$2</span>"
        );
        $text = preg_replace($a, $b, $text);
        $text = nl2br($text);
        return $text;
    }

}