<?php

class Utils{

    public $hostname = "https://isa.epfl.ch/";

    public function call_service($url): array
    {
        $result = array();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result['response'] = curl_exec($curl);
        $result['httpCode']  = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return $result;
    }

    public function show_error_message($url,$error){
        $message = $url . ' - ' . $error;
        echo "<script type='text/javascript'>alert('$message');</script>";
    }
}