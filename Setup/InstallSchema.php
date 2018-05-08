<?php

namespace OuterEdge\CategoryAttributeFilter\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();
        
        //Leads table
        $table = $installer->getConnection()->newTable(
            $installer->getTable('catalog_category_filterable_attributes')
            )
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Entity ID'
            )    
            ->addColumn(
                'category_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Category FK'
            )
            ->addColumn(
                'eav_attribute_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Eav Attribute Fk'
            )
            ->addForeignKey(
                $setup->getFkName('catalog_category_filterable_attributes', 'category_id', 'catalog_category_entity', 'entity_id'),
                'category_id',
                $setup->getTable('catalog_category_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName('catalog_category_filterable_attributes', 'eav_attribute_id', 'eav_attribute', 'attribute_id'),
                'eav_attribute_id',
                $setup->getTable('eav_attribute'),
                'attribute_id',
                Table::ACTION_CASCADE
            )
            ->setComment('Filterable Attributes Table');
        
        $installer->getConnection()->createTable($table);

        $setup->endSetup();
    }
}
