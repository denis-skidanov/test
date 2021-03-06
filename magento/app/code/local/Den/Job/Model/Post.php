<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/LICENSE-L.txt
 *
 * @category   AW
 * @package    Den_Job
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */

class Den_Job_Model_Post extends Mage_Core_Model_Abstract{

    const NOROUTE_PAGE_ID = 'no-route';

    protected function _construct(){
        $this->_init('job/post');
    }

    public function load($id, $field=null){
        return $post = parent::load($id, $field);
    }

    public function noRoutePage(){
        $this->setData($this->load(self::NOROUTE_PAGE_ID, $this->getIdFieldName()));
        return $this;
    }
    
    public function getShortContent(){
        $content = $this->getData('short_content');
        if(Mage::getStoreConfig(Den_Job_Helper_Config::XML_BLOG_PARSE_CMS)){
            $processor = Mage::getModel('core/email_template_filter');
            $content = $processor->filter($content);
        }
        return $content;
    }

    public function getPostContent(){
        $content = $this->getData('post_content');
        if(Mage::getStoreConfig(Den_Job_Helper_Config::XML_BLOG_PARSE_CMS)){
            $processor = Mage::getModel('core/email_template_filter');
            $content = $processor->filter($content);
        }
        return $content;
    }

    public function loadByIdentifier($v) {
        return $this->load($v, 'identifier');
    }
}
