<?php
class Pine_HomepageProducts_Block_Products
        extends Mage_Catalog_Block_Product_List
        implements Mage_Widget_Block_Interface
{

    /**
     * A model to serialize attributes
     * @var Varien_Object
     */
    protected $_serializer = null;

    /**
     * Initialization
     */
    protected function _construct()
    {
        $this->_serializer = new Varien_Object();
        parent::_construct();
    }

    /**
     * Callback Form
     *
     * @return string
     */
    protected function _toHtml()
    {
        $storeId = Mage::app()->getStore()->getId();
        $title = trim($this->getData('title'));
        $maxProductNumber = $this->getData('max_product_number') > 0 ? $this->getData('max_product_number') : 5;
        $productType = $this->getData('product_type');
        $productByAttribute = $this->getData('product_by_attribute');
        $todayDate = date('Y-m-d');

        switch ($productType) {

            case 'new':
                $collection = Mage::getResourceModel('catalog/product_collection');
                Mage::getModel('catalog/layer')->prepareProductCollection($collection);
                $collection->setStoreId($storeId);
                $collection->setPage(1, $maxProductNumber);
                $collection->addStoreFilter();
                $collection->addAttributeToFilter('status', array('eq'=>1));
                $collection->addAttributeToFilter('news_from_date', array('lteq'=>$todayDate));
                $collection->addAttributeToFilter('news_to_date', array('gteq'=>$todayDate));
                $collection->addAttributeToSort('sku', 'ASC');
                break;

            case 'sale':
                $collection = Mage::getResourceModel('catalog/product_collection');
                Mage::getModel('catalog/layer')->prepareProductCollection($collection);
                $collection->setStoreId($storeId);
                $collection->setPage(1, $maxProductNumber);
                $collection->addStoreFilter();
                $collection->addAttributeToFilter('status', array('eq'=>1));
                $collection->addAttributeToFilter('special_from_date', array('lteq'=>$todayDate));
                $collection->addAttributeToFilter('special_to_date', array('gteq'=>$todayDate));
                $collection->addAttributeToSort('sku', 'ASC');
                break;

            case 'popular':
                $collection = Mage::getResourceModel('reports/product_collection');
                Mage::getModel('catalog/layer')->prepareProductCollection($collection);
                $collection->setStoreId($storeId);
                $collection->setPage(1, $maxProductNumber);
                $collection->addStoreFilter();
                $collection->addAttributeToFilter('status', array('eq'=>1));
                $collection->addOrderedQty();
                $collection->addViewsCount();
                $collection->addAttributeToSort('ordered_qty', 'DESC');
                break;

            case 'featured':
                $collection = Mage::getResourceModel('catalog/product_collection');
                Mage::getModel('catalog/layer')->prepareProductCollection($collection);
                $collection->setStoreId($storeId);
                $collection->setPage(1, $maxProductNumber);
                $collection->addStoreFilter();
                $collection->addAttributeToFilter('status', array('eq'=>1));
                $collection->addAttributeToFilter($productByAttribute, array('eq'=>1));
                $collection->addAttributeToSort('sku', 'ASC');
                break;

            default:
                $collection = Mage::getResourceModel('catalog/product_collection');
                Mage::getModel('catalog/layer')->prepareProductCollection($collection);
                $collection->setStoreId($storeId);
                $collection->setPage(1, $maxProductNumber);
                $collection->addStoreFilter();
                $collection->addAttributeToFilter('status', array('eq'=>1));
                $collection->addAttributeToSort('sku', 'ASC');
                break;
        }

        // echo $collection->getSelect();

        $this->_title = $title;
        $this->_productCollection = $collection;

        return parent::_toHtml();
    }

}
