## Installation

1. Run

    ```bash
    composer require sylius/paypal-plugin
    ```

2. Import routes

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

3. Import configuration

   ```yaml
   # config/packages/_sylius.yaml

   imports:
       # ...
       - { resource: "@SyliusPayPalPlugin/config/config.yaml" }
   ```

4. Apply migrations

   ```
   bin/console doctrine:migrations:migrate -n
   ```

#### BEWARE!

To make PayPal integration working, your local Sylius URL should be accessible for the PayPal servers. Therefore you can
add the proper directive to your `/etc/hosts` (something like `127.0.0.1 sylius.local`) or use a service as [ngrok](https://ngrok.com/).

---

Next: [PayPal environment](sandbox-vs-live.md)
