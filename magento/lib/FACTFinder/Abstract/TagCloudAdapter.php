<?php

/**
 * adapter for the factfinder tagcloud data
 *
 * @author    Rudolf Batt <rb@omikron.net>
 * @version   $Id: TagCloudAdapter.php 25893 2010-06-29 08:19:43Z rb $
 * @package   FACTFinder\Abstract
 */
abstract class FACTFinder_Abstract_TagCloudAdapter extends FACTFinder_Abstract_Adapter
{
    private $tagCloud;

    /**
     * get tag cloud
     *
     * @return array $tagCloud list of FACTFinder_Tag objects
     */
    public function getTagCloud() {
        if ($this->tagCloud == null) {
            $this->tagCloud = $this->createTagCloud();
        }
        return $this->tagCloud;
    }

    /**
     * @return array $tagCloud list of FACTFinder_Tag objects
     */
    abstract protected function createTagCloud();
}