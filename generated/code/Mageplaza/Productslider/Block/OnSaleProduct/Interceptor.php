<?php
namespace Mageplaza\Productslider\Block\OnSaleProduct;

/**
 * Interceptor class for @see \Mageplaza\Productslider\Block\OnSaleProduct
 */
class Interceptor extends \Mageplaza\Productslider\Block\OnSaleProduct implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Block\Product\Context $context, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory, \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility, \Magento\Framework\Stdlib\DateTime\DateTime $dateTime, \Mageplaza\Productslider\Helper\Data $helperData, \Magento\Framework\App\Http\Context $httpContext, \Magento\Framework\Url\EncoderInterface $urlEncoder, array $data = [])
    {
        $this->___init();
        parent::__construct($context, $productCollectionFactory, $catalogProductVisibility, $dateTime, $helperData, $httpContext, $urlEncoder, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getImage($product, $imageId, $attributes = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getImage');
        return $pluginInfo ? $this->___callPlugins('getImage', func_get_args(), $pluginInfo) : parent::getImage($product, $imageId, $attributes);
    }
}