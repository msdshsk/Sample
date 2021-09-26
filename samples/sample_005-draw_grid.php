<?php

require_once '../src/Shsk/Autoloader.php';
new Shsk\Autoloader();

use Shsk\Sample\Creator;
use Shsk\Image\Color as Color;

function sample_005()
{
    // 画像を読み込む
    $creator = Creator::createFromImage('src/ishinageport_TP_V4.jpg');
    // テキストを設定（フォントサイズは自動的に決定されるので設定をしない）
    $creator->setText('サンプル画像００５', realpath('fonts/Mplus2-Medium.otf'), 0);
    // 画像サイズを表す{width} x {height}のテキストを設定する
    $creator->setImageSizeText();

    // テキスト設定前に処理を追加する
    $creator->before(function ($controller) {
        // 指定した横幅にリサイズする
        $resized = $controller->resize(['width' => 600]);

        // 方眼を書き込む
        $resized->drawGrid(20, new Color(0, 0, 0));

        return $resized;
    });
    // 処理実行
    $creator->execute();
    // 画像形式を変換して保存する
    $creator->save('results/sample005-draw_grid/sample_005.png', true);
}
