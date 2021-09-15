<?php

require_once '../src/Shsk/Autoload.php';
Shsk\Autoload::register();

use Shsk\Sample\Creator;

function sample_001()
{
    // 画像を読み込む
    $creator = Creator::createFromImage('src/050AME0226_TP_V4.jpg');
    // テキストを設定（フォントサイズは自動的に決定されるので設定をしない）
    $creator->setText('サンプル画像００１', realpath('fonts/Mplus2-Medium.otf'), 6);
    // テキスト設定前に処理を追加する
    $creator->before(function ($controller) {
        // 指定した横幅にリサイズする
        return $controller->resize(['width' => 300]);
    });
    // 処理実行
    $creator->execute();
    // 画像形式を変換して保存する
    $creator->save('results/sample001-simple/sample_001.png', true);
}
