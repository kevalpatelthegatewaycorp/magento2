<?php
namespace Mageplaza\BannerSlider\Controller\Adminhtml\Slider\BannersGrid;

/**
 * Interceptor class for @see \Mageplaza\BannerSlider\Controller\Adminhtml\Slider\BannersGrid
 */
class Interceptor extends \Mageplaza\BannerSlider\Controller\Adminhtml\Slider\BannersGrid implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory, \Mageplaza\BannerSlider\Model\SliderFactory $bannerFactory, \Magento\Framework\Registry $registry, \Magento\Backend\App\Action\Context $context)
    {
        $this->___init();
        parent::__construct($resultLayoutFactory, $bannerFactory, $registry, $context);
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
