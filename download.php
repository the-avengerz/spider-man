<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

use GuzzleHttp\Psr7\Uri;
use Symfony\Component\DomCrawler\Crawler;

include __DIR__ . '/vendor/autoload.php';

$db = new medoo([
    'database_type' => 'mysql',
    'database_name' => 'ycf',
    'server' => 'localhost',
    'username' => 'root',
    'password' => '123456',
    'charset' => 'utf8'
]);

$list = $db->query('select * from scenic_spot where product_id < 200 AND status = 0');

foreach ($list as $item) {
    $content = '<html><body>' . $item['body'] . '</body></html>';
    $node = new Crawler($content, 'https://qiniu-cdn6.jinxidao.com');
    $node->filter('img')->each(function (Crawler $node) {
        $url = $node->attr('src');
        if ('defImage.png' == pathinfo($url, PATHINFO_BASENAME)) {
            $url = $node->attr('data-original');// data-original
            $uri = new Uri($url);
            $url = $uri->getScheme() . '://' . $uri->getHost() . $uri->getPath();
        }
        $name = pathinfo($url, PATHINFO_BASENAME);
        file_put_contents(__DIR__ . '/downloads/yaochufa/images/' . $name, file_get_contents($url));
        echo $name . ' finish' . PHP_EOL;
    });
}