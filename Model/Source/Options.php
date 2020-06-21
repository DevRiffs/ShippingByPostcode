<?php

namespace DevRiffs\ShippingByPostcode\Model\Source;

use DevRiffs\ShippingByPostcode\Model\PostcodeInterface;
use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    /**
     * @var \Magento\Shipping\Model\Config
     */
    protected $shippingConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        \Magento\Shipping\Model\Config $shippingConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->shippingConfig = $shippingConfig;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return array|array[]
     */
    public function toOptionArray()
    {
        $allCarriers = $this->shippingConfig->getAllCarriers($this->storeManager->getStore());

        foreach ($allCarriers as $shippingCode => $shippingModel) {
            $shippingTitle = $this->scopeConfig->getValue('carriers/'.$shippingCode.'/title');
            $options[] = [
                'label' => $shippingTitle,
                'value' => $shippingCode
            ];
        }
        return $options;
    }
}