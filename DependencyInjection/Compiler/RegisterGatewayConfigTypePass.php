<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) CoreShop GmbH (https://www.coreshop.org)
 * @license    https://www.coreshop.org/license     GPLv3 and CCL
 */

declare(strict_types=1);

namespace CoreShop\Bundle\PayumPaymentBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RegisterGatewayConfigTypePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('coreshop.form_registry.payum_gateway_config')) {
            return;
        }

        $formRegistry = $container->findDefinition('coreshop.form_registry.payum_gateway_config');
        $gatewayFactories = [];

        $gatewayConfigurationTypes = $container->findTaggedServiceIds('coreshop.gateway_configuration_type');

        foreach ($gatewayConfigurationTypes as $id => $attributes) {
            $definition = $container->findDefinition($id);

            foreach ($attributes as $tags) {
                if (!isset($tags['type'])) {
                    $tags['type'] = Container::underscore(substr(strrchr($definition->getClass(), '\\'), 1));
                }

                $gatewayFactories[$tags['type']] = $tags['type'];

                $formRegistry->addMethodCall(
                    'add',
                    ['gateway_config', $tags['type'], $container->getDefinition($id)->getClass()]
                );
            }
        }

        $gatewayFactories = array_merge($gatewayFactories, ['offline' => 'coreshop.payum_gateway_factory.offline']);
        ksort($gatewayFactories);

        $container->setParameter('coreshop.gateway_factories', $gatewayFactories);
    }
}
