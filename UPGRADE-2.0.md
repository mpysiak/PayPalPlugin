# UPGRADE FROM 1.x to 2.0

1. Support for Sylius 2.0 has been added, it is now the recommended Sylius version to use with PayPalPlugin.

1. Support for Sylius 1.X has been dropped, upgrade your application to [Sylius 2.0](https://github.com/Sylius/Sylius/blob/2.0/UPGRADE-2.0.md).

1. The minimum supported version of PHP has been increased to 8.2.

1. The following classes have been removed:
   - `Sylius\PayPalPlugin\Controller\PayPalOrderItemController`
   - `Sylius\PayPalPlugin\Form\Type\ChangePaymentMethodType`
   - `Sylius\PayPalPlugin\Form\Type\SelectPaymentType`

1. The route `sylius_paypal_plugin_create_paypal_order_from_product` has been removed and replaced with the `AddToCartFormComponent`.

1. The following constructor signatures have been changed:

   `Sylius\PayPalPlugin\Client\PayPalClient`
   ```diff
     public function __construct(
   -     private readonly GuzzleClientInterface|ClientInterface $client,
   +     private readonly ClientInterface $client,
         private readonly LoggerInterface $logger,
         private readonly UuidProviderInterface $uuidProvider,
         private readonly PayPalConfigurationProviderInterface $payPalConfigurationProvider,
         private readonly ChannelContextInterface $channelContext,
         private readonly string $baseUrl,
         private int $requestTrialsLimit,
   -     private readonly bool $loggingLevelIncreased = false,
   -     private readonly ?RequestFactoryInterface $requestFactory = null,
   -     private readonly ?StreamFactoryInterface $streamFactory = null,
   +     private readonly RequestFactoryInterface $requestFactory,
   +     private readonly StreamFactoryInterface $streamFactory,
   +     private readonly bool $loggingLevelIncreased = false,
     )
   ```

   `Sylius\PayPalPlugin\Api\GenericApi`
   ```diff
     public function __construct(
   -      private readonly GuzzleClientInterface|ClientInterface $client,
   +      private readonly ClientInterface $client,
   -      private readonly ?RequestFactoryInterface $requestFactory = null,
   +      private readonly RequestFactoryInterface $requestFactory,
   )
   ```

   `Sylius\PayPalPlugin\Api\WebhookApi`
   ```diff
   public function __construct(
   -      private readonly GuzzleClientInterface|ClientInterface $client,
   +      private readonly ClientInterface $client,
          private readonly string $baseUrl,
   -      private readonly ?RequestFactoryInterface $requestFactory = null,
   -      private readonly ?StreamFactoryInterface $streamFactory = null,
   +      private readonly RequestFactoryInterface $requestFactory,
   +      private readonly StreamFactoryInterface $streamFactory,
   )
   ```

   `Sylius\PayPalPlugin\Onboarding\Processor\BasicOnboardingProcessor`
   ```diff
   public function __construct(
   -      private readonly GuzzleClientInterface|ClientInterface $client,
   +      private readonly ClientInterface $client,
          private readonly SellerWebhookRegistrarInterface $sellerWebhookRegistrar,
          private readonly string $url,
   -      private readonly ?RequestFactoryInterface $requestFactory = null,
   +      private readonly RequestFactoryInterface $requestFactory,
   )
   ```

   `Sylius\PayPalPlugin\Controller\ProcessPayPalOrderAction`
   ```diff
   public function __construct(
   -      private ?OrderRepositoryInterface $orderRepository,
          private CustomerRepositoryInterface $customerRepository,
          private FactoryInterface $customerFactory,
          private AddressFactoryInterface $addressFactory,
          private ObjectManager $orderManager,
   -      private StateMachineFactoryInterface|StateMachineInterface $stateMachineFactory,
   +      private StateMachineInterface $stateMachineFactory,
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
   -      ?OrderDetailsApiInterface $orderDetailsApi,
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
      -      ?PayPalItemDataProviderInterface $payPalItemsDataProvider,
      )
      ```
