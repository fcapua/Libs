<?php
/**
 * Author: Facundo CApua
 * Date: 4/9/12
 */
class UserHelper
{
    public static function generatePasswordHash($password)
    {

        return md5(SITE_HASH.$password);
    }
}
