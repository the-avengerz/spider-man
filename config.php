<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

return [
    'yao' => [
        'index' => new \yaochufa\YaochufaPipeline('GET', 'https://m.yaochufa.com/youji/list?event_time=1484056015&DeviceToken=E1076D4F-2A28-41D9-B58A-0DE05BAA073F&system=ios&appcitycode=440100&userId=0&version=5.5.4'),
        'options' => [
            'download_path' => __DIR__ . '/downloads/yaochufa'
        ],
    ],
    'lw' => [
        'index' => new \yaochufa\lw('GET', 'https://m.yaochufa.com/youji/list'),
        'options' => [
            'download_path' => __DIR__ . '/downloads/yaochufa'
        ],
    ],
    'zy' => [
        'index' => new \zybang\ZyPipeline('GET', 'http://www.zybang.com/'),
        'options' => [
            'download_path' => __DIR__ . '/downloads/zy'
        ],
    ],
    'tj' => [
        'index' => new \tj\tj('GET', 'http://112.124.40.205:55757/'),
        'options' => [
            'download_path' => __DIR__ . '/downloads/tj'
        ],
    ],
    'linghit' => [
        'index' => new \linghit\news('GET', 'http://m.linghit.com/'),
        'options' => [
            'download_path' => __DIR__ . '/downloads/linghit'
        ],
    ],
    'huaban' => [
        'index' => new \huaban\huaban('GET', 'http://huaban.com/favorite/beauty/'),
        'options' => [
            'download_path' => __DIR__ . '/downloads/huaban'
        ],
    ],
];