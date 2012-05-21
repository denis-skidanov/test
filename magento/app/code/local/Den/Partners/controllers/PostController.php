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
 * @package    Den_Partners
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */

require_once 'recaptcha/recaptchalib-aw.php';

class Den_Partners_PostController extends Mage_Core_Controller_Front_Action {
    
	public function preDispatch() {       
        
		parent::preDispatch();
		 
        if(!Mage::helper('partners')->getEnabled()) {
            $this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());
        }
        
	}

	public function viewAction() {         
        
        $identifier = $this->getRequest()->getParam('identifier', $this->getRequest()->getParam('id', false));

		if ($data = $this->getRequest()->getPost()) {
			$model = Mage::getModel('partners/comment');
            $data['user'] = strip_tags($data['user']);
			$model->setData($data);

			if (!Mage::getStoreConfig('partners/comments/enabled')) {
				Mage::getSingleton('customer/session')->addError(Mage::helper('partners')->__('Comments are not enabled.'));
				if (!Mage::helper('partners/post')->renderPage($this, $identifier)) {
					$this->_forward('NoRoute');
				}
				return;
			}


			if (!Mage::getSingleton('customer/session')->isLoggedIn() && Mage::getStoreConfig('partners/comments/login')) {
				Mage::getSingleton('customer/session')->addError(Mage::helper('partners')->__('You must be logged in to comment.'));
				if (!Mage::helper('partners/post')->renderPage($this, $identifier)) {
					$this->_forward('NoRoute');
				}
				return;
			}
			else if (Mage::getSingleton('customer/session')->isLoggedIn() && Mage::getStoreConfig('partners/comments/login')) {
				$model->setUser(Mage::helper('partners')->getUserName());
				$model->setEmail(Mage::helper('partners')->getUserEmail());
			}

			try {

				if (Mage::getStoreConfig('partners/recaptcha/enabled') && !Mage::getSingleton('customer/session')->isLoggedIn()) {
					$publickey = Mage::getStoreConfig('partners/recaptcha/publickey');
					$privatekey = Mage::getStoreConfig('partners/recaptcha/privatekey');

					$resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"], $data["recaptcha_challenge_field"], $data["recaptcha_response_field"]);

					if (!$resp->is_valid) {
						if ($resp->error == "incorrect-captcha-sol") {
							Mage::getSingleton('customer/session')->addError(Mage::helper('partners')->__('Your Recaptcha solution was incorrect, please try again'));
						}
						else {
							Mage::getSingleton('customer/session')->addError(Mage::helper('partners')->__('An error occured. Please try again'));
						}
						// Redirect back with error message
                        Mage::getSingleton('customer/session')->setPartnersPostModel($model);
						$this->_redirectReferer();
						return;
					}
				}

                if (Mage::getSingleton('customer/session')->getData('partners_post_model'))
                    Mage::getSingleton('customer/session')->unsetData('partners_post_model');
				$model->setCreatedTime(now());
				$model->setComment(htmlspecialchars($model->getComment(), ENT_QUOTES));
				if (Mage::getStoreConfig('partners/comments/approval')) {
					$model->setStatus(2);
					Mage::getSingleton('customer/session')->addSuccess(Mage::helper('partners')->__('Your comment has been submitted.'));
				}
				else if (Mage::getSingleton('customer/session')->isLoggedIn() && Mage::getStoreConfig('partners/comments/loginauto')) {
					$model->setStatus(2);
					Mage::getSingleton('customer/session')->addSuccess(Mage::helper('partners')->__('Your comment has been submitted.'));
				}
				else {
					$model->setStatus(1);
					Mage::getSingleton('customer/session')->addSuccess(Mage::helper('partners')->__('Your comment has been submitted and is awaiting approval.'));
				}
				$model->save();

				$comment_id = $model->getCommentId();

			} catch (Exception $e) {
				if (!Mage::helper('partners/post')->renderPage($this, $identifier)) {
					$this->_forward('NoRoute');
				}
			}

			if (Mage::getStoreConfig('partners/comments/recipient_email') != null && $model->getStatus() == 1 && isset($comment_id)) {
				$translate = Mage::getSingleton('core/translate');
				/* @var $translate Mage_Core_Model_Translate */
				$translate->setTranslateInline(false);
				try {
					$data["url"] = Mage::getUrl('partners/manage_comment/edit/id/' . $comment_id);
					$postObject = new Varien_Object();
					$postObject->setData($data);
					$mailTemplate = Mage::getModel('core/email_template');
					/* @var $mailTemplate Mage_Core_Model_Email_Template */
					$mailTemplate->setDesignConfig(array('area' => 'frontend'))
							->sendTransactional(
							Mage::getStoreConfig('partners/comments/email_template'),
							Mage::getStoreConfig('partners/comments/sender_email_identity'),
							Mage::getStoreConfig('partners/comments/recipient_email'),
							null,
							array('data' => $postObject)
					);
					$translate->setTranslateInline(true);
				} catch (Exception $e) {
					$translate->setTranslateInline(true);
				}
			}
			$this->_redirectReferer();
			return;
			if (!Mage::helper('partners/post')->renderPage($this, $identifier)) {
				$this->_forward('NoRoute');
			}


		}else {
			if (!Mage::helper('partners/post')->renderPage($this, $identifier)) {
				$this->_forward('NoRoute');
			}
		}
	}

	public function noRouteAction($coreRoute = null) {
		$this->getResponse()->setHeader('HTTP/1.1','404 Not Found');
		$this->getResponse()->setHeader('Status','404 File not found');

		$pageId = Mage::getStoreConfig('web/default/cms_no_route');
		if (!Mage::helper('cms/page')->renderPage($this, $pageId)) {
			$this->_forward('defaultNoRoute');
		}
	}


}
