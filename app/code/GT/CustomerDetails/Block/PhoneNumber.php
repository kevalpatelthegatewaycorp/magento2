<?php

namespace GT\CustomerDetails\Block;

class PhoneNumber extends \Magento\Framework\View\Element\Template
{
    protected $customer;

    protected $customerFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Customer\Model\Session $customer,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerFactory
    ) {
        $this->customer = $customer;
        $this->customerFactory = $customerFactory;
        parent::__construct($context);
    }


    public function getCustomerPhoneNumber()
    {
        $phoneNumber = '';
        $customer = $this->customer;
        $customerId = $customer->getId();
        if (!empty($customerId)) {
            $currentCustomer = $this->customerFactory->getById($customerId);
            $phoneNumber = $currentCustomer->getCustomAttribute('customer_phone_number') != null ? $currentCustomer->getCustomAttribute('customer_phone_number')->getValue() : '';
        }

        return $phoneNumber;
    }
}
