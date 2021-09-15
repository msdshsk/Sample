<?php

require_once '../src/Shsk/Autoload.php';
Shsk\Autoload::register();

use Shsk\Sample\Creator;

// Textオブジェクトを読み込む
use Shsk\Image\Text;

function sample_002()
{
    // テキストを作成する（フォントサイズは8pt）
    $text = new Text('サンプル画像００１', 8, realpath('fonts/Mplus2-Medium.otf'), 6);

    // 画像を読み込む
    $creator = Creator::createFromImage('src/050AME0226_TP_V4.jpg');
    // テキストを設定
    $creator->setText($text);
    // テキスト設定前に処理を追加する
    $creator->before(function ($controller) {
        // 指定した横幅にリサイズする
        return $controller->resize(['width' => 300]);
    });
    // 処理実行
    $creator->execute();
    // 画像形式を変換して保存する
    $creator->save('results/sample002-fix_fontsize/sample_002.png', true);
}
