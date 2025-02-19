<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\PayPalPlugin\Controller;

use Payum\Core\Model\GatewayConfigInterface;
use Sylius\Component\Core\Factory\AddressFactoryInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Sylius\PayPalPlugin\Api\CacheAuthorizeClientApiInterface;
use Sylius\PayPalPlugin\Api\OrderDetailsApiInterface;
use Sylius\PayPalPlugin\Api\UpdateOrderApiInterface;
use Sylius\PayPalPlugin\Provider\PaymentProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class UpdatePayPalOrderAction
{
    private PaymentProviderInterface $paymentProvider;

    private CacheAuthorizeClientApiInterface $authorizeClientApi;

    private ?OrderDetailsApiInterface $orderDetailsApi;

    private UpdateOrderApiInterface $updateOrderApi;

    private AddressFactoryInterface $addressFactory;

    private OrderProcessorInterface $orderProcessor;

    public function __construct(
        PaymentProviderInterface $paymentProvider,
        CacheAuthorizeClientApiInterface $authorizeClientApi,
        ?OrderDetailsApiInterface $orderDetailsApi,
        UpdateOrderApiInterface $updateOrderApi,
        AddressFactoryInterface $addressFactory,
        OrderProcessorInterface $orderProcessor,
    ) {
        $this->paymentProvider = $paymentProvider;
        $this->authorizeClientApi = $authorizeClientApi;
        $this->orderDetailsApi = $orderDetailsApi;
        $this->updateOrderApi = $updateOrderApi;
        $this->addressFactory = $addressFactory;
        $this->orderProcessor = $orderProcessor;

        if (null !== $this->orderDetailsApi) {
            trigger_deprecation(
                'sylius/paypal-plugin',
                '1.7',
                sprintf(
                    'Passing an instance of "%s" as the first argument is deprecated and will be prohibited in 2.0',
                    OrderDetailsApiInterface::class,
                ),
            );
        }
    }

    public function __invoke(Request $request): Response
    {
        $payment = $this->paymentProvider->getByPayPalOrderId((string) $request->request->get('orderID'));
        /** @var OrderInterface $order */
        $order = $payment->getOrder();

        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $payment->getMethod();
        $token = $this->authorizeClientApi->authorize($paymentMethod);

        /** @var array $shippingAddress */
        $shippingAddress = $request->request->get('shipping_address');

        /** @var AddressInterface $address */
        $address = $this->addressFactory->createNew();
        $address->setFirstName('Temp');
        $address->setLastName('Temp');
        $address->setStreet('Temp');
        $address->setCity((string) $shippingAddress['city']);
        $address->setPostcode((string) $shippingAddress['postal_code']);
        $address->setCountryCode((string) $shippingAddress['country_code']);
        $order->setBillingAddress($address);
        $order->setShippingAddress($address);

        $this->orderProcessor->process($order);

        /** @var GatewayConfigInterface $gatewayConfig */
        $gatewayConfig = $paymentMethod->getGatewayConfig();

        $response = $this->updateOrderApi->update(
            $token,
            (string) $request->request->get('orderID'),
            $payment,
            $payment->getDetails()['reference_id'],
            $gatewayConfig->getConfig()['merchant_id'],
        );

        return new JsonResponse($response);
    }
}
