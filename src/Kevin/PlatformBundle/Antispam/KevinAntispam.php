<?php

namespace Kevin\PlatformBundle\Antispam;

class KevinAntispam
{
    private $mailer;
    private $locale;
    private $minLength;

    private function __construct(\Swift_Mailer $mailer, $locale, $minLength)
    {
        $this->mailer    = $mailer;
        $this->locale    = $locale;
        $this->minLength = (int) $minLength;
    }

    public function isSpam($text)
    {
        return strlen($text) < $this->minLength;
    }
}