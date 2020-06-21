<?php

namespace DevRiffs\ShippingByPostcode\Model\Source;

class Depth implements \Magento\Framework\Option\ArrayInterface
{

    public function toOptionArray()
    {
        return [
            ['value' => '0', 'label' => __('Exact match')],
            ['value' => '4', 'label' => __('First 4 symbols')],
            ['value' => '3', 'label' => __('First 3 symbols')],
            ['value' => '2', 'label' => __('First 2 symbols')]
        ];
    }
}