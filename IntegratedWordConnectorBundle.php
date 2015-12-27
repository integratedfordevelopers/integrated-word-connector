<?php

namespace Integrated\Bundle\WordConnectorBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Integrated\Bundle\WordConnectorBundle\DependencyInjection\IntegratedWordConnectorExtension;
/**
 * IntegratedWordConnectorBundle
 *
 * @author Nizar Ellouze <integrated@e-active.nl>
 */
class IntegratedWordConnectorBundle extends Bundle
{

    /**
     * @inheritdoc
     */
    public function getContainerExtension()
    {
        //comment
        return new IntegratedWordConnectorExtension();
    }

}
