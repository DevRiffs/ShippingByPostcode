<?php

namespace DevRiffs\ShippingByPostcode\Ui\Component\Listing\Column;

use DevRiffs\ShippingByPostcode\Model\PostcodeInterface;

class PostcodeActions extends \Magento\Ui\Component\Listing\Columns\Column
{

    const URL_PATH_DELETE = 'shippingbypostcode/postcodes/remove';
    const URL_PATH_EDIT = 'shippingbypostcode/postcodes/edit';

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item[PostcodeInterface::POSTCODE_ID_FIELD])) {
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_EDIT,
                                [
                                    PostcodeInterface::POSTCODE_ID_FIELD => $item[PostcodeInterface::POSTCODE_ID_FIELD]
                                ]
                            ),
                            'label' => __('Edit')
                        ],
                        'remove' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_DELETE,
                                [
                                    PostcodeInterface::POSTCODE_ID_FIELD => $item[PostcodeInterface::POSTCODE_ID_FIELD]
                                ]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete'),
                                'message' => __('Are you sure you wan\'t to delete postcode?')
                            ]
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
