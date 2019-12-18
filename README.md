# Who Bought This Item Also Bought GraphQl

## How to install
Run the following command in Magento 2 root folder:

```
composer require mageplaza/module-who-bought-this-item-also-bought-graphql
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```

## How to use

- To perform GraphQL queries in Magento, you need to do the following requirements:
  - Use Magento 2.3.x. Return your site to developer mode
  - Install the [ChromeiQL](https://chrome.google.com/webstore/detail/chromeiql/fkkiamalmpiidkljmicmjfbieiclmeij) extension for Chrome browser (currently does not support other browsers)
  - Set GraphQL endpoint as `http://<magento2-3-server>/graphql` in url box, click **Set endpoint**. (e.g. http://develop.mageplaza.com/graphql/ce232/graphql)
  - Perform a query in the left cell then click the **Run** button or **Ctrl + Enter** to see the result in the right cell
  - To see the supported queries for **Who bought this item also bought GraphQL** of Mageplaza, you can look in `Docs > Query > mpalsobought` in the right corner

![](https://i.imgur.com/csZlYRT.png)
