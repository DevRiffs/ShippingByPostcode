<?php

namespace DevRiffs\ShippingByPostcode\Model;

use Magento\Store\Model\ScopeInterface;

class PostcodeManager
{

    /**
     * @var \DevRiffs\ShippingByPostcode\Model\PostcodeFactory
     */
    private $_postcodeFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\App\CacheInterface
     */
    protected $cache;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \DevRiffs\ShippingByPostcode\Model\PostcodeFactory $postcodeFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\CacheInterface $cache
    ) {
        $this->_postcodeFactory = $postcodeFactory;
        $this->scopeConfig = $scopeConfig;
        $this->cache = $cache;
    }

    /**
     * @param  string  $postcodeFromRequest
     * @param  string  $shippingMethod
     * @return bool
     */
    public function shippingIsAllowed($postcodeFromRequest, $shippingMethod)
    {
        $postcodeFromRequest = $this->preparePostcode($postcodeFromRequest);
        $cacheData = $this->cache->load('cached_postcode_'.$postcodeFromRequest.$shippingMethod);

        if (!$cacheData) {
            $matchDepth = $this->scopeConfig->getValue('shippingbypostcode/general/match_depth', ScopeInterface::SCOPE_STORE);
            if($matchDepth){
                $shortPostCodes[] = substr($postcodeFromRequest, 0, $matchDepth);
            } else {
                $shortPostCodes[] = $postcodeFromRequest;
            }

            $foundPostcode = $this->findPostcode($shortPostCodes);
            $shipOnlyTo = (bool)$this->scopeConfig->getValue('shippingbypostcode/general/ship_only_to', ScopeInterface::SCOPE_STORE);

            if ($shipOnlyTo) {
                if($foundPostcode) {
                    $result = $this->haveCarriers(
                        $foundPostcode->getData(PostcodeInterface::POSTCODE_ALLOWED_CARRIERS),
                        $shippingMethod,
                        $shipOnlyTo
                    );
                } else {
                    $result = false;
                }
            } else {
                if($foundPostcode) {
                    $result = $this->haveCarriers(
                        $foundPostcode->getData(PostcodeInterface::POSTCODE_ALLOWED_CARRIERS),
                        $shippingMethod,
                        $shipOnlyTo
                    );
                } else {
                    $result = true;
                }
            }
            $this->cache->save($result ? 'allow' : 'disallow', 'cached_postcode_'.$postcodeFromRequest.$shippingMethod);
            return $result;
        } else {
            return $cacheData == 'allow' ? true : false;
        }
    }


    /**
     * @param  string  $allowedCarriers
     * @param  string  $shippingMethod
     * @return bool
     */
    private function haveCarriers($allowedCarriers, $shippingMethod, $showOnlyTo)
    {
        $allowedCarriers = explode(',', $allowedCarriers);
        if (in_array($shippingMethod, $allowedCarriers)) {
            return $showOnlyTo ? true : false;
        }
        return $showOnlyTo ? false : true;
    }

    /**
     * @param  string  $postcodeFromRequest
     * @return string
     */
    protected function preparePostcode(string $postcodeFromRequest): string
    {
        $postcodeFromRequest = str_replace(' ', '', $postcodeFromRequest);
        $postcodeFromRequest = strtoupper($postcodeFromRequest);
        return $postcodeFromRequest;
    }

    /**
     * @param $shortPostcodes
     * @return mixed
     */
    protected function findPostcode($shortPostcodes)
    {
        foreach ($shortPostcodes as $shortPostcode) {
            $postcodes = $this->_postcodeFactory->create();
            $collection = $postcodes->getCollection();
            $collection->addFieldToFilter(PostcodeInterface::POSTCODE_POSTCODE_FIELD, ['like' => $shortPostcode.'%']);
            if ($collection->getSize() > 0) {
                foreach ($collection as $postcode) {
                    return $postcode;
                }
            }
        }
    }
}