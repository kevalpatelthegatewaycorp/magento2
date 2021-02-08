<?php

namespace GT\StoreLocator\Model;

class Postcode extends \Magento\Framework\Model\AbstractModel
{
    public function _construct()
    {
        $this->_init('GT\StoreLocator\Model\ResourceModel\Postcode');
    }
}