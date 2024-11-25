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

namespace Sylius\PayPalPlugin\Api;

use Sylius\PayPalPlugin\Client\PayPalClientInterface;

final readonly class RefundPaymentApi implements RefundPaymentApiInterface
{
    public function __construct(private PayPalClientInterface $client)
    {
    }

    public function refund(
        string $token,
        string $paymentId,
        string $payPalAuthAssertion,
        string $invoiceNumber,
        string $amount,
        string $currencyCode,
    ): array {
        return $this->client->post(
            sprintf('v2/payments/captures/%s/refund', $paymentId),
            $token,
            ['amount' => ['value' => $amount, 'currency_code' => $currencyCode], 'invoice_number' => $invoiceNumber],
            ['PayPal-Auth-Assertion' => $payPalAuthAssertion],
        );
    }
}
