## Installation

1. Run

    ```bash
    composer require sylius/paypal-plugin
    ```

1. Import routes

    ```yaml
    # config/routes/sylius_shop.yaml

    sylius_paypal_shop:
        resource: "@SyliusPayPalPlugin/config/shop_routing.yaml"
        prefix: /{_locale}
        requirements:
            _locale: ^[A-Za-z]{2,4}(_([A-Za-z]{4}|[0-9]{3}))?(_([A-Za-z]{2}|[0-9]{3}))?$

    # config/routes/sylius_admin.yaml

    sylius_paypal_admin:
        resource: "@SyliusPayPalPlugin/config/admin_routing.yml"
        prefix: '/%sylius_admin.path_name%'

    # config/routes.yaml

    sylius_paypal_webhook:
        resource: "@SyliusPayPalPlugin/config/webhook_routing.yaml"
    ```

1. Import configuration

   ```yaml
   # config/packages/_sylius.yaml

   imports:
       # ...
       - { resource: "@SyliusPayPalPlugin/config/config.yaml" }
   ```

1. Add `FOS\RestBundle` class to your `config/bundles.php` file

    ```php
    return [
        // ...
        FOS\RestBundle\FOSRestBundle::class => ['all' => true],
    ];
    ```

1. Add `FOS\RestBundle` configuration to your `config/packages/fos_rest.yaml` file

    ```yaml
     fos_rest:
        exception: true
        view:
            formats:
                json: true
                xml:  true
            empty_content: 204
        format_listener:
            rules:
                - { path: '^/api/.*', priorities: ['json', 'xml'], fallback_format: json, prefer_extension: true }
                - { path: '^/', stop: true }
   ```

1. Apply migrations

   ```
   bin/console doctrine:migrations:migrate -n
   ```

#### BEWARE!

To make PayPal integration working, your local Sylius URL should be accessible for the PayPal servers. Therefore you can
add the proper directive to your `/etc/hosts` (something like `127.0.0.1 sylius.local`) or use a service as [ngrok](https://ngrok.com/).

---

Next: [PayPal environment](sandbox-vs-live.md)
