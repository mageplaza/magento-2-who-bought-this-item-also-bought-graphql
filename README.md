# Magento 2 Who Bought This Item Also Bought GraphQL

**Magento Who Bought This Also Bought GraphQL is a part of Mageplaza Who Bought This Also This extension; this supports for PWA Studio.** 

[Mageplaza Who Bought This Also Bought for Magento 2](https://www.mageplaza.com/magento-2-who-bought-this-also-bought/) is an effective solution to increase sales for an online store by showcasing and encouraging customers to buy related products. 

Based on the customers’ purchase history, the extension will automatically select the related products and display them on the page your customers are viewing. So the purchase history record plays a vital role in this method. It decides how frequent and diverse the suggested products are showcased to customers. 

It’s easy to configure at the backend to make your products appear as attractive options besides the products customers have purchased. You can display suggested products at the top or bottom of many different places, such as product pages, category pages, and shopping carts. 

Also, at the backend, you can select the layout to organize the related products in a slider or multiple lines. The related products will be displayed along with essential details, such as price, ratings, and “Add to cart” button. It makes the related products block display in sync more with the current page's content and easy to catch customers’ eyes. 

## 1. How to install
Run the following command in Magento 2 root folder:

```
composer require mageplaza/module-who-bought-this-item-also-bought-graphql
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```
**Note:** Magento 2 Who Bought This Also Bought GraphQL requires installing Mageplaza [Who Bought This Also Bought](https://www.mageplaza.com/magento-2-who-bought-this-also-bought/) in your Magento installation. 

## 2. How to use

- To perform GraphQL queries in Magento, you need to do the following requirements:
  - Use Magento 2.3.x. Return your site to developer mode
  - Install the [ChromeiQL](https://chrome.google.com/webstore/detail/chromeiql/fkkiamalmpiidkljmicmjfbieiclmeij) extension for Chrome browser (currently does not support other browsers)
  - Set GraphQL endpoint as `http://<magento2-3-server>/graphql` in url box, click **Set endpoint**. (e.g. http://develop.mageplaza.com/graphql/ce232/graphql)
  - Perform a query in the left cell then click the **Run** button or **Ctrl + Enter** to see the result in the right cell
  - To see the supported queries for **Who bought this item also bought GraphQL** of Mageplaza, you can look in `Docs > Query > mpalsobought` in the right corner

![](https://i.imgur.com/csZlYRT.png)

## 3. Devdocs

- [Magento 2 Who Bought This Also Bought API & examples](https://documenter.getpostman.com/view/10589000/SzRxXrBi?version=latest)
- [Magento 2 Who Bought This Also Bought GraphQL & examples](https://documenter.getpostman.com/view/10589000/SzRxXrBo?version=latest)

Click on Run in Postman to add these collections to your workspace quickly. 

![Magento 2 blog graphql pwa](https://i.imgur.com/lhsXlUR.gif)

## 4. Contribute to this module 

Feel free to **Fork** and contribute to this module. 
You can create a pull request, so we will merge your changes in the main branch. 

## 5. Get Support
- Don't hesitate to [contact us](https://www.mageplaza.com/contact.html) if you have any further questions. 
- If you like this project, please give us a **Star** ![star](https://i.imgur.com/S8e0ctO.png)
