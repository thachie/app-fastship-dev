<?php
/**
 * DO NOT EDIT THIS FILE!
 *
 * This file was automatically generated from external sources.
 *
 * Any manual change here will be lost the next time the SDK
 * is updated. You've been warned!
 */

namespace DTS\eBaySDK\BusinessPoliciesManagement\Types;

/**
 *
 * @property \DTS\eBaySDK\BusinessPoliciesManagement\Types\PaymentProfileList $paymentProfileList
 * @property \DTS\eBaySDK\BusinessPoliciesManagement\Types\ReturnPolicyProfileList $returnPolicyProfileList
 * @property \DTS\eBaySDK\BusinessPoliciesManagement\Types\ShippingPolicyProfileList $shippingPolicyProfile
 */
class GetSellerProfilesResponse extends \DTS\eBaySDK\BusinessPoliciesManagement\Types\BaseResponse
{
    /**
     * @var array Properties belonging to objects of this class.
     */
    private static $propertyTypes = [
        'paymentProfileList' => [
            'type' => 'DTS\eBaySDK\BusinessPoliciesManagement\Types\PaymentProfileList',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'paymentProfileList'
        ],
        'returnPolicyProfileList' => [
            'type' => 'DTS\eBaySDK\BusinessPoliciesManagement\Types\ReturnPolicyProfileList',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'returnPolicyProfileList'
        ],
        'shippingPolicyProfile' => [
            'type' => 'DTS\eBaySDK\BusinessPoliciesManagement\Types\ShippingPolicyProfileList',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'shippingPolicyProfile'
        ]
    ];

    /**
     * @param array $values Optional properties and values to assign to the object.
     */
    public function __construct(array $values = [])
    {
        list($parentValues, $childValues) = self::getParentValues(self::$propertyTypes, $values);

        parent::__construct($parentValues);

        if (!array_key_exists(__CLASS__, self::$properties)) {
            self::$properties[__CLASS__] = array_merge(self::$properties[get_parent_class()], self::$propertyTypes);
        }

        if (!array_key_exists(__CLASS__, self::$xmlNamespaces)) {
            self::$xmlNamespaces[__CLASS__] = 'xmlns="http://www.ebay.com/marketplace/selling/v1/services"';
        }

        $this->setValues(__CLASS__, $childValues);
    }
}
