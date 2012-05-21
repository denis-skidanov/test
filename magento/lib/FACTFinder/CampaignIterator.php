<?php
/**
 * represents the whole collection of campaigns. decorates some campaign functions to execute on every campaign.
 * By iterating over an CampaignIterator you get FACTFinder_Campaign objects in the loop.
 *
 * @author    Rudolf Batt <rb@omikron.net>
 * @version   $Id: CampaignIterator.php 25985 2010-06-30 15:31:53Z rb $
 * @package   FACTFinder\Common
 */
class FACTFinder_CampaignIterator extends ArrayIterator
{
    /**
     * true if a redirect link exists at one of all campaigns
     *
     * @see FACTFinder_Campaign::hasRedirect()
     * @return boolean
     */
    public function hasRedirect()
    {
        $hasRedirect = false;
        foreach ($this AS $campaign) {
            if ($campaign->hasRedirect()) {
                $hasRedirect = true;
                break;
            }
        }
        return $hasRedirect;
    }

    /**
     * return the first redirect of all campaigns or null if non exists
     *
     * @see FACTFinder_Campaign::getRedirectUrl()
     * @return string url
     */
    public function getRedirectUrl(){
        $redirectUrl = null;
        foreach ($this AS $campaign) {
            if ($campaign->hasRedirect()) {
                $redirectUrl = $campaign->getRedirectUrl();
                break;
            }
        }
        return $redirectUrl;
    }

    /**
     * true if pushed products exist in of all campaigns
     *
     * @see FACTFinder_Campaign::hasPushedProducts()
     * @return boolean
     */
    public function hasPushedProducts() {
        $hasPushedProducts = false;
        foreach ($this AS $campaign) {
            if ($campaign->hasPushedProducts()) {
                $hasPushedProducts = true;
                break;
            }
        }
        return $hasPushedProducts;
    }

    /**
     * decorates FACTFinder_Campaign::getPushedProducts() for all campaigns
     *
     * @see FACTFinder_Campaign::getPushedProducts()
     * @return array of records
     */
    public function getPushedProducts() {
        $pushedProducts = array();
        foreach ($this AS $campaign) {
            if ($campaign->hasPushedProducts()) {
                $pushedProducts = array_merge($pushedProducts, $campaign->getPushedProducts());
            }
        }
        return $pushedProducts;
    }

    /**
     * decorates FACTFinder_Campaign::hasFeedback() for each campaign. return true, if one of all campaigns has feedback
     * text snippets
     *
     * @see FACTFinder_Campaign::hasFeedback()
     * @return boolean
     */
    public function hasFeedback($nr = null) {
        $hasFeedback = false;
        foreach ($this AS $campaign) {
            if ($campaign->hasFeedback($nr)) {
                $hasFeedback = true;
                break;
            }
        }
        return $hasFeedback;
    }

    /**
     * decorates FACTFinder_Campaign::getFeedback() for each campaign. returnes a string of each feedback from all
     * campaigns, glued together with PHP_EOL
     *
     * @see FACTFinder_Campaign::getFeedback()
     * @param int $nr of feedback text
     * @return string
     */
    public function getFeedback($nr) {
        $feedback = '';
        foreach ($this AS $campaign) {
            if ($campaign->hasFeedback()) {
                $feedback .= $campaign->getFeedback($nr).PHP_EOL;
            }
        }
        return $feedback;
    }
    
    /**
     * true if active advisor questions exist in any campaign
     *
     * @see FACTFinder_Campaign::hasActiveQuestions()
     * @return boolean
     */
    public function hasActiveQuestions() {
        $hasActiveQuestions = false;
        foreach ($this AS $campaign) {
            if ($campaign->hasActiveQuestions()) {
                $hasActiveQuestions = true;
                break;
            }
        }
        return $hasActiveQuestions;
    }

    /**
     * decorates FACTFinder_Campaign::getActiveQuestions() for all campaigns
     *
     * @see FACTFinder_Campaign::getActiveQuestions()
     * @return array of records
     */
    public function getActiveQuestions() {
        $activeQuestions = array();
        foreach ($this AS $campaign) {
            if ($campaign->hasActiveQuestions()) {
                $activeQuestions = array_merge($activeQuestions, $campaign->getActiveQuestions());
            }
        }
        return $activeQuestions;
    }
    
        
    /**
     * true if an advisor tree exists in any campaign
     *
     * @see FACTFinder_Campaign::hasActiveQuestions()
     * @return boolean
     */
    public function hasAdvisorTree() {
        $hasAdvisorTree = false;
        foreach ($this AS $campaign) {
            if ($campaign->hasAdvisorTree()) {
                $hasAdvisorTree = true;
                break;
            }
        }
        return $hasAdvisorTree;
    }
    
    /**
     * decorates FACTFinder_Campaign::getAdvisorTree() for all campaigns
     *
     * @see FACTFinder_Campaign::getAdvisorTree()
     * @return array of records
     */
    public function getAdvisorTree() {
        $advisorTree = array();
        foreach ($this AS $campaign) {
            if ($campaign->hasAdvisorTree()) {
                $advisorTree = array_merge($advisorTree, $campaign->getAdvisorTree());
            }
        }
        return $advisorTree;
    }
}