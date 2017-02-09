<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

use PhpOffice\PhpWord\PhpWord;
use Support\Html;

include __DIR__ . '/vendor/autoload.php';

$db = new medoo([
    'database_type' => 'mysql',
    'database_name' => 'ycf',
    'server' => 'localhost',
    'username' => 'root',
    'password' => '123456',
    'charset' => 'utf8'
]);

$list = $db->query('select * from scenic_spot WHERE status = 0')->fetchAll();

$success = 0;
$fail = 0;
$isStart = true;
foreach ($list as $item) {

    if ($isStart) {
        try {
            $city = $item['city'];
            if (empty($item['city'])) {
                $city = '溜娃';
            }
            $path = __DIR__ . '/downloads/yaochufa/word/' .
                $item['province'] . '/' .
                $city;
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            $docx = $path . '/' . $item['title'] . '.docx';
            if (file_exists($docx)) {
                echo $item['title'] . ' continue ' . PHP_EOL;
                continue;
            }

            $phpWord = new PhpWord();
            $section = $phpWord->addSection(array('align' => 'both', 'spaceAfter' => 100));
            $section->addTitle($item['title'] . PHP_EOL);
            $section->addLine();
            if (!empty($item['detail'])) {
                Html::addHtml($section, '<html><body>' . $item['detail'] . '</body></html>');
                $section = $phpWord->addSection();
                $section->addLine();
            }

            $content = <<<HTML
<html>
<body>
<div>
<ul>
{$item['body']}
</ul>
</div>
</body>
</html>
HTML;

            $content = preg_replace('/<img([^>]+)>/i', '<img$1 />', $content);
            $content = str_replace([
                '<br>'
            ], '<br />', $content);
            $section = $phpWord->addSection();
            Html::addHtml($section, $content);
            $phpWord->save($docx);
            echo $item['title'] . ' finish ' . PHP_EOL;
            $success++;
            $db->update('scenic_spot', ['status' => 1], ['product_id' => $item['product_id']]);
        } catch (Exception $e) {
            echo $e->getFile();
            echo $e->getLine();
            echo $item['title'] . ' error ' . PHP_EOL;
            $fail++;
        }
    }
}

echo 'Success: ' . $success . PHP_EOL;
echo 'Fail: ' . $fail . PHP_EOL;


