<?php
class Ktpl_Discountpopup_Model_Observer
{
	public function appendMyNewCustomFiled(Varien_Event_Observer $observer)
        {
            $block = $observer->getEvent()->getBlock();
            if (!isset($block)) {
                return $this;
            }
            if ($block->getType() == 'adminhtml/promo_quote_edit_tab_main') {
                $form = $block->getForm();
                $model = Mage::registry('current_promo_quote_rule');
                $val = 0;
                if($model->getUseInPopup()){
		            $val = 1;
		        }
                //create new custom fieldset 'website'
                $fieldset = $form->getElement('base_fieldset');
                //add new website field
                $fieldset->addField('use_in_popup', 'select', array(
		            'label'     => Mage::helper('salesrule')->__('Use In Popup'),
		            'title'     => Mage::helper('salesrule')->__('Use In Popup'),
		            'name'      => 'use_in_popup',
		            'value'		=> $val,
		            'options'   => array(
		                '1' => Mage::helper('salesrule')->__('Yes'),
		                '0' => Mage::helper('salesrule')->__('No'),
		            ),
		        ));
            }
        }
}