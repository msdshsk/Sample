<?php

require_once '../src/Shsk/Autoloader.php';
new Shsk\Autoloader();

use Shsk\Sample\Creator;

function sample_006()
{
    // 画像を読み込む
    $creator = Creator::createFromImage('src/yukicyan0I9A4348_TP_V4.jpg');
    // テキストを設定（フォントサイズは自動的に決定されるので設定をしない）
    $creator->setText('サンプル画像００６', realpath('fonts/Mplus2-Medium.otf'), 0);
    // 画像サイズを表す{width} x {height}のテキストを設定する
    $creator->setImageSizeText();

    // テキスト設定前に処理を追加する
    $creator->before(function ($controller) {
        // 正方形にする
        $squared = $controller->square();

        return $squared;
    });
    // 処理実行
    $creator->execute();
    // 画像形式を変換して保存する
    $creator->save('results/sample006-square/sample_006.png', true);
}
