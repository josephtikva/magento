<?php

/**
 * SunshineBiz_Location Region collection
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Model_Resource_Region_Collection extends Mage_Directory_Model_Resource_Region_Collection {

    public function __construct($resource = null) {
        parent::__construct(Mage::getResourceModel('location/region'));
    }
    
    protected function _construct() {
         $this->_init('location/region');

        $this->_countryTable = $this->getTable('directory/country');
        
        $this->addOrder('country_id', Varien_Data_Collection::SORT_ORDER_ASC);
        $this->addOrder('region_id', Varien_Data_Collection::SORT_ORDER_ASC);
    }
    
    protected function _initSelect() {
        
        $this->addBindParam(':locale', Mage::app()->getLocale()->getLocaleCode());
        $this->getSelect()->from(array('main_table' => $this->getMainTable()))->joinLeft(
            array('name_table' => $this->getResource()->getNameTable()),
            "main_table.{$this->getResource()->getIdFieldName()} = name_table.{$this->getResource()->getFkFieldName()} AND name_table.locale = :locale",
            $this->getResource()->getNameCols());

        return $this;
    }

    /**
     * Convert collection items to select options array
     *
     * @param string $emptyLabel
     * @return array
     */
    public function toOptionArray($emptyLabel = '') {

        $options = array();
        foreach ($this as $region) {
            $data['value'] = $region->getId();
            $data['label'] = $region->getName();
            $options[] = $data;
        }
        
        if (count($options) > 0 && $emptyLabel !== false) {
            if(!$emptyLabel)
                $emptyLabel = Mage::helper('core')->__('-- Please Select --');
            
            array_unshift($options, array('value' => '', 'label' => $emptyLabel));
        }

        return $options;
    }

}