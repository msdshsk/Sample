# サンプルイメージ生成用ライブラリ

## 概要
GDで各種サイズのサンプルイメージを簡単に生成するためのライブラリです。

## サンプルコード
```php
use Sample\Creator;
use Sample\Image\Text;

$text = new Text('Hello World', 16, 'C:\\Windows\\Fonts\\meiryo.ttc', 6);
$creator = Creator::create(400, 400);
$creator->setText($text);
$creator->execute();
$creator->save('sample.png');
```