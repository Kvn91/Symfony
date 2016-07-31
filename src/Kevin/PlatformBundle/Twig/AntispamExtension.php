<?php
/**
 * Created by PhpStorm.
 * User: kevindargere
 * Date: 31/07/2016
 * Time: 19:03
 */

namespace Kevin\PlatformBundle\Twig;


use Kevin\PlatformBundle\Antispam\KevinAntispam;

class AntispamExtension extends \Twig_Extension
{
    /**
     * @var KevinAntispam
     */
    private $kevinAntispam;

    public function __construct($kevinAntispam)
    {
        $this->kevinAntispam = $kevinAntispam;
    }

    public function checkIfArgumentIsSpam($text)
    {
        return $this->kevinAntispam->isSpam($text);
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('checkIfSpam', array($this, 'checkIfArgumentIsSpam'))
        );
    }

    public function getName()
    {
        return 'KevinAntispam';
    }
}