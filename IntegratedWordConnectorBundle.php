<?php

namespace Integrated\Bundle\WordConnectorBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Integrated\Bundle\WordConnectorBundle\DependencyInjection\IntegratedWordConnectorExtension;
/**
 * IntegratedWordConnectorBundle
 *
 * @author Nizar Ellouze <nizarellouze@yahoo.fr>
 */
class IntegratedWordConnectorBundle extends Bundle
{

    /**
     * @inheritdoc
     */
    public function getContainerExtension()
    {
        return new IntegratedWordConnectorExtension();
    }

}
