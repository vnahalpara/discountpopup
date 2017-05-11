<?php
class Ktpl_Discountpopup_Block_Discountpopup extends Mage_Checkout_Block_Cart_Coupon
{
    public function getCouponCollection()
    {
        $collection = Mage::getModel('salesrule/rule')->getCollection();
        $collection
                ->addfieldtofilter('from_date',
                        array(
                            array('lteq' => Mage::getModel('core/date')->gmtDate()),
                            array('from_date', 'null'=>''))
                        )
                ->addfieldtofilter('to_date',
                        array(
                            array('gteq' => Mage::getModel('core/date')->gmtDate()),
                            array('to_date', 'null'=>''))
                        )
                ->addFieldToFilter('is_active',1)
                ->addFieldToFilter('use_in_popup',1)
                ->addWebsiteFilter(Mage::app()->getStore()->getWebsiteId());
        return $collection;
    }
}