### UPGRADE FROM 1.6 to 1.7

1. Support for Sylius 1.14 has been added, it is now the recommended Sylius version to use.

1. Support for Sylius 1.12 has been dropped, upgrade your application to [Sylius 1.13](https://github.com/Sylius/Sylius/blob/1.13/UPGRADE-1.13.md).
   or [Sylius 1.14](https://github.com/Sylius/Sylius/blob/1.14/UPGRADE-1.14.md).

1. The following classes have been deprecated and will be removed in Sylius/PayPalPlugin 2.0:
   - `Sylius\PayPalPlugin\Form\Type\ChangePaymentMethodType`
   - `Sylius\PayPalPlugin\Form\Type\SelectPaymentType`
   - `Sylius\PayPalPlugin\Controller\PayPalOrderItemController`

1. The following old parameters have been deprecated and will be removed in PayPalPlugin 2.0. Use the corresponding new parameters instead:

   | Old parameter                            | New parameter                            | 
   |------------------------------------------|------------------------------------------|
   | `sylius.paypal.prioritized_factory_name` | `sylius_paypal.prioritized_factory_name` |
   | `sylius.pay_pal.request_trials_limit`    | `sylius_paypal.request_trials_limit`     |
   | `sylius.paypal.logging.increased`        | `sylius_paypal.logging.increased`        |
   | `sylius.pay_pal.facilitator_url`         | `sylius_paypal.facilitator_url`          |
   | `sylius.pay_pal.api_base_url`            | `sylius_paypal.api_base_url`             |
   | `sylius.pay_pal.reports_sftp_host`       | `sylius_paypal.reports_sftp_host`        |

1. The parameter `sylius.paypal.logging_level_increased` has been deprecated and will be removed in PayPalPlugin 2.0, because it is not used.

1. The following constructor signatures have been changed:

   `Sylius\PayPalPlugin\Controller\ProcessPayPalOrderAction`
      ```diff
      public function __construct(
      -      private OrderRepositoryInterface $orderRepository,
      +      private ?OrderRepositoryInterface $orderRepository,
             private CustomerRepositoryInterface $customerRepository,
             private FactoryInterface $customerFactory,
             private AddressFactoryInterface $addressFactory,
             private ObjectManager $orderManager,
             private StateMachineFactoryInterface|StateMachineInterface $stateMachineFactory,
             private PaymentStateManagerInterface $paymentStateManager,
             private CacheAuthorizeClientApiInterface $authorizeClientApi,
             private OrderDetailsApiInterface $orderDetailsApi,
             private OrderProviderInterface $orderProvider,
      )
      ```

   `Sylius\PayPalPlugin\Controller\UpdatePayPalOrderAction`
      ```diff
      public function __construct(
             PaymentProviderInterface $paymentProvider,
             CacheAuthorizeClientApiInterface $authorizeClientApi,
      -      OrderDetailsApiInterface $orderDetailsApi,
      +      ?OrderDetailsApiInterface $orderDetailsApi,
             UpdateOrderApiInterface $updateOrderApi,
             AddressFactoryInterface $addressFactory,
             OrderProcessorInterface $orderProcessor,
      )
      ```

   `Sylius\PayPalPlugin\Payum\Action\CompleteOrderAction`
      ```diff
      public function __construct(
             CacheAuthorizeClientApiInterface $authorizeClientApi,
             UpdateOrderApiInterface $updateOrderApi,
             CompleteOrderApiInterface $completeOrderApi,
             OrderDetailsApiInterface $orderDetailsApi,
             PayPalAddressProcessorInterface $payPalAddressProcessor,
             PaymentUpdaterInterface $payPalPaymentUpdater,
             StateResolverInterface $orderPaymentStateResolver,
      -      PayPalItemDataProviderInterface $payPalItemsDataProvider,
      +      ?PayPalItemDataProviderInterface $payPalItemsDataProvider,
      )
      ```

### UPGRADE FROM 1.5.1 to 1.6.0

1. Support for Sylius 1.13 has been added, it is now the recommended Sylius version to use.

1. Support for PHP 8.0 has been dropped.

1. The following constructor signatures have been changed:

    `Sylius\PayPalPlugin\Client\PayPalClient`:
     ```diff
     use Psr\Http\Client\ClientInterface;
     use GuzzleHttp\ClientInterface as GuzzleClientInterface;
     use Psr\Http\Message\RequestFactoryInterface;
     use Psr\Http\Message\StreamFactoryInterface;
    
        public function __construct(
    -      private readonly GuzzleClientInterface $client, 
    +      private readonly GuzzleClientInterface|ClientInterface $client,
            private readonly LoggerInterface $logger,
            private readonly UuidProviderInterface $uuidProvider,
            private readonly PayPalConfigurationProviderInterface $payPalConfigurationProvider,
            private readonly ChannelContextInterface $channelContext,
            private readonly string $baseUrl,
            private int $requestTrialsLimit,
            private readonly bool $loggingLevelIncreased = false,
    +      private readonly ?RequestFactoryInterface $requestFactory = null,
    +      private readonly ?StreamFactoryInterface $streamFactory = null,
        )
     ```

   `Sylius\PayPalPlugin\Api\GeneralApi`:
     ```diff
     use Psr\Http\Client\ClientInterface;
     use GuzzleHttp\ClientInterface as GuzzleClientInterface;
     use Psr\Http\Message\RequestFactoryInterface;
   
        public function __construct(
   -      private readonly GuzzleClientInterface $client,
   +      private readonly GuzzleClientInterface|ClientInterface $client,
   +      private readonly ?RequestFactoryInterface $requestFactory = null,
    )
     ```

   `Sylius\PayPalPlugin\Api\WebhookApi`:
     ```diff
     use Psr\Http\Client\ClientInterface;
     use GuzzleHttp\ClientInterface as GuzzleClientInterface;
     use Psr\Http\Message\RequestFactoryInterface;
     use Psr\Http\Message\StreamFactoryInterface;
   
        public function __construct(
   -       private readonly GuzzleClientInterface $client,
   +       private readonly GuzzleClientInterface|ClientInterface $client,
             private readonly string $baseUrl,
   +       private readonly ?RequestFactoryInterface $requestFactory = null,
   +       private readonly ?StreamFactoryInterface $streamFactory = null,
    )
     ```

   `Sylius\PayPalPlugin\Onboarding\Processor\BasicOnboardingProcessor`:
     ```diff
      use Psr\Http\Client\ClientInterface;
      use GuzzleHttp\ClientInterface as GuzzleClientInterface;
      use Psr\Http\Message\RequestFactoryInterface;
   
        public function __construct(
   -      private readonly GuzzleClientInterface $client,
   +      private readonly GuzzleClientInterface|ClientInterface $client,
            private readonly SellerWebhookRegistrarInterface $sellerWebhookRegistrar,
            private readonly string $url,
   +      private readonly ?RequestFactoryInterface $requestFactory = null,
    )
     ```
   
1. Added doctrine migration for PostgreSQL. For more information, please refer to the [Sylius 1.13 UPGRADE.md](https://github.com/Sylius/Sylius/blob/1.13/UPGRADE-1.13.md)

### UPGRADE FROM 1.3.0 to 1.3.1

1. `sylius_paypal_plugin_pay_with_paypal_form` route now operates on both payment ID and order token. URl then changed from
   `/pay-with-paypal/{id}` to `/pay-with-paypal/{orderToken}/{paymentId}`. If you use this route anywhere in your application, you
   need to change the URL attributes

### UPGRADE FROM 1.2.3 to 1.2.4

1. `sylius_paypal_plugin_pay_with_paypal_form` route now operates on both payment ID and order token. URl then changed from
    `/pay-with-paypal/{id}` to `/pay-with-paypal/{orderToken}/{paymentId}`. If you use this route anywhere in your application, you
    need to change the URL attributes

### UPGRADE FROM 1.0.X TO 1.1.0

1. Upgrade your application to [Sylius 1.8](https://github.com/Sylius/Sylius/blob/master/UPGRADE-1.8.md).

1. Remove previously copied migration files (You may check migrations to remove [here](https://github.com/Sylius/PayPalPlugin/pull/160/files)).
