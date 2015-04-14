<?php

namespace Works\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class WorksUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
