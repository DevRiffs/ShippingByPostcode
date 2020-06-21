<?php

namespace DevRiffs\ShippingByPostcode\Controller\Adminhtml\Postcodes;

use DevRiffs\ShippingByPostcode\Model\PostcodeInterface;

class Edit extends \DevRiffs\ShippingByPostcode\Controller\Adminhtml\Postcodes\Postcode
{

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \DevRiffs\ShippingByPostcode\Model\PostcodeFactory
     */
    protected $postcodeFactory;

    /**
     * Edit constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \DevRiffs\ShippingByPostcode\Model\PostcodeFactory $postcodeFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \DevRiffs\ShippingByPostcode\Model\PostcodeFactory $postcodeFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->postcodeFactory = $postcodeFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DevRiffs_ShippingByPostcode::create_postcode');
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam(PostcodeInterface::POSTCODE_ID_FIELD);
        $model = $this->postcodeFactory->create();

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This postcode no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('devriffs_shippingbypostcode_postcode', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit postcode') : __('New postcode'),
            $id ? __('Edit postcode') : __('New postcode')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Postcodes'));
        $resultPage->getConfig()->getTitle()->prepend( __('New postcode'));
        return $resultPage;
    }
}
