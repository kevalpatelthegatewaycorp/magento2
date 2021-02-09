<?php
namespace Amazon\Login\Model\Customer\Account\Redirect;

/**
 * Interceptor class for @see \Amazon\Login\Model\Customer\Account\Redirect
 */
class Interceptor extends \Amazon\Login\Model\Customer\Account\Redirect implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\RequestInterface $request, \Magento\Customer\Model\Session $customerSession, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\UrlInterface $url, \Magento\Framework\Url\DecoderInterface $urlDecoder, \Magento\Customer\Model\Url $customerUrl, \Magento\Framework\Controller\ResultFactory $resultFactory, \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory, \Magento\Checkout\Model\Session $checkoutSession)
    {
        $this->___init();
        parent::__construct($request, $customerSession, $scopeConfig, $storeManager, $url, $urlDecoder, $customerUrl, $resultFactory, $cookieMetadataFactory, $checkoutSession);
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirect()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRedirect');
        return $pluginInfo ? $this->___callPlugins('getRedirect', func_get_args(), $pluginInfo) : parent::getRedirect();
    }
}
