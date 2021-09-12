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

