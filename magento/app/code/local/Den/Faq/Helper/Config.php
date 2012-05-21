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
 * @package    Den_Faq
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */

class Den_Faq_Helper_Config extends Mage_Core_Helper_Abstract{
    const XML_TAGCLOUD_SIZE = 'faq/menu/tagcloud_size';
    const XML_RECENT_SIZE = 'faq/menu/recent';
    
    const XML_BLOG_PERPAGE = 'faq/faq/perpage';
    const XML_BLOG_READMORE = 'faq/faq/readmore';
    const XML_BLOG_PARSE_CMS = 'faq/faq/parse_cms';
    
    const XML_BLOG_USESHORTCONTENT = 'faq/faq/useshortcontent';
    
    const XML_COMMENTS_PER_PAGE = 'faq/comments/page_count';
    
    public function getCommentsPerPage($store = null) {
        $perPageCount = intval(Mage::getStoreConfig(self::XML_COMMENTS_PER_PAGE, $store));
        if($perPageCount < 1) $perPageCount = 10;
        return $perPageCount;
    }
}
