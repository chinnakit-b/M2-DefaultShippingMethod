<?php

namespace Dev\DefaultShippingMethod\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use Magento\Shipping\Model\Config as ShippingConfig;

class ShippingMethods implements ArrayInterface
{
    protected $shippingConfig;

    public function __construct(ShippingConfig $shippingConfig)
    {
        $this->shippingConfig = $shippingConfig;
    }

    /**
     * Return available shipping methods as array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $methods = $this->shippingConfig->getActiveCarriers();
        $options = [];

        foreach ($methods as $carrierCode => $carrierModel) {
            if ($carrierModel->getAllowedMethods()) {
                foreach ($carrierModel->getAllowedMethods() as $methodCode => $methodTitle) {
                    $code = $carrierCode . '_' . $methodCode;
                    $options[] = [
                        'value' => $code,
                        'label' => $methodTitle,
                    ];
                }
            }
        }

        return $options;
    }
}
