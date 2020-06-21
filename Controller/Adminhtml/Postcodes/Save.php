<?php


namespace DevRiffs\ShippingByPostcode\Controller\Adminhtml\Postcodes;

use _HumbugBox01d8f9a04075\Nette\Neon\Exception;
use DevRiffs\ShippingByPostcode\Model\PostcodeInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \DevRiffs\ShippingByPostcode\Model\PostcodeFactory
     */
    protected $postcodeFactory;

    /**
     * @param  \Magento\Backend\App\Action\Context  $context
     * @param  \Magento\Framework\App\Request\DataPersistorInterface  $dataPersistor
     * @param  \DevRiffs\ShippingByPostcode\Model\PostcodeFactory  $postcodeFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \DevRiffs\ShippingByPostcode\Model\PostcodeFactory $postcodeFactory
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->postcodeFactory = $postcodeFactory;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
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
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $postcode = $this->getRequest()->getParam(PostcodeInterface::POSTCODE_POSTCODE_FIELD);
            $allowedCarries = $this->getRequest()->getParam(PostcodeInterface::POSTCODE_ALLOWED_CARRIERS);
            $data = $this->preparePostcode($postcode, $data);
            if ($allowedCarries) {
                $data['allowed_carriers'] = implode(',', $allowedCarries);
            } else {
                throw new \Exception('Missing carriers');
            }

            $id = $this->getRequest()->getParam(PostcodeInterface::POSTCODE_ID_FIELD);

            $model = $this->postcodeFactory->create()->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This report no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            $model->setData($data);
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the postcode.'));
                $this->dataPersistor->clear('devriffs_shippingbypostcode_postcode');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['fileicon_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the postcode.'));
            }

            $this->dataPersistor->set('devriffs_shippingbypostcode_postcode', $data);
            return $resultRedirect->setPath(
                '*/*/edit',
                [
                    PostcodeInterface::POSTCODE_ID_FIELD => $this->getRequest()->getParam(
                        PostcodeInterface::POSTCODE_ID_FIELD
                    )
                ]
            );
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param $postcode
     * @param $data
     * @return mixed
     */
    protected function preparePostcode($postcode, $data)
    {
        if ($postcode) {
            $postcode = preg_replace('/\s+/', '', $postcode);
            $data[PostcodeInterface::POSTCODE_POSTCODE_FIELD] = strtoupper($postcode);
        } else {
            $this->messageManager->addErrorMessage(__('Postcode is missing'));
        }
        return $data;
    }
}