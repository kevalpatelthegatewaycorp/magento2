<?php
namespace Magento\Customer\Model\Account\Redirect;

/**
 * Interceptor class for @see \Magento\Customer\Model\Account\Redirect
 */
class Interceptor extends \Magento\Customer\Model\Account\Redirect implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\RequestInterface $request, \Magento\Customer\Model\Session $customerSession, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\UrlInterface $url, \Magento\Framework\Url\DecoderInterface $urlDecoder, \Magento\Customer\Model\Url $customerUrl, \Magento\Framework\Controller\ResultFactory $resultFactory, \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory, ?\Magento\Framework\Url\HostChecker $hostChecker = null)
    {
        $this->___init();
        parent::__construct($request, $customerSession, $scopeConfig, $storeManager, $url, $urlDecoder, $customerUrl, $resultFactory, $cookieMetadataFactory, $hostChecker);
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
