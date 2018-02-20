<?php
/**
 * Created by PhpStorm.
 * User: Jims
 * Date: 15/02/2018
 * Time: 22:08
 */

namespace Formation\VocabulaireBundle\Twig;


class VocabulaireExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
          new \Twig_SimpleFilter('convert_utf8',array($this,'convert_utf8')),
        );
    }

    public function convert_utf8( $str ) {

        if (strpos($str, 'é') !== false) {
            return $str;
        }
        if (strpos($str, 'è') !== false) {
            return $str;
        }
        if (strpos($str, 'à') !== false) {
            return $str;
        }
        if (strpos($str, 'ç') !== false) {
            return $str;
        }
        $decoded = str_replace("â€™", "<<<<<<<<<<", $str);
        $decoded = str_replace("â€", "wwwwwwwwwwwwwwwwwww", $decoded);
        $decoded = str_replace("â€œ", "??", $decoded);
        $decoded = str_replace("â€¦â€", "++++", $decoded);
        $decoded = str_replace("â€¦", ">>>>>>>>>>>>>>>>>>>", $decoded);
        $decoded = str_replace("â€", "----", $decoded);
        $decoded = str_replace("Å“", "======", $decoded);
        $decoded = str_replace("Ã‰", "@@@@@@@@@@@@@@@@@@@@@@@@@@", $decoded);



        $decoded = mb_convert_encoding($decoded, 'ISO-8859-1', 'UTF-8');

        $decoded = str_replace("??", "'", $decoded);
        $decoded = str_replace("wwwwwwwwwwwwwwwwwww", "'", $decoded);
        $decoded = str_replace("<<<<<<<<<<", "'", $decoded);
        $decoded = str_replace("----", "“", $decoded);
        $decoded = str_replace("++++", "…”", $decoded);
        $decoded = str_replace(">>>>>>>>>>>>>>>>>>>", "…", $decoded);
        $decoded = str_replace("======", "œ", $decoded);
        $decoded = str_replace("@@@@@@@@@@@@@@@@@@@@@@@@@@", "É", $decoded);

        return $decoded;
        // return $str;
    }

    public function getName()
    {
        return 'vocabulaire_extension';
    }
}