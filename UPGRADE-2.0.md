# UPGRADE FROM 1.x to 2.0

1. Support for Sylius 2.0 has been added, it is now the recommended Sylius version to use with PayPalPlugin.

1. Support for Sylius 1.X has been dropped, upgrade your application to [Sylius 2.0](https://github.com/Sylius/Sylius/blob/2.0/UPGRADE-2.0.md).

1. The minimum supported version of PHP has been increased to 8.2.

1. The following classes have been removed:
   - `Sylius\PayPalPlugin\Controller\PayPalOrderItemController`
   - `Sylius\PayPalPlugin\Form\Type\ChangePaymentMethodType`
   - `Sylius\PayPalPlugin\Form\Type\SelectPaymentType`

1. The route `sylius_paypal_plugin_create_paypal_order_from_product` has been removed and replaced with the `AddToCartFormComponent`.

1. The directories structure has been updated to the current Symfony recommendations:
   - `@SyliusPayPalPlugin/Resources/config` -> `@SyliusPayPalPlugin/config`
   - `@SyliusPayPalPlugin/Resources/translations` -> `@SyliusPayPalPlugin/translations`
   - `@SyliusPayPalPlugin/Resources/views` -> `@SyliusPayPalPlugin/templates`

   You need to adjust the import of configuration file in your end application:
   ```diff
   imports:
   -   - { resource: "@SyliusPayPalPlugin/Resources/config/config.yml" }
   +   - { resource: '@SyliusPayPalPlugin/config/config.yaml' }
   ```

   And the routes configuration paths:
   ```diff
   sylius_paypal_shop:
   -   resource: "@SyliusPayPalPlugin/Resources/config/shop_routing.yaml"
   +   resource: "@SyliusPayPalPlugin/config/shop_routing.yaml"
       prefix: /{_locale}
       requirements:
           _locale: ^[a-z]{2}(?:_[A-Z]{2})?$

   sylius_paypal_admin:
   -   resource: "@SyliusPayPalPlugin/Resources/config/admin_routing.yaml"
   -   prefix: /admin
   +   resource: "@SyliusPayPalPlugin/config/admin_routing.yaml"
   +   prefix: '/%sylius_admin.path_name%'

   sylius_paypal_webhook:
   -   resource: "@SyliusPayPalPlugin/Resources/config/webhook_routing.yaml"
   +   resource: "@SyliusPayPalPlugin/config/webhook_routing.yaml"
   ```

   And the paths to assets and templates if you are using them.

1. **No need to overwrite templates**:  
   Thanks to the use of Twig Hooks and the refactoring of templates, you no longer need to overwrite templates to use plugin features.

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
