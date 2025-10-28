<?php

namespace Pathologic\Commerce\Addresses;

class Manager
{
    protected $params = [];

    public function __construct($params)
    {
        $params['deliveryMethods'] = array_map('trim', explode(',', $params['deliveryMethods'] ?? ''));
        $params['addressTpl'] = $params['addressTpl'] ?? '';
        $this->params = $params;
    }

    public function list($uid)
    {
        return \DB::table('commerce_addresses')->where('user_id', (int) $uid)->orderBy('id',
            'desc')->get()->pluck('address', 'id')->toArray();
    }

    public function get($id)
    {
        return \DB::table('commerce_addresses')->where('id', (int) $id)->first();
    }

    public function create($uid, $address)
    {
        return \DB::table('commerce_addresses')->insertGetId(['user_id' => (int) $uid, 'address' => $address]);
    }

    public function update($id, $address)
    {
        return \DB::table('commerce_addresses')->where('id', (int) $id)->update(['address' => $address]);
    }

    public function delete($id)
    {
        return \DB::table('commerce_addresses')->where('id', (int) $id)->delete();
    }

    public function getDeliveryMethods()
    {
        return $this->params['deliveryMethods'];
    }

    public function getAddressTpl()
    {
        return $this->params['addressTpl'];
    }
}
