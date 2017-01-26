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
    ],
    'weibo_hz' => [
        'index' => new \weibo_hz\WeiBoPipeline('GET', 'http://m.weibo.cn/container/getIndex?type=uid&value=6049220524&containerid=1076036049220524'),
        'options' => [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1',
                'X-Requested-With' => 'XMLHttpRequest',
                'Referer' => 'http://m.weibo.cn/u/6049220524',
                'Host' => 'm.weibo.cn'
            ],
        ],
        'download_path' => __DIR__ . '/downloads/weibo/6049220524'
    ],
    'yao' => [
        'index' => new \yaochufa\YaochufaPipeline('GET', 'https://m.yaochufa.com/youji/list?event_time=1484056015&DeviceToken=E1076D4F-2A28-41D9-B58A-0DE05BAA073F&system=ios&appcitycode=440100&userId=0&version=5.5.4'),
        'options' => [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1',
            ],
            'download_path' => __DIR__ . '/downloads/yaochufa'
        ],
    ],
    'xr' => [
        'index' => new \xianrou\xr('GET', ''),
    ],
    'gzt' => [
        'index' => new \gzt\guangzhoutong('GET', 'http://gzt.unimip.cn/mobile/services/all.do?city=gzt'),
        'options' => [
            'download_path' => __DIR__ . '/downloads/gzt',
        ]
    ]
];