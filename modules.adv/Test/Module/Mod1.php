<?php
namespace Test\Module;

use D4rk4ng3l\Oxid\AdvancedModule\Annotations as Module;

/**
 * @Module\Module
 */
class Mod1
{
    /**
     * @Module\Method(class="oxarticle", method="getBasePrice", parentExecution="before", hasReturn=true)
     * @Module\Method(class="oxbasket", method="_calcDeliveryCost", parentExecution="after", hasReturn=true)
     */
    public function getPrice()
    {
        mt_srand();
        return (mt_rand(1, 999999) / 100);
    }

    private function fetchFromDB()
    {

    }
}