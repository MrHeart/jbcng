<?php

/**
 * Created by PhpStorm.
 * User: Mr Heart
 * Date: 10/2/2016
 * Time: 8:11 PM
 */
class Activity
{
    public function __construct(){
        $this->load->model('adminModel');
    }
    public function log($activity, $userId){
        $this->adminModel->activity_log($activity, $userId);

    }
}