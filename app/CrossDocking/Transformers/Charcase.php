<?php

namespace App\CrossDocking\Transformers;

class Charcase
{
    public static function regularize($text)
    {
        $result = null;
        $excession = ['da', 'de', 'di', 'do', 'du', 'para', 'por', 'pelo', 'com', 'sem', 'para'];
        $blacklist = ['entusiasta'];
        $replace   = [
            'P/ ' => 'para ',
            'P/' => 'para ',
        ];

        foreach(explode(' ', strtr($text, $replace)) as $word) {
            if (in_array($word, $blacklist)) {
                continue;
            }

            if (preg_match('/[A-Za-z]/', $word) && preg_match('/[0-9]/', $word)) {
                $result .= mb_strtoupper($word);
            } elseif (in_array($word, $excession)) {
                $result .= mb_strtolower($word);
            }else {
                $result .= ucfirst(mb_strtolower($word));
            }

            $result .= ' ';
        }

        return trim($result);
    }
}
