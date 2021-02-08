<?php

namespace GT\StoreLocator\Model\ResourceModel;

class Postcode extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('postcodelatlng', 'id');
    }
}