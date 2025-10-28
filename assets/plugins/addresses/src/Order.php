<?php

namespace Pathologic\Commerce\Addresses;

class Order
{
    public static function prepare($modx, $data, $FormLister, $name)
    {
        $addressDeliveries = ci()->addresses->getDeliveryMethods();
        $delivery = $data['delivery_method'] ?? '';

        $uid = evo()->getLoginUserID('web');
        $addressId = $data['address_id'] ?? 0;
        $addressRules = $FormLister->config->loadArray($FormLister->getCFGDef('addressRules', []), '');
        if (!$addressId && in_array($delivery, $addressDeliveries)) {
            $FormLister->config->setConfig([
                'rules' => array_merge($FormLister->getValidationRules(), $addressRules)
            ]);
        }
        $addressesTpl = $FormLister->getCFGDef('addressesTpl', '');
        $addressTpl = $FormLister->getCFGDef('addressTpl', '');
        $addresses = ci()->addresses->list($uid);
        $out = '';
        if ($addresses) {
            if(!$FormLister->isSubmitted()) $addressId = key($addresses);
            foreach ($addresses as $id => $address) {
                $out .= $FormLister->parseChunk($addressTpl, [
                    'id' => $id,
                    'address' => e($address),
                    'checked' => $id == $addressId ? 'checked' : '',
                    'selected' => $id == $addressId ? 'selected' : ''
                ]);
            }
            $out = $FormLister->parseChunk($addressesTpl, [
                'wrap' => $out,
                'checked' => 0 == $addressId ? 'checked' : '',
                'selected' => 0 == $addressId ? 'selected' : '',
            ]);
        }
        $FormLister->setPlaceholder('addresses', $out);
    }

    public static function validateAddressId($FormLister, $value)
    {
        $uid = evo()->getLoginUserID('web');
        $value = (int) $value;
        $address = ci()->addresses->get($value);

        return !$uid || ($address && $address->user_id == $uid);
    }
}
