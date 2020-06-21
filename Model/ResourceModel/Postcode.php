<?php
namespace DevRiffs\ShippingByPostcode\Model\ResourceModel;

use DevRiffs\ShippingByPostcode\Model\PostcodeInterface;

class Postcode extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Postcode constructor.
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param null $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $connectionName = null)
    {
        parent::__construct($context, $connectionName);
    }

    protected function _construct()
    {
        $this->_init(PostcodeInterface::POSTCODE_TABLE, PostcodeInterface::POSTCODE_ID_FIELD);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param mixed $value
     * @param null $field
     * @return Postcode
     */
    public function load(\Magento\Framework\Model\AbstractModel $object, $value, $field = null)
    {
        return parent::load($object, $value, $field);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return Postcode
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function save(\Magento\Framework\Model\AbstractModel $object)
    {
        return parent::save($object);
    }

}
