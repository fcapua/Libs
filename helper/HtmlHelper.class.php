<?php
/**
 * @author: Facundo Capua
 *        Date: 4/14/12
 */
class HtmlHelper
{
    public static function arrayToHtmlAttributes($attributes)
    {
        $return = '';
        if (!empty($attributes)) {
            foreach ($attributes as $name => $value) {
                $return = $name . '="' . $value . '"';
            }
        }

        return $return;
    }
}
