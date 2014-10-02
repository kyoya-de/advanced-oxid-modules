<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 26.09.2014
 * Time: 15:55
 */

namespace Test\Module;

use D4rk4ng3l\Oxid\AdvancedModule\Annotations as Module;

/**
 * @Module\Module
 */
class Mod2
{
    /**
     * @Module\Method(class="oxarticle", method="getBasePrice", parentExecution="before", hasReturn=true)
     * @Module\Method(class="oxbasket", method="_calcTotalPrice", parentExecution="after", hasReturn=true)
     */
    public function getPrice()
    {
        mt_srand();
        return (mt_rand(1, 999999) / 100);
    }

} 