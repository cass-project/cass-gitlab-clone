<?php
namespace CASS\Application\Bootstrap\Scripts;

use Zend\Expressive\Application;

interface AppInitScript
{
    public function __invoke(Application $app);
}