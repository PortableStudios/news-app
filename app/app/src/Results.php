<?php 

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

        // return the results or null if there was an error
        if ($proxy_api_raw_data!=null) return $proxy_api_raw_data->response->results;
        return null;
    }
}