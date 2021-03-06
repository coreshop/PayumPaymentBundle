<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) CoreShop GmbH (https://www.coreshop.org)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

declare(strict_types=1);

namespace CoreShop\Bundle\PayumPaymentBundle\Controller;

use CoreShop\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Response;

class PaymentProviderController extends ResourceController
{
    public function getConfigAction(): Response
    {
        $factoryResults = [];

        foreach (array_keys($this->container->getParameter('coreshop.gateway_factories')) as $factory) {
            $factoryResults[] = [
                'type' => $factory,
                'name' => $factory,
            ];
        }

        return $this->viewHandler->handle(
            [
                'success' => true,
                'factories' => $factoryResults,
            ]
        );
    }
}
