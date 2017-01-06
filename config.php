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
        'dir' => './example/images',
        'index' => XianRouSpiderWeb::class,
        'request' => [
            'url' => 'https://api.xianrou.com/user/userinfo/index',
            'args' => [
                'user_id' => 2
            ],
            'headers' => [
                'token' => '8e2f34a64f239ac70a004d921545471a',
            ],
            'method' => 'GET'
        ]
    ]
];