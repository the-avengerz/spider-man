<?php
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Spider\SpiderWeb;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class DemoSpiderWeb extends SpiderWeb
{
    public $name = 'demo';

    public function process(Crawler $crawler, ResponseInterface $response)
    {
//        $response->getBody()->rewind();
//        print_r($response->getBody()->getContents());

        $this->pipe(DemoPipe::class, $crawler, $response);

//        $this->emit(new DemoSpiderWeb('GET', 'http://api.k780.com:88/?app=ip.get&ip=8.8.8.8&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4&format=json'));
//        $this->emit(new DemoSpiderWeb('GET', 'http://api.k780.com:88/?app=ip.get&ip=8.8.8.8&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4&format=json'));
//        $this->emit(new DemoSpiderWeb('GET', 'http://api.k780.com:88/?app=ip.get&ip=8.8.8.8&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4&format=json'));
//        $this->emit(new DemoSpiderWeb('GET', 'http://api.k780.com:88/?app=ip.get&ip=8.8.8.8&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4&format=json'));
//        $this->emit(new DemoSpiderWeb('GET', 'http://api.k780.com:88/?app=ip.get&ip=8.8.8.8&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4&format=json'));
    }

    function error(RequestException $requestException)
    {

    }
}