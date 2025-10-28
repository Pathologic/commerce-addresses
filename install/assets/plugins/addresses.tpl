//<?php
/**
 * Commerce Addresses
 *
 * Allows to select delivery address from list
 *
 * @category    plugin
 * @version     1.0.0
 * @author      Pathologic
 * @internal    @events OnBeforeOrderSaving,OnInitializeCommerce,OnInitializeOrderForm,OnWebPageInit,OnPluginFormSave
 * @internal    @properties &deliveryMethods=Delivery methods with addresses;text; &addressTpl=Address template;text;
 * @internal    @modx_category Commerce
 */

return require MODX_BASE_PATH . 'assets/plugins/addresses/plugin.addresses.php';
