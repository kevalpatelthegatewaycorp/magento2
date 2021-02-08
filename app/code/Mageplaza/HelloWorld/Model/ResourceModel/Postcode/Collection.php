<?php

namespace Mageplaza\HelloWorld\Model\ResourceModel\Postcode;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection 
{
    protected function _construct()
    {
        $this->_init('Mageplaza\HelloWorld\Model\Postcode', 'Mageplaza\HelloWorld\Model\ResourceModel\Postcode');
    }
}