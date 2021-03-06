<?php
namespace CASS\Util;

class GenerateRandomString
{
    static public function gen(int $length = 10) {
        /** @see http://stackoverflow.com/questions/4356289/php-random-string-generator */
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';

        for($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}