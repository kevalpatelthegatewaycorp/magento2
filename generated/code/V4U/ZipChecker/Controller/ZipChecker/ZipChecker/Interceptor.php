<?php
namespace V4U\ZipChecker\Controller\ZipChecker\ZipChecker;

/**
 * Interceptor class for @see \V4U\ZipChecker\Controller\ZipChecker\ZipChecker
 */
class Interceptor extends \V4U\ZipChecker\Controller\ZipChecker\ZipChecker implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Catalog\Model\ProductFactory $productFactory, \V4U\ZipChecker\Helper\Data $dataHelper)
    {
        $this->___init();
        parent::__construct($context, $productFactory, $dataHelper);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        return $pluginInfo ? $this->___callPlugins('execute', func_get_args(), $pluginInfo) : parent::execute();
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        return $pluginInfo ? $this->___callPlugins('dispatch', func_get_args(), $pluginInfo) : parent::dispatch($request);
    }
}
