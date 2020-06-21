<?php
namespace DevRiffs\ShippingByPostcode\Block\Adminhtml\Edit;

use DevRiffs\ShippingByPostcode\Model\PostcodeInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData(): array
    {
        if (!$this->getRequest()->getParam(PostcodeInterface::POSTCODE_ID_FIELD)) {
            return [];
        }
        
        return [
            'label' => __('Delete postcode'),
            'class' => 'delete',
            'on_click' => 'deleteConfirm(\''
                . __('Are you sure you want to delete this postcode ?')
                . '\', \'' . $this->getDeleteUrl() . '\')',
            'sort_order' => 20,
        ];
    }

    /**
     * @return string
     */
    public function getDeleteUrl(): string
    {
        $id = $this->getRequest()->getParam(PostcodeInterface::POSTCODE_ID_FIELD);
        return $this->getUrl('*/*/remove', [PostcodeInterface::POSTCODE_ID_FIELD => $id]);
    }
}
