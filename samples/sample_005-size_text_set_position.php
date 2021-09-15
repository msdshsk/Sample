<?php

require_once '../src/Shsk/Autoload.php';
Shsk\Autoload::register();

use Shsk\Sample\Creator;

function sample_005()
{
    // 画像を読み込む
    $creator = Creator::createFromImage('src/ANJ3P2A3559_TP_V4.jpg');
    // テキストを設定（フォントサイズは自動的に決定されるので設定をしない）
    $creator->setText('サンプル画像００５', realpath('fonts/Mplus2-Medium.otf'), 6);
    // 画像サイズを表す{width} x {height}のテキストを設定する
    $creator->setImageSizeText();
    // 画像サイズを表すテキストの表示位置を設定
    $creator->setImageSizeTextPosition('rightBottom');

    // テキスト設定前に処理を追加する
    $creator->before(function ($controller) {
        // 指定した横幅にリサイズする
        return $controller->resize(['width' => 400]);
    });
    // 処理実行
    $creator->execute();
    // 画像形式を変換して保存する
    $creator->save('results/sample005-size_text/sample_005.png', true);
}
