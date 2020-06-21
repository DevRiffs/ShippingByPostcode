<?php

namespace DevRiffs\ShippingByPostcode\Model;

use DevRiffs\ShippingByPostcode\Model\ResourceModel\Postcode as ResourceModel;

class Postcode extends \Magento\Framework\Model\AbstractModel implements PostcodeInterface
{

    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    public function getAllowedCarriers() {
        return 'freeshipping';
    }

}
