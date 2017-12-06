<?php
/**
 * Created by PhpStorm.
 * User: chenkuan
 * Date: 2017/12/6
 * Time: 下午10:52
 */

namespace Jack\test\Url;


class Scanner
{
    /**
     * @var array 一个由url组成的数组
     */
    protected $urls;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * 构造方法
     * @param array $urls 一个需要扫描的url数组
     */
    public function __construct(array $urls)
    {
        $this->urls = $urls;
        $this->httpClient = new \GuzzleHttp\Client();
    }

    /**
     * 获取死链
     */
    public function getInvalidUrls()
    {
        $invalidUrls = [];
        foreach ($this->urls as $url) {
            try {
                $statusCode = $this->getStatusCodeForUrl($url);
            } catch (\Exception $e) {
                $statusCode = 500;
            }

            if ($statusCode >= 400) {
                array_push($invalidUrls, [
                    'url' => $url,
                    'statusCode' => $statusCode
                ]);
            }
        }
        return $invalidUrls;
    }

    /**
     * 获取指定url的statusCode
     * @param $url
     * @return mixed
     */
    protected function getStatusCodeForUrl($url)
    {
        $httpResponse = $this->httpClient->options($url);
        return $httpResponse->getStatusCode();
    }
}
