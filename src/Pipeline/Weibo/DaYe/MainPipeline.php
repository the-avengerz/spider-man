<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 17-2-9 14:48
 */
namespace Pipeline\Weibo\DaYe;
use GuzzleHttp\Exception\RequestException;
use Pipeline;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

class MainPipeline extends Pipeline
{

    /**
     * @param Crawler $crawler
     * @param ResponseInterface $response
     * @return mixed
     */
    public function process(Crawler $crawler = null, ResponseInterface $response = null)
    {
        $list = json_decode($response->getBody()->__toString(), true);
        if (!isset($list['cards'])) {
            return '';
        }

        foreach ($list['cards'] as $card) {
            if (!isset($card['mblog']['pics']) || !$card['mblog']['pics']) {
                continue;
            }
            foreach ($card['mblog']['pics'] as $pic) {
                if (false !== strpos('ssl.', $pic['url'])) {
                    continue;
                }
                copy($pic['url'], $this->options['download_path'] . basename($pic['url']));
            }
        }

        if (count($list['cards']) > 9) {
            $queryString = $this->uri->getQuery();
            parse_str($queryString, $queryString);
            ++$queryString['page'];
            $this->wait(new self('GET', $this->uri->withQuery(http_build_query($queryString))));
        }
    }

    /**
     * @param RequestException $exception
     * @return mixed
     */
    public function error(RequestException $exception)
    {
        // TODO: Implement error() method.
    }
}