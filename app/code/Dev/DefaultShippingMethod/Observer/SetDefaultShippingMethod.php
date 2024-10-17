<?php

namespace Dev\DefaultShippingMethod\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Quote\Model\QuoteRepository;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Config\ScopeConfigInterface;

class SetDefaultShippingMethod implements ObserverInterface
{
    protected $quoteRepository;

    protected $checkoutSession;

    protected $scopeConfig;

    // Path to the system config for shipping method
    const XML_PATH_DEFAULT_SHIPPING_METHOD = 'shipping_config_section/default_config_group/shipping_method_list';

    public function __construct(
        QuoteRepository      $quoteRepository,
        CheckoutSession      $checkoutSession,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->quoteRepository = $quoteRepository;
        $this->checkoutSession = $checkoutSession;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute(Observer $observer)
    {
        // Get the quote object
        $quote = $this->checkoutSession->getQuote();

        // Check if a shipping method is already set
        if (!$quote->getShippingAddress()->getShippingMethod()) {

            // Retrieve the default shipping method from system config
            $defaultShippingMethod = $this->scopeConfig->getValue(
                self::XML_PATH_DEFAULT_SHIPPING_METHOD,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );

            // Check if a default shipping method is set in the configuration
            if ($defaultShippingMethod) {
                // Set the default shipping method from system config
                $quote->getShippingAddress()->setShippingMethod($defaultShippingMethod);

                // Recalculate shipping totals
                $quote->getShippingAddress()->setCollectShippingRates(true);
                $quote->collectTotals();

                // Save the updated quote
                $this->quoteRepository->save($quote);
            }
        }
    }
}
