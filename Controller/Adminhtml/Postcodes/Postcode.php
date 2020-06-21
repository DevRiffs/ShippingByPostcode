<?php


namespace DevRiffs\ShippingByPostcode\Controller\Adminhtml\Postcodes;

abstract class Postcode extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Postcode constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * @param $resultPage
     * @return mixed
     */
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu('DevRiffs_ShippingByPostcode::internal')
            ->addBreadcrumb(__('Shipping by postcode'), __('Shipping by postcode'))
            ->addBreadcrumb(__('Postocdes'), __('Postcodes'));
        return $resultPage;
    }
}
