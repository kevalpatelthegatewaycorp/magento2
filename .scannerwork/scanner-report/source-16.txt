<?php

namespace GT\StoreLocator\Model\ResourceModel\Postcode;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection 
{
    protected function _construct()
    {
        $this->_init('GT\StoreLocator\Model\Postcode', 'GT\StoreLocator\Model\ResourceModel\Postcode');
    }
}