<?php 

use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use SilverStripe\ORM\GroupedList;

/**
 * 
 * Handles the formatting of the results
 * 
 */
class Results {

    // query 
    private $keyword;

    // add more sites later?
    const SITES = "guardian";

    /**
     * @param $keyword: searched keyword
     */
    function __construct($keyword=null) {
        $this->keyword = $keyword;
    }

    /**
     * 
     */
    public function getData()
    {
        // get raw data using proxyAPI
        $proxy_api= new ProxyAPI(
            $this->keyword, 
            self::SITES, 
            "https://content.guardianapis.com/", 
            "search",
            "test");

        $proxy_api_raw_data = $proxy_api->getData();

        return $this->formatData($proxy_api_raw_data);
    }

    /**
     * 
     */
    private function formatData($api_data = null)
    {

        $results = $api_data->response->results;
        // save result in a format grouped by section and that the template ss will understand
        $output = new ArrayList();
        foreach($results as $result)
        {
            $output->push(new ArrayData(array(
                'SectionName' => $result->sectionName,
                'SectionID' => $result->sectionId,
                'ArticleData' => $this->formatArticleData($result)
            ))); 
        }
        $grouped_output = new GroupedList($output);
        $grouped_output = $grouped_output->GroupedBy("SectionName",'ArticleData');
        return $grouped_output;
    }

    /**
     * 
     */
    private function formatArticleData($result = null)
    {
        $output = new ArrayData(array(
            'ID' => $result->id,
            'PublicationDate' => $result->webPublicationDate,
            'WebsiteTitle' => $result->webTitle,
            'WebsiteURL' => $result->webUrl,
            'SectionID' => $result->sectionId
        )); 
        return $output;
    }

    private function formatDate($raw_date)
    {
        // Convert raw date to D/M/Y format
        return $raw_date;
    }
}