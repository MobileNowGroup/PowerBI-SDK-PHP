<?php
/*
 * @Author: your name
 * @Date: 2021-06-22 16:23:49
 * @LastEditTime: 2021-07-02 10:04:37
 * @LastEditors: MacBook-Pro.local
 * @Description: In User Settings Edit
 * @FilePath: /PowerBI-SDK-PHP/example.php
 */
require __DIR__.'/vendor/autoload.php';

class example {
    public $clientId = '4fb46635-1674-4486-9c66-b55c94faf123';
    public $clientSecret = '4R.~~5wTR.8~mtZQNs45QhHD-h2Y.KVyGI';
    public $urlAuthorize = 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize';
    public $tenantId = '1524aae2-a73c-44af-ae6a-5e29618346a2';
    public $scope = 'openid';
    public $resource = 'https://analysis.windows.net/powerbi/api';
    public $urlAccessToken;
    public $oauthClient;
    public $pbClient;
        
    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->urlAccessToken = "https://login.windows.net/$this->tenantId/oauth2/token";
        $this->oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                        => $this->clientId,
            'clientSecret'                    => $this->clientSecret,
            'urlAuthorize'                    => $this->urlAuthorize,
            'urlAccessToken'                  => $this->urlAccessToken,
            'urlResourceOwnerDetails'         => '',
            'scope'                           => $this->scope
        ]);
        $this->getToken();
        $this->pbClient = new \Tngnt\PBI\Client($this->accessToken);
    }    
    /**
     * Method getToken
     *
     * @return void
     */
    public function getToken()
    {
        $accessToken = $this->oauthClient->getAccessToken('client_credentials', [
            'resource' => $this->resource
        ]);
        $this->accessToken = $accessToken->getToken();
    }    
    /**
     * Method getGroups
     *
     * @return void
     */
    public function getGroups()
    {
        return $this->pbClient->group->getGroups();
        // {
        //     "id":"4969ba06-9466-44d5-a976-7b2fb1057375","isReadOnly":false,"isOnDedicatedCapacity":true,"capacityId":"651DD900-756F-461F-9541-961078D6DF8D","name":"DFS Rubik Brand Portal"
        //   }
        
    }    
    /**
     * Method getReports
     *
     * @param $group_id $group_id [explicite description]
     *
     * @return void
     */
    public function getReports($group_id = null)
    {
        return $this->pbClient->report->getReports($group_id);
    }    
    /**
     * Method getDashboards
     *
     * @param $group_id $group_id [explicite description]
     *
     * @return void
     */
    public function getDashboards($group_id = null)
    {
        return $this->pbClient->dashboard->getDashboards($group_id);
    }    
    /**
     * Method getReportEmbedToken
     *
     * @param $report_id $report_id [explicite description]
     * @param $group_id $group_id [explicite description]
     * @param $access_level $access_level [explicite description]
     *
     * @return void
     */
    public function getReportEmbedToken($report_id, $group_id, $access_level = "view")
    {
        return $this->pbClient->report->getReportEmbedToken($report_id, $group_id, $access_level);
    }    
    /**
     * Method getDashboardEmbedToken
     *
     * @param $dashboardId $dashboardId [explicite description]
     * @param $groupId $groupId [explicite description]
     * @param $accessLevel $accessLevel [explicite description]
     *
     * @return void
     */
    public function getDashboardEmbedToken($dashboardId, $groupId, $accessLevel = 'view')
    {
        return $this->pbClient->dashboard->getDashboardEmbedToken($report_id, $group_id, $access_level);
    }
    public function getPages($report_id, $group_id)
    {
        return $this->pbClient->report->getPages($report_id, $group_id);
    }
}

$example = new example();
$groups  = $example->getGroups()->toArray();
$reports = $example->getReports($groups['value'][0]['id'])->toArray();
// $pages   = $example->getPages($reports['value'][1]['id'], $groups['value'][0]['id']);
$embed_token = $example->getReportEmbedToken($reports['value'][1]['id'], $groups['value'][0]['id'])->toArray();
var_dump($embed_token);

