<?php

namespace GT\ApproveUser\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class SendApprovalEmail implements ObserverInterface
{
    public const ENABLE_MAIL = 'customerlogin/general/enableMail';

    public function __construct(
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $enableMail = $this->scopeConfig->getValue(self::ENABLE_MAIL, $storeScope);

        // do not send mail if disabled
        if (!$enableMail) return $this;

        $customer = $observer->getCustomerDataObject();
        $customerOld = $observer->getOrigCustomerDataObject();

        // do not send mail if customer is new created from backoffice
        if (!isset($customerOld)) return $this;

        $approveAccount = (int) $customer->getCustomAttribute('approve_account')->getValue();
        $oldAppAcc = $customerOld->getCustomAttribute('approve_account');
        $approveAccountOld = isset($oldAppAcc) ? (int) $oldAppAcc->getValue() : 0;

        if ($approveAccount !== $approveAccountOld && $approveAccount === 1) {
            $firstName = $customer->getFirstName();
            $customerEmail = $customer->getEmail();
            $approveVariables = [
                'first_name' => $firstName
            ];


            $email = $this->scopeConfig->getValue('trans_email/ident_general/email', ScopeInterface::SCOPE_STORE);
            $name  = $this->scopeConfig->getValue('trans_email/ident_general/name', ScopeInterface::SCOPE_STORE);

            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($approveVariables);

            $transport = $this->transportBuilder
                ->setTemplateIdentifier('gt_approveUser_account_approved')
                ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_ADMINHTML, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
                ->setTemplateVars(['data' => $postObject])
                ->setFrom(['name' => $name, 'email' => $email])
                ->addTo([$customerEmail])
                ->getTransport();

            $transport->sendMessage();
        }

        return $this;
    }
}
