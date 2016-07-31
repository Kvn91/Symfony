<?php

namespace Kevin\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class KevinUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
