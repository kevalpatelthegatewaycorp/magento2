<?php


namespace GT\CustomerDetails\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\CheckoutAgreements\Model\AgreementFactory;

class AddTermsAndCondition implements DataPatchInterface, PatchRevertableInterface
{

    /**
     * @var ModuleDataSetupInterface
     */

    private $moduleDataSetup;

    /**
     * @var AgreementFactory
     */
    private $agreementFactory;

    /**
     * Constructor
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param AgreementFactory $agreementFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        AgreementFactory $agreementFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->agreementFactory = $agreementFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        $model = $this->agreementFactory->create();
        $termsAndConditionsDetail =  [
            "name" => "Terms and Conditions",
            "is_active" => "1",
            "is_html" => "0",
            "mode" => "0",
            "stores" => ["0"],
            "checkbox_text" => 'By proceeding you accept the terms and conditions.',
            "content" => "These Terms and Conditions constitute a legally binding agreement made between you, whether personally or on behalf of an entity (“you”) and [business entity name] (“we,” “us” or “our”), concerning your access to and use of the [website name.com] website as well as any other media form, media channel, mobile website or mobile application related, linked, or otherwise connected thereto (collectively, the “Site”).",
            "content_height" => ""
        ];

        $model->setData($termsAndConditionsDetail);
        $model->save();

        $this->moduleDataSetup->endSetup();
    }

    public function revert()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }
}
