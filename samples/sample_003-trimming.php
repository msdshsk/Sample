<?php

require_once '../src/Shsk/Autoload.php';
Shsk\Autoload::register();

use Shsk\Sample\Creator;

use Shsk\Property\Size;
use Shsk\Property\Coordinate;

// 画像を読み込む
$creator = Creator::createFromImage('src/yukachi0I9A5356_TP_V4.jpg');
// テキストを設定（フォントサイズは自動的に決定されるので設定をしない）
$creator->setText('サンプル画像００３（トリミング）', realpath('fonts/Mplus2-Medium.otf'), 10);
// テキスト設定前に処理を追加する
$creator->before(function ($controller) {
    // 指定した横幅にリサイズする
    $resized = $controller->resize(['width' => 400]);
    // 切り取るサイズを設定
    $trimSize = new Size(400, 400);
    // 切り取る左上のXY座標を設定
    $trimPos = new Coordinate(0, 0);
    // トリミングする
    return $resized->trimming($trimSize, $trimPos);
});
// 処理実行
$creator->execute();
// 画像形式を変換して保存する
$creator->save('dist/sample003-trimming/sample_003.png');