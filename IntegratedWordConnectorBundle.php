<?php

namespace Integrated\Bundle\WordConnectorBundle;

use Integrated\Bundle\ContentBundle\DependencyInjection\Compiler\DoctrineMongoDBMetadataFactoryPass;
use Integrated\Bundle\WordConnectorBundle\DependencyInjection\IntegratedWordConnectorExtension;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class IntegratedWordConnectorBundle extends Bundle
{


	/**
	 * @inheritdoc
	 */
	public function getContainerExtension()
	{
	}
}
