<?php

namespace Pathologic\Commerce\Addresses;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Plugin
{
    public function OnInitializeCommerce($params)
    {
        ci()->set('addresses', function ($ci) use ($params) {
            return new Manager($params);
        });
    }

    public function OnWebPageInit($params)
    {
        $uid = evo()->getLoginUserID('web');
        $addresses = json_encode(array_keys(ci()->addresses->list($uid)));
        evo()->regClientScript("<script>const deliveryAddresses = {$addresses};</script>", true);
    }

    public function OnPluginFormSave($params)
    {
        if (!Schema::hasTable('commerce_addresses')) {
            Schema::create('commerce_addresses', function (Blueprint $table) {
                $table->id();
                $table->text('address');
                $table->unsignedInteger('user_id');
            });
        }
        \DB::table('site_plugin_events')->where('pluginid', (int) $params['id'])->where(
            'evtid', 98)->delete();
    }

    public function OnInitializeOrderForm($params)
    {
        $prepare = $params['config']['prepare'] ?? '';
        $prepare .= ',Pathologic\Commerce\Addresses\Order::prepare';
        $params['config']['prepare'] = trim($prepare, ',');
        $params['config']['rules']['address_id'] = [
            'custom' => [
                'function' => 'Pathologic\Commerce\Addresses\Order::validateAddressId',
                'message'  => ''
            ]
        ];
    }

    public function OnBeforeOrderSaving($params)
    {
        $uid = (int) evo()->getLoginUserID('web');
        if ($params['order_id'] || !$uid) {
            return;
        }

        $addressDeliveries = ci()->addresses->getDeliveryMethods();
        $delivery = $params['values']['fields']['delivery_method'] ?? '';
        if (empty($addressDeliveries) || !in_array($delivery, $addressDeliveries)) {
            return;
        }

        if ($params['fields']['address_id']) {
            $address = ci()->addresses->get($params['values']['fields']['address_id'])->address;
        } else {
            $addressTpl = ci()->addresses->getAddressTpl();
            $address = \DLTemplate::getInstance(evo())->parseChunk($addressTpl, $params['values']['fields']);
            ci()->addresses->create($uid, $address);
        }
        $params['values']['fields']['address'] = $address;
    }

    public function OnUserDelete($params)
    {
        $id = (int) $params['userid'];
        \DB::table('commerce_addresses')->where('user_id', $id)->delete();
    }
}
