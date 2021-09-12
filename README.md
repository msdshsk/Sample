# サンプルイメージ生成用ライブラリ

## 概要
GDで各種サイズのサンプルイメージを簡単に生成するためのライブラリです。

## サンプルコード

### 簡単な例
```php
use Sample\Creator;
use Sample\Image\Text;

$text = new Text('Hello World', 16, 'C:\\Windows\\Fonts\\meiryo.ttc', 6);
$creator = Creator::create(400, 400);
$creator->setText($text);
$creator->execute();
$creator->save('sample.png');
```

#### 結果

![sample](https://user-images.githubusercontent.com/625393/132983364-77962722-7d01-47a3-a105-fb83b401bb19.png)

### 背景色、テキスト色を設定
```php
use Sample\Creator;
use Sample\Image\Text;

$text = new Text('サンプル画像その２', 24, 'C:/Windows/Fonts/Mplus2-Medium.otf', 6);
// 画像サイズを250 x 200で作成
$creator = Creator::create(250, 200);
$creator->setText($text);

// 背景色の設定
$creator->setBackgroundColor(255, 0, 0);

// テキスト色を設定
$creator->setTextColor(255, 255, 255);

$creator->execute();
$creator->save('sample02.png');
```

### 結果

![sample02](https://user-images.githubusercontent.com/625393/132983799-a452eca8-43be-4a15-85ae-2fd5b92edb77.png)