<?php

use Illuminate\Support\Facades\Route;
use DOMWrap\Document;
use HeadlessChromium\BrowserFactory;
use GuzzleHttp\Client;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

// ここに書かれている URL はダミーです
// クロールリストは実際にはデータベースから取ってくるとか
$sites = [
    [
        'url' => 'https://books.rakuten.co.jp/search?g=101&merch=94142&v=1&s=1&spv=2&h=100&l-id=ebook-event-all-8949-11',
        'selector' => '.rbcomp__item-tile__list > li > div > h3 > a > span',
    ],
];

foreach ($sites as $site) {
    $browserFactory = new BrowserFactory(
        // 実行するブラウザのパスに応じて変更
        // https://github.com/chrome-php/headless-chromium-php/issues/75
        //'/Applications/Google\ Chrome.app/Contents/MacOS/Google\ Chrome'
        'chromium-browser'
    );
    $browser = $browserFactory->createBrowser();
    $page = $browser->createPage();
    $page->navigate($site['url'])->waitForNavigation();
    $evaluation = $page->evaluate('document.documentElement.innerHTML');
    $value = $evaluation->getReturnValue();
    $browser->close();

    $doc = new Document;
    $node = $doc->html($value);

    // 取得した文字列
    $text = $node->find($site['selector'])->text();

    // 通知したりデータベースに追加したり
    echo $text, PHP_EOL;
}

//$sites = [
//    [
//        'url' => 'https://books.rakuten.co.jp/search?g=101&merch=94142&v=1&s=1&spv=2&h=100&l-id=ebook-event-all-8949-11',
//        'selector' => '.rbcomp__item-tile__list > li > div > h3 > a > span',
//    ],
//];

//$client = new Client;

//foreach ($sites as $site) {
//    $response = $client->get($site['url']);
//    $html = (string) $response->getBody();
//    $doc = new Document;
//    $node = $doc->html($html);

    // 取得した文字列
//    $text = $node->find($site['selector'])->text();

    // 通知したりデータベースに追加したり
//    echo $text, PHP_EOL;
//}
