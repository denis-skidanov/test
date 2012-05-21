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
class Den_Faq_Block_Post extends Mage_Core_Block_Template {

    private $_pageCount = 1;
    private $_totalCommentsCount = null;

    public function getPost() {
        if (!$this->hasData('post')) {
            if ($this->getPostId()) {
                $post = Mage::getModel('faq/post')
                                ->setStoreId(Mage::app()->getStore()->getId())
                                ->load($this->getPostId(), 'post_id');
            } else {
                $post = Mage::getSingleton('faq/post');
            }
            
            /* Escape special chars */
            Den_Faq_Helper_Data::escapeSpecialChars($post);
            /******************************/
            
            
            
            $cat = Mage::getSingleton('faq/cat')->load($this->getRequest()->getParam('cat'), "identifier");
            $route = Mage::getStoreConfig('faq/faq/route');
            if ($route == "") {
                $route = "faq";
            }
            $route = Mage::getUrl($route);
            if ($cat->getIdentifier() != null) {
                $post->setAddress($route . 'cat/' . $cat->getIdentifier() . "/post/" . $post->getIdentifier());
                $post->setIdentifier('cat/' . $cat->getIdentifier() . "/post/" . $post->getIdentifier());
            } else {
                $post->setAddress($route . $post->getIdentifier());
                $post->setIdentifier($post->getIdentifier());
            }
            $post->setCreatedTime($this->formatTime($post->getCreatedTime(), Mage::getStoreConfig('faq/faq/dateformat'), true));
            $post->setUpdateTime($this->formatTime($post->getUpdateTime(), Mage::getStoreConfig('faq/faq/dateformat'), true));

            $cats = Mage::getModel('faq/cat')->getCollection()
                            ->addPostFilter($post->getPostId());
            $catUrls = array();
            foreach ($cats as $cat) {
                $catUrls[$cat->getTitle()] = $route . "cat/" . $cat->getIdentifier();
            }
            $post->setCats($catUrls);

            $this->setData('post', $post);
        }
        return $this->getData('post');
    }

    public function getBookmarkHtml($post) {
        if (Mage::getStoreConfig('faq/faq/bookmarkspost')) {
            $this->setTemplate('aw_faq/bookmark.phtml');
            $this->setPost($post);
            return $this->toHtml();
        }
        return;
    }

    public function getComment() {
        $post = $this->getPost();
        $_curPage = Mage::app()->getRequest()->getParam('p') ? Mage::app()->getRequest()->getParam('p') : 1;
        $_perPage = Mage::helper('faq/config')->getCommentsPerPage();
        $collection = Mage::getModel('faq/comment')->getCollection()
                        ->addPostFilter($post->getPostId())
                        ->setOrder('created_time ', 'asc')
                        ->addApproveFilter(2)
                        ->setPageSize($_perPage)
                        ->setCurPage($_curPage);
        $this->_totalCommentsCount = $collection->getSize();
        $this->_pageCount = intval(ceil($this->_totalCommentsCount / $_perPage));
        return $collection;
    }

    public function getPageCount() {
        return $this->_pageCount;
    }

    public function getCommentTotalString($comments) {
        $comment_count = $this->_totalCommentsCount;
        if ($comment_count == 1) {
            $comment_string = $comment_count . " " . Mage::helper('faq')->__('Comment');
        } else {
            $comment_string = $comment_count . " " . Mage::helper('faq')->__('Comments');
        }
        return $comment_string;
    }

    public function getCommentsEnabled() {
        return Mage::getStoreConfig('faq/comments/enabled');
    }

    public function getLoginRequired() {
        return Mage::getStoreConfig('faq/comments/login');
    }

    public function getFormAction() {
        return $this->getUrl('*/*/*');
    }

    public function getFormData() {
        return $this->getRequest();
    }

    protected function _prepareLayout() {
        $post = $this->getPost();
        $cat = Mage::getSingleton('faq/cat')->load($this->getRequest()->getParam('cat'), "identifier");

        $route = Mage::helper('faq')->getRoute();
        // show breadcrumbs
        if (Mage::getStoreConfig('faq/faq/faqcrumbs') && ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))) {
            $breadcrumbs->addCrumb('home', array('label' => Mage::helper('faq')->__('Home'), 'title' => Mage::helper('faq')->__('Go to Home Page'), 'link' => Mage::getBaseUrl()));
            ;
            $breadcrumbs->addCrumb('faq', array('label' => Mage::getStoreConfig('faq/faq/title'), 'title' => Mage::helper('faq')->__('Return to ' . Mage::getStoreConfig('faq/faq/title')), 'link' => Mage::getUrl($route)));
            if ($cat->getTitle() != "") {
                $breadcrumbs->addCrumb('cat', array('label' => $cat->getTitle(), 'title' => Mage::helper('faq')->__('Return to ' . $cat->getTitle()), 'link' => Mage::getUrl($route . '/cat/' . $cat->getIdentifier())));
            }
            $breadcrumbs->addCrumb('faq_page', array('label' => htmlspecialchars_decode($post->getTitle())));
        }

        if ($head = $this->getLayout()->getBlock('head')) {
            $head->setTitle($post->getTitle());
            $head->setKeywords($post->getMetaKeywords());
            $head->setDescription($post->getMetaDescription());
        }
    }

    public function setCommentDetails($name, $email, $comment) {
        $this->_data['commentName'] = $name;
        $this->_data['commentEmail'] = $email;
        $this->_data['commentComment'] = $comment;
        return $this;
    }

    public function getCommentText() {
        $faqPostModelFromSession = Mage::getSingleton('customer/session')->getBlogPostModel();
        if ($faqPostModelFromSession)
            return $faqPostModelFromSession->getComment();

        if (!empty($this->_data['commentComment'])) {
            return $this->_data['commentComment'];
        }
        return;
    }

    public function getCommentEmail() {
        $faqPostModelFromSession = Mage::getSingleton('customer/session')->getBlogPostModel();
        if ($faqPostModelFromSession)
            return $faqPostModelFromSession->getEmail();

        if (!empty($this->_data['commentEmail'])) {
            return $this->_data['commentEmail'];
        } elseif ($customer = Mage::getSingleton('customer/session')->getCustomer()) {
            return $customer->getEmail();
        }
        return;
    }

    public function getCommentName() {
        $faqPostModelFromSession = Mage::getSingleton('customer/session')->getBlogPostModel();
        if ($faqPostModelFromSession)
            return $faqPostModelFromSession->getUser();

        if (!empty($this->_data['commentName'])) {
            return $this->_data['commentName'];
        } elseif ($customer = Mage::getSingleton('customer/session')->getCustomer()) {
            return $customer->getName();
        }
        return;
    }

    public function _toHtml() {
        return Mage::helper('faq')->filterWYS(parent::_toHtml());
    }

    public function getPageAddress($page) {
        $route = Mage::helper('faq')->getRoute();
        $params = array(
            '_query' => array(
                'p' => $page
            ),
            '_direct' => $route . '/' . $this->getPost()->getIdentifier()
        );
        return Mage::getUrl('', $params);
    }

    public function current($i) {
        
        if($i == 1 && !Mage::app()->getRequest()->getParam('p')) { return true; }
        
        return $i == Mage::app()->getRequest()->getParam('p');        
    }

}
