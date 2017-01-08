<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

return [
    /*'ha' => [
        'index' => new \haha\HaSpiderWeb('GET', 'http://www.haha.mx/topic/1/new/'),
        'options' => [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1'
            ],
            'download_path' => __DIR__ . '/downloads',
        ]
    ],
    'you' => [
        'index' => new \youguo\YouSpiderWeb('GET', 'http://m.ugirls.com/'),
        'options' => [
            'download_path' => __DIR__ . '/downloads/you',
        ]
    ],
    'hz' => [
        'index' => new \hz\HaiZhuSpiderWeb('POST', 'http://haizhu.gov.cn:8080/haizhuhome/peopleService/serviceOrgList'),
        'options' => [
            'download_path' => __DIR__ . '/downloads'
        ]
    ],*/
    'weibo' => [
        'index' => new \weibo\WeiBoPipeline('GET', 'http://m.weibo.cn/container/getIndex?type=uid&value=5143249142&containerid=1076035143249142'),
        'options' => [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1',
                'X-Requested-With' => 'XMLHttpRequest',
                'Referer' => 'http://m.weibo.cn/u/5143249142',
                'Host' => 'm.weibo.cn'
            ],
        ],
        'download_path' => __DIR__ . '/downloads/weibo'
    ]
];