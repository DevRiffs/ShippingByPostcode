<?php

namespace DevRiffs\ShippingByPostcode\Plugin;

use Magento\Sales\Model\Order\Shipment;
use Magento\Store\Model\ScopeInterface;

class AroundCollectRates
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \DevRiffs\ShippingByPostcode\Model\PostcodeManager
     */
    protected $postcodeManager;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \DevRiffs\ShippingByPostcode\Model\PostcodeManager $postcodeManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->postcodeManager = $postcodeManager;
    }

    public function aroundCollectRates(
        \Magento\Shipping\Model\Shipping $subject,
        callable $proceed,
        \Magento\Quote\Model\Quote\Address\RateRequest $request
    ) {
        if ($this->scopeConfig->getValue('shippingbypostcode/general/enabled', ScopeInterface::SCOPE_STORE)) {
            $storeId = $request->getStoreId();
            if (!$request->getOrig()) {
                $request->setCountryId(
                    $this->scopeConfig->getValue(
                        Shipment::XML_PATH_STORE_COUNTRY_ID,
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                        $request->getStore()
                    )
                )->setRegionId(
                    $this->scopeConfig->getValue(
                        Shipment::XML_PATH_STORE_REGION_ID,
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                        $request->getStore()
                    )
                )->setCity(
                    $this->scopeConfig->getValue(
                        Shipment::XML_PATH_STORE_CITY,
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                        $request->getStore()
                    )
                )->setPostcode(
                    $this->scopeConfig->getValue(
                        Shipment::XML_PATH_STORE_ZIP,
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                        $request->getStore()
                    )
                );
            }

            $limitCarrier = $request->getLimitCarrier();
            if (!$limitCarrier) {
                $carriers = $this->scopeConfig->getValue(
                    'carriers',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $storeId
                );

                foreach ($carriers as $carrierCode => $carrierConfig) {
                    $postcodes = $this->postcodeManager->shippingIsAllowed($request->getDestPostcode(), $carrierCode);
                    if ($postcodes) {
                        $subject->collectCarrierRates($carrierCode, $request);
                    }
                }
            } else {
                if (!is_array($limitCarrier)) {
                    $limitCarrier = [$limitCarrier];
                }
                foreach ($limitCarrier as $carrierCode) {
                    $carrierConfig = $this->scopeConfig->getValue(
                        'carriers/'.$carrierCode,
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                        $storeId
                    );
                    if (!$carrierConfig) {
                        continue;
                    }
                    $postcodes = $this->postcodeManager->shippingIsAllowed($request->getDestPostcode(), $carrierCode);
                    if ($postcodes) {
                        $subject->collectCarrierRates($carrierCode, $request);
                    }
                }
            }

            return $subject;
        }
        return $proceed($request);
    }

}