<?php

namespace DevRiffs\ShippingByPostcode\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            'label' => __('Save postcode'),
            'class' => 'save primary',
            'sort_order' => 90,
        ];
    }
}
