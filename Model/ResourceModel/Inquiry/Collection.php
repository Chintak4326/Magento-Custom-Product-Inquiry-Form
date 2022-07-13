<?php

namespace ChintakExtensions\ProductInquiry\Model\ResourceModel\Inquiry;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init('ChintakExtensions\ProductInquiry\Model\Inquiry', 'ChintakExtensions\ProductInquiry\Model\ResourceModel\Inquiry');
    }
}