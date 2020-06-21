<?php
namespace DevRiffs\ShippingByPostcode\Model\ResourceModel\Postcode;

use DevRiffs\ShippingByPostcode\Model\Postcode;
use DevRiffs\ShippingByPostcode\Model\ResourceModel\Postcode as ResourceModel;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Postcode::class, ResourceModel::class);
    }

}

