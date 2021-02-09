<?php
namespace FME\GoogleMapsStoreLocator\Controller\Router;

/**
 * Interceptor class for @see \FME\GoogleMapsStoreLocator\Controller\Router
 */
class Interceptor extends \FME\GoogleMapsStoreLocator\Controller\Router implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\ActionFactory $actionFactory, \Magento\Framework\App\RequestInterface $request, \FME\GoogleMapsStoreLocator\Helper\Data $helper, \Magento\Cms\Api\PageRepositoryInterface $pageRepository, \Magento\Framework\App\ResponseInterface $response)
    {
        $this->___init();
        parent::__construct($actionFactory, $request, $helper, $pageRepository, $response);
    }

    /**
     * {@inheritdoc}
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'match');
        return $pluginInfo ? $this->___callPlugins('match', func_get_args(), $pluginInfo) : parent::match($request);
    }
}
