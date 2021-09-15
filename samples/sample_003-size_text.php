<?php

require_once '../src/Shsk/Autoload.php';
Shsk\Autoload::register();

use Shsk\Sample\Creator;

function sample_003()
{
    // 画像を読み込む
    $creator = Creator::createFromImage('src/050AME0226_TP_V4.jpg');
    // テキストを設定（フォントサイズは自動的に決定されるので設定をしない）
    $creator->setText('サンプル画像００３', realpath('fonts/Mplus2-Medium.otf'), 6);
    // 画像サイズを表す{width} x {height}のテキストを設定する
    $creator->setImageSizeText();

    // テキスト設定前に処理を追加する
    $creator->before(function ($controller) {
        // 指定した横幅にリサイズする
        return $controller->resize(['width' => 300]);
    });
    // 処理実行
    $creator->execute();
    // 画像形式を変換して保存する
    $creator->save('results/sample003-size_text/sample_003.png', true);
}
