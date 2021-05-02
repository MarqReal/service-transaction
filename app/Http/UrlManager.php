<?php


namespace App\Http;


class UrlManager
{
    private $url;
    private $params;

    public function __construct()
    {

    }

    /**
     * This function aims to make an HTPP / GET request according to the passed url
     * @param $url
     * @return array|mixed
     */
    public function get($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'MicroServiceTransaction');
        $output = json_decode(curl_exec($ch), true);
        if (!$output) {
            $output = ["success" => false, "message" => curl_error($ch)];
        }
        curl_close($ch);
        $output["success"] = true;
        return $output;
    }

    /**
     * This function aims to make an HTPP / POST request according to the passed url and parameters
     * @param $url
     * @param array $requestParameters
     * @return array|mixed
     */
    public function post($url, array $requestParameters)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'MicroServiceTransaction');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($requestParameters));
        $output = json_decode(curl_exec($ch), true);
        if (!$output) {
            $output = ["success" => false, "message" => curl_error($ch)];
        }
        curl_close($ch);
        $output["success"] = true;
        return $output;
    }
}
