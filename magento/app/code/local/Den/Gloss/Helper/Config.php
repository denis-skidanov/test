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
 * @package    Den_Gloss
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */

class Den_Gloss_Helper_Config extends Mage_Core_Helper_Abstract{
    const XML_TAGCLOUD_SIZE = 'gloss/menu/tagcloud_size';
    const XML_RECENT_SIZE = 'gloss/menu/recent';
    
    const XML_BLOG_PERPAGE = 'gloss/gloss/perpage';
    const XML_BLOG_READMORE = 'gloss/gloss/readmore';
    const XML_BLOG_PARSE_CMS = 'gloss/gloss/parse_cms';
    
    const XML_BLOG_USESHORTCONTENT = 'gloss/gloss/useshortcontent';
    
    const XML_COMMENTS_PER_PAGE = 'gloss/comments/page_count';
    
    public function getCommentsPerPage($store = null) {
        $perPageCount = intval(Mage::getStoreConfig(self::XML_COMMENTS_PER_PAGE, $store));
        if($perPageCount < 1) $perPageCount = 10;
        return $perPageCount;
    }
}
