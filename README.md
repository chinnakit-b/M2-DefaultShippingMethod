# **Magento 2 - Default Shipping Method Module**

# Overview
This Magento 2 module allows store owners to set a default shipping method via the admin panel's system configuration. If a customer does not select a shipping method during checkout, the module will automatically apply the default shipping method configured in the admin.

# Features
* **Default Shipping Method**: Allows store administrators to select a default shipping method in the system configuration.
* **Dynamic Shipping Method List**: Shipping methods are dynamically populated from the available active shipping methods in the store.
* **Automatic Application**: The default shipping method is applied during checkout if no method is pre-selected by the customer.

# Installation
Clone or download the module and place it in the app/code/Dev/DefaultShippingMethod directory of your Magento 2 installation.

Run the following commands to install and enable the module:

bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush

# Configuration
* In the Magento admin panel, go to Stores -> Configuration -> Sales -> Shipping Method Config.
* In the Shipping Methods section, you will see a new option to select a default shipping method from a dropdown list of all active shipping methods.

# How It Works
* The module listens to the checkout_cart_save_before event, which is triggered when a customer proceeds through checkout.
* If no shipping method is selected, the module retrieves the default shipping method configured in the admin panel and applies it to the cart.
* The selected shipping method can be found in the Stores -> Configuration -> Sales -> Shipping Method Config.

# Files
1. etc/adminhtml/system.xml
   Defines the system configuration field in the admin panel, where the admin selects the default shipping method.

2. Model/Config/Source/ShippingMethods.php
   A source model that retrieves all active shipping methods and populates them in the system configuration dropdown.

3. Observer/SetDefaultShippingMethod.php
   This observer listens to the checkout_cart_save_before event and sets the default shipping method programmatically based on the configuration value.

# Customization
You can modify the following:

 - Default Shipping Method: By changing the value selected in the admin panel.
 - Observer Event: You can change the event in events.xml if you want to apply the default shipping method at a different point during checkout.