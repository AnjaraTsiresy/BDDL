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

        $decoded = utf8_decode($str);
        if (mb_detect_encoding($decoded , 'UTF-8', true) === false)
            return $str;
        return $decoded;
    }

    public function getName()
    {
        return 'vocabulaire_extension';
    }
}