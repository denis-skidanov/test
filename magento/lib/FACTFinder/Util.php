<?php

/**
 * util class for some repeated issues which do not fit to a single class
 *
 * @author    Rudolf Batt <rb@omikron.net>
 * @version   $Id: Util.php 25893 2010-06-29 08:19:43Z rb $
 * @package   FACTFinder\Common
 **/
class FACTFinder_Util
{
    protected $searchAdapter;
    protected $ffparams;

    public function __construct(FACTFinder_Parameters $ffparams, FACTFinder_Abstract_SearchAdapter $searchAdapter) {
        $this->ffparams = $ffparams;
        $this->searchAdapter = $searchAdapter;
    }

    /**
     * @return string javascript method call "clickProduct" with all needed arguments
     */
    public function createJavaScriptClickCode($record, $title, $sid)
    {
        $query             = addcslashes(htmlspecialchars($this->ffparams->getQuery()), "'");
        $channel           = $this->ffparams->getChannel();

        $currentPageNumber = $this->searchAdapter->getPaging()->getCurrentPageNumber();
        $origPageSize      = $this->searchAdapter->getProductsPerPageOptions()->getDefaultOption()->getValue();
        $pageSize          = $this->searchAdapter->getProductsPerPageOptions()->getSelectedOption()->getValue();

        $position          = $record->getPosition();
        if ($position != 0) {
            $originalPosition  = $record->getOriginalPosition();
            $similarity        = $record->getSimilarity();
            $id                = $record->getId();

            $title             = addslashes($title);
            $sid               = addslashes($sid);
            $clickCode         = "clickProduct('$query', '$id', '$position', '$originalPosition', '$currentPageNumber',"
                                ."'$similarity', '$sid', '$title', '$pageSize', '$origPageSize', '$channel', 'click');";
        } else {
            $clickCode = '';
        }

        return $clickCode;
    }
}