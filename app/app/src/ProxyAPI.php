<?php 

    /**
     * 
     * Handles the communications post/get with the guardian API
     * 
     */
class ProxyAPI {
    private $api_key;
    private $keyword;
    private $api_url;
    private $api_operation;
    private $site;

    function __construct($keyword, $site, $api_url, $api_operation, $api_key) {
        $this->api_key = $api_key;
        $this->keyword = $keyword;
        $this->site = $site;
        $this->api_url = $api_url;
        $this->api_operation = $api_operation;
        $this->api_key = $api_key;
    }

    /**
     * Get data from API
     * 
     */
    public function getData()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->api_url.$this->api_operation.'?api-key='.$this->api_key.'&q='.$this->keyword,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET'
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        // JSON format the response for future work
        $response = json_decode($response);
        return $response;
    }
}