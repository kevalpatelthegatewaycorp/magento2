<?php


namespace GT\ApproveUser\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

class ApproveUserAttribute implements DataPatchInterface, PatchRevertableInterface
{

    /**
     * attribute to identify customer account is approved or not
     */
    const APPROVE_ACCOUNT = 'approve_account';

    /**
     * @var ModuleDataSetupInterface
     */

    private $moduleDataSetup;
    /**
     * @var CustomerSetup
     */

    private $customerSetupFactory;
    /**
     * @var AttributeSetFactory
     */

    private $attributeSetFactory;

    /**
     * @var Customer
     */
    private $customer;

    /**
     * Constructor
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory,
        Customer $customer
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->customer = $customer;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        /**
         *  Customer attributes
         */
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        /** @var $attributeSet AttributeSet */
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);


        /**
         * Create customer attribute account_approve
         */
        $customerSetup->addAttribute(
            Customer::ENTITY,
            self::APPROVE_ACCOUNT,
            [
                'type' => 'int',
                'label' => 'Approve Account',
                'input' => 'select',
                "source"   => "GT\ApproveUser\Model\Config\Source\CustomerYesNoOptions",
                'required' => false,
                'default' => '0',
                'visible' => true,
                'user_defined' => true,
                'sort_order' => 215,
                'position' => 215,
                'system' => false,
            ]
        );
        $approve_account = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, self::APPROVE_ACCOUNT)
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer', 'customer_account_create', 'customer_account_edit'],
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true,
            ]);
        $approve_account->save();

        $this->moduleDataSetup->endSetup();

        $customers = $this->customer->getCollection()->addAttributeToSelect("*")->load();
        foreach ($customers as $key => $customer) {

            $customer->setData('approve_account', '1');
            $customer->save();
        }
    }

    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $customerSetup->removeAttribute(\Magento\Customer\Model\Customer::ENTITY, 'approve_account');

        $this->moduleDataSetup->getConnection()->endSetup();
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
