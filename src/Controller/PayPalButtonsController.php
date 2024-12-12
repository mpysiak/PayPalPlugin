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

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\PayPalPlugin\Processor\LocaleProcessorInterface;
use Sylius\PayPalPlugin\Provider\AvailableCountriesProviderInterface;
use Sylius\PayPalPlugin\Provider\PayPalConfigurationProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

final readonly class PayPalButtonsController
{
    public function __construct(
        private Environment $twig,
        private UrlGeneratorInterface $router,
        private ChannelContextInterface $channelContext,
        private LocaleContextInterface $localeContext,
        private PayPalConfigurationProviderInterface $payPalConfigurationProvider,
        private OrderRepositoryInterface $orderRepository,
        private AvailableCountriesProviderInterface $availableCountriesProvider,
        private LocaleProcessorInterface $localeProcessor,
    ) {
    }

    public function renderProductPageButtonsAction(Request $request): Response
    {
        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();

        try {
            return new Response($this->twig->render('@SyliusPayPalPlugin/pay_from_product_page.html.twig', [
                'available_countries' => $this->availableCountriesProvider->provide(),
                'clientId' => $this->payPalConfigurationProvider->getClientId($channel),
                'completeUrl' => $this->router->generate('sylius_shop_checkout_complete'),
                'errorPayPalPaymentUrl' => $this->router->generate('sylius_paypal_shop_payment_error'),
                'locale' => $this->localeProcessor->process($this->localeContext->getLocaleCode()),
                'processPayPalOrderUrl' => $this->router->generate('sylius_paypal_shop_process_paypal_order'),
            ]));
        } catch (\InvalidArgumentException $exception) {
            return new Response('');
        }
    }

    public function renderCartPageButtonsAction(Request $request): Response
    {
        $orderId = $request->attributes->getInt('orderId');
        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();
        /** @var OrderInterface $order */
        $order = $this->orderRepository->find($orderId);

        try {
            return new Response($this->twig->render('@SyliusPayPalPlugin/pay_from_cart_page.html.twig', [
                'available_countries' => $this->availableCountriesProvider->provide(),
                'clientId' => $this->payPalConfigurationProvider->getClientId($channel),
                'completeUrl' => $this->router->generate('sylius_shop_checkout_complete'),
                'createPayPalOrderFromCartUrl' => $this->router->generate('sylius_paypal_shop_create_paypal_order_from_cart', ['id' => $orderId]),
                'currency' => $order->getCurrencyCode(),
                'errorPayPalPaymentUrl' => $this->router->generate('sylius_paypal_shop_payment_error'),
                'locale' => $this->localeProcessor->process((string) $order->getLocaleCode()),
                'orderId' => $orderId,
                'partnerAttributionId' => $this->payPalConfigurationProvider->getPartnerAttributionId($channel),
                'processPayPalOrderUrl' => $this->router->generate('sylius_paypal_shop_process_paypal_order'),
            ]));
        } catch (\InvalidArgumentException $exception) {
            return new Response('');
        }
    }

    public function renderPaymentPageButtonsAction(Request $request): Response
    {
        $orderId = $request->attributes->getInt('orderId');
        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();
        /** @var OrderInterface $order */
        $order = $this->orderRepository->find($orderId);

        try {
            return new Response($this->twig->render('@SyliusPayPalPlugin/pay_from_payment_page.html.twig', [
                'available_countries' => $this->availableCountriesProvider->provide(),
                'cancelPayPalPaymentUrl' => $this->router->generate('sylius_paypal_shop_cancel_payment'),
                'clientId' => $this->payPalConfigurationProvider->getClientId($channel),
                'currency' => $order->getCurrencyCode(),
                'completePayPalOrderFromPaymentPageUrl' => $this->router->generate('sylius_paypal_shop_complete_paypal_order_from_payment_page', ['id' => $orderId]),
                'createPayPalOrderFromPaymentPageUrl' => $this->router->generate('sylius_paypal_shop_create_paypal_order_from_payment_page', ['id' => $orderId]),
                'errorPayPalPaymentUrl' => $this->router->generate('sylius_paypal_shop_payment_error'),
                'locale' => $this->localeProcessor->process((string) $order->getLocaleCode()),
                'orderId' => $orderId,
                'partnerAttributionId' => $this->payPalConfigurationProvider->getPartnerAttributionId($channel),
            ]));
        } catch (\InvalidArgumentException $exception) {
            return new Response('');
        }
    }
}
