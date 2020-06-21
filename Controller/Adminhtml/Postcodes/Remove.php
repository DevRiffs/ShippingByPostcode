<?php

namespace DevRiffs\ShippingByPostcode\Controller\Adminhtml\Postcodes;

use DevRiffs\ShippingByPostcode\Model\PostcodeInterface;

class Remove extends \DevRiffs\ShippingByPostcode\Controller\Adminhtml\Postcodes\Postcode
{

    /**
     * @var \DevRiffs\ShippingByPostcode\Model\PostcodeFactory
     */
    protected $postcodeFactory;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    protected $file;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;

    /**
     * Remove constructor.
     * @param  \Magento\Backend\App\Action\Context  $context
     * @param  \Magento\Framework\Registry  $coreRegistry
     * @param  \DevRiffs\ShippingByPostcode\Model\PostcodeFactory  $postcodeFactory
     * @param  \Magento\Framework\App\Filesystem\DirectoryList  $directoryList
     * @param  \Magento\Framework\Filesystem\Driver\File  $file
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \DevRiffs\ShippingByPostcode\Model\PostcodeFactory $postcodeFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem\Driver\File $file
    ) {
        $this->postcodeFactory = $postcodeFactory;
        $this->file = $file;
        $this->directoryList = $directoryList;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * @return bool
     */
    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('DevRiffs_ShippingByPostcode::create_postcode');
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam(PostcodeInterface::POSTCODE_ID_FIELD);
        if ($id) {
            try {
                $model = $this->postcodeFactory->create();
                $model = $model->load($id);
                $mediaRootDir = $this->directoryList->getPath('media'). '/shippingbypostcode/postcodes/';
                if ($this->file->isExists($mediaRootDir. $model->getReportAttachment()))  {

                    $this->file->deleteFile($mediaRootDir. $model->getReportAttachment());
                }
                $model->delete();
                $this->messageManager->addSuccessMessage(__('You deleted the postcode.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', [PostcodeInterface::POSTCODE_ID_FIELD => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a postcode to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
