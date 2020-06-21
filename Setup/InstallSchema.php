<?php

namespace DevRiffs\ShippingByPostcode\Setup;

use DevRiffs\ShippingByPostcode\Model\PostcodeInterface;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{

    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('shippingbypostcode')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('shippingbypostcode')
            )
                ->addColumn(
                    PostcodeInterface::POSTCODE_ID_FIELD,
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary'  => true,
                        'unsigned' => true,
                    ],
                    'Postcode ID'
                )
                ->addColumn(
                    PostcodeInterface::POSTCODE_POSTCODE_FIELD,
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    10,
                    ['nullable => false'],
                    'Postcode'
                )
                ->addColumn(
                    PostcodeInterface::POSTCODE_ALLOWED_CARRIERS,
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable => false'],
                    'Allowed carries'
                )
                ->setComment('Postcodes Table');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
