<?php

namespace Mageplaza\HelloWorld\Model;

class Postcode extends \Magento\Framework\Model\AbstractModel
{
    public function _construct()
    {
        $this->_init('Mageplaza\HelloWorld\Model\ResourceModel\Postcode');
    }
}