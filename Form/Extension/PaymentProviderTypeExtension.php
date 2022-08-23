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

namespace CoreShop\Bundle\PayumPaymentBundle\Form\Extension;

use CoreShop\Bundle\PaymentBundle\Form\Type\PaymentProviderType;
use CoreShop\Bundle\PayumPaymentBundle\Form\Type\GatewayConfigType;
use CoreShop\Component\PayumPayment\Model\PaymentProviderInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class PaymentProviderTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('gatewayConfig', GatewayConfigType::class)
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $paymentMethod = $event->getData();

                if (!$paymentMethod instanceof PaymentProviderInterface) {
                    return;
                }

                $gatewayConfig = $paymentMethod->getGatewayConfig();
                /** @psalm-suppress DocblockTypeContradiction */
                if (null === $gatewayConfig->getGatewayName()) {
                    $gatewayConfig->setGatewayName($paymentMethod->getIdentifier());
                }
            });
    }

    public function getExtendedType(): string
    {
        return PaymentProviderType::class;
    }

    public static function getExtendedTypes(): array
    {
        return [PaymentProviderType::class];
    }
}
