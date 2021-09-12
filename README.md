# サンプルイメージ生成用ライブラリ

## 概要
GDで各種サイズのサンプルイメージを簡単に生成するためのライブラリです。

## サンプルコード

### 1. 簡単な例
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

### 2. 背景色、テキスト色を設定
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


### 2. 既存の画像をリサイズする
```php
use Sample\Creator;
use Sample\Image\Text;
use Sample\Image\Controller\Config\Resize as ResizeConfig;

$text = new Text('サンプル画像その３', 32, 'C:/Windows/Fonts/Mplus2-Medium.otf', 6);
// 既存の画像を読み込む
$creator = Creator::createFromImg('SAYAPAKU5347_TP_V4.jpg');
$creator->setText($text);

// テキスト色を設定
$creator->setTextColor(200, 200, 200);

// 実行前に処理を追加する場合
$creator->before(function ($controller) {
    // 1/2の大きさに変換する
    return $controller->resize(new ResizeConfig(['parsent' => 0.5]));
});

$creator->execute();
$creator->save('sample03.jpg');
```
### 結果

![sample03](https://user-images.githubusercontent.com/625393/132984657-43e7faaa-3458-4098-b066-c35d7d4c4311.jpg)

### 3. なんかもう色々やる

```php
use Sample\Creator;
use Sample\Image\Text;
use Sample\Image\Controller\Config\Resize as ResizeConfig;
use Sample\FileSystem\Directory;
use Sample\Color\Picker as ColorPicker;
use Sample\Coordinate\Calculator;
use Sample\Property\Size;
use Sample\Color\RGB as Color;

// ディレクトリを読み込む
$srcDir = new Directory('src_images');

// 保存フォルダ
$saveDir = new Directory('images');

$i = 1;
// ディレクトリ内のファイルを全てサンプル画像にする
foreach ($srcDir->searchFiles('/\.(?:png|jpg|webp)/i') as $filePath) {
    $line = sprintf('サンプル画像 %03s', $i);
    $text = new Text($line, 72, 'C:/Windows/Fonts/Mplus2-Medium.otf', 10);
    $creator = Creator::createFromImg($filePath)
        ->setText($text)
        ->before(function ($ctrl) use ($filePath) {
            // 既存画像のサイズを取得
            $size = $ctrl->size();
            // 幅、高さの小さい方のサイズを取得
            $s = $size->min();
            // サイズを決定する
            $trimSize = new Size($s, $s);
            
            // 画像の真ん中を切り取る
            $calc = new Calculator($size, $trimSize);
            $trimmed = $ctrl->trimming($trimSize, $calc->center());
            // さらに画像をリサイズしてすべての画像が同じ大きさになるようにする
            $resized = $trimmed->resize(new ResizeConfig(['size' => new Size(200, 200)]));

            unset($ctrl);
            unset($trimmed);

            // 画像に方眼を書き込む
            $resized->drawGrid(50, new Color(200, 200, 200));
            // 画像に枠線を書き込む
            $resized->drawOutline(2, new Color(255, 0, 0));

            return $resized;
        })
        // 画像サイズテキスト（左上に表示される）を調整
        ->setImageSizeTextPosition(10, 10)
        // 処理を実行
        ->execute()
        // 画像を保存する
        ->save($saveDir->path(sprintf('image_%03s.png', $i)))
    ;
    // 作成完了したので破棄する
    $creator->destroy();
    $i++;
}
```

### 結果

自分の目でたしかめてくれよな！