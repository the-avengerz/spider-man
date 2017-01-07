<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

return [
    'images' => [
        'dir' => __DIR__ . '/example/images',
        'index' => XianRouSpiderWeb::class,
        'url' => 'https://api.xianrou.com/user/userinfo/index',
        'method' => 'GET',
        'options' => [
            'query' => [
                'user_id' => 2
            ],
            'headers' => [
                'token' => '8e2f34a64f239ac70a004d921545471a',
            ],
        ]
    ],
    'ha' => [
        'dir' => __DIR__ . '/example/haha',
        'index' => HaSpiderWeb::class,
        'url' => 'http://www.haha.mx/topic/1/new/',
        'method' => 'GET',
        'options' => [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1'
            ],
            'download_path' => __DIR__ . '/downloads',
        ]
    ]
];