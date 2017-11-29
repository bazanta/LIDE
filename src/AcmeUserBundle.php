<?php

namespace Acme\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
/**
 * Description of AcmeUserBundle
 *
 * @author etudiant
 */
class AcmeUserBundle extends Bundle{
    //put your code here
    
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
