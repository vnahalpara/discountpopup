<?php
ini_set('memory_limit', '500M');
ini_set('max_execution_time', 0);
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
class Ktpl_Discountpopup_IndexController extends Mage_Core_Controller_Front_Action
{
	public function applyAction()
	{
		$response = array();
		$couponCode = (string) $this->getRequest()->getParam('coupon_code');
        if ($this->getRequest()->getParam('remove') == 1) {
            $couponCode = '';
        }
        $oldCouponCode = $this->_getQuote()->getCouponCode();

        if (!strlen($couponCode) && !strlen($oldCouponCode)) {
            $message = "Please enter valid code";
            $response['error'] = 1;
        }

        try {
            $codeLength = strlen($couponCode);
            $isCodeLengthValid = $codeLength && $codeLength <= Mage_Checkout_Helper_Cart::COUPON_CODE_MAX_LENGTH;

            $this->_getQuote()->getShippingAddress()->setCollectShippingRates(true);
            $this->_getQuote()->setCouponCode($isCodeLengthValid ? $couponCode : '')
                ->collectTotals()
                ->save();

            if ($codeLength) {
                if ($isCodeLengthValid && $couponCode == $this->_getQuote()->getCouponCode()) {
                        $message = $this->__('Coupon code "%s" was applied.', Mage::helper('core')->escapeHtml($couponCode));
                    $this->_getSession()->setCartCouponCode($couponCode);
                } else {
                        $message = $this->__('Coupon code "%s" is not valid.', Mage::helper('core')->escapeHtml($couponCode));
                        $response['error'] = 1;
                }
            } else {
                $message = $this->__('Coupon code was canceled.');
            }

        } catch (Mage_Core_Exception $e) {
            $message = $e->getMessage();
            $response['error'] = 1;
        } catch (Exception $e) {
        	$response['error'] = 1;
            $message = $this->__('Cannot apply the coupon code.');
            Mage::logException($e);
        }
        $response['message'] = $message;
        $response['code'] = $couponCode;
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
	}

	public function cancelAction()
	{
		
	}

	public function reloadtotalAction()
	{
		$response['block'] = $this->getLayout()->createBlock('checkout/cart_totals')->setTemplate('checkout/cart/totals.phtml')->toHtml();
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
	}

	public function reloadcouponAction()
	{
		$response['block'] = $this->getLayout()->createBlock('checkout/cart_coupon')->setTemplate('discountpopup/reloadCoupon.phtml')->toHtml();
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
	}

	protected function _getQuote()
    {
        return $this->_getCart()->getQuote();
    }

    protected function _getCart()
    {
        return Mage::getSingleton('checkout/cart');
    }

    protected function _getSession()
    {
        return Mage::getSingleton('checkout/session');
    }

}