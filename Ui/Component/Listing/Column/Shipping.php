<?php

namespace DevRiffs\ShippingByPostcode\Ui\Component\Listing\Column;

use DevRiffs\ShippingByPostcode\Model\PostcodeInterface;

class Shipping extends \Magento\Ui\Component\Listing\Columns\Column
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


    /**
     * Shipping constructor.
     * @param  \Magento\Framework\View\Element\UiComponent\ContextInterface  $context
     * @param  \Magento\Framework\View\Element\UiComponentFactory  $uiComponentFactory
     * @param  \Magento\Shipping\Model\Config  $shippingConfig
     * @param  \Magento\Store\Model\StoreManagerInterface  $storeManager
     * @param  \Magento\Framework\App\Config\ScopeConfigInterface  $scopeConfig
     * @param  array  $components
     * @param  array  $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Shipping\Model\Config $shippingConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $components = [],
        array $data = []
    ) {
        $this->shippingConfig = $shippingConfig;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        $allCarriers = $this->shippingConfig->getAllCarriers($this->storeManager->getStore());
        $shippingMethodsArray = [];
        foreach ($allCarriers as $shippigCode => $shippingModel) {
            $shippingTitle = $this->scopeConfig->getValue('carriers/'.$shippigCode.'/title');
            $shippingMethodsArray[$shippigCode] = $shippingTitle;
        }
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $options = explode(',', $item[PostcodeInterface::POSTCODE_ALLOWED_CARRIERS]);
                $item['allowed_carriers'] = '';
                $last = end($options);
                foreach ($options as $option){
                    if(isset($shippingMethodsArray[$option])) {
                        $item['allowed_carriers'] .= $shippingMethodsArray[$option];
                        if($option !== $last) {
                            $item['allowed_carriers'] .= ", ";
                        }
                    }
                }

            }
        }
        return $dataSource;
    }

}