<?php

namespace Shsk\Sample;

use Shsk\Image\Creator\GD\TrueColor;
use Shsk\Image\Reader\Factory\GD as ReaderFactory;
use Shsk\Image\Writer\Factory\GD as WriterFactory;
use Shsk\Image\Controller\GD as Controller;
use Shsk\Color\RGB as Color;
use Shsk\Image\Text\BoundingBox\TTF as BBox;
use Shsk\Image\Text;
use Shsk\Image\Controller\Config\Resize as ResizeConfig;
use Shsk\Property\Size;
use Shsk\Property\Coordinate;
use Shsk\Exception\Exception;
use Shsk\Color\Picker;
use Shsk\FileSystem\Directory;

class Creator
{
    private $im;
    private $backgroundColor;
    private $textColor;
    private $text;
    private $fitText = true;
    private $isFill;
    private $callbacks = [];
    private $imageSizeTextPosition = 'leftTop';
    private $imageSizeText;
    private $imageSizeTextSize;
    private $imagePath;

    private function __construct(Controller $ctrl, bool $fill)
    {
        $this->controller = $ctrl;
        $this->isFill = $fill;
        $this->backgroundColor = new Color(200, 200, 200);
        $this->imageSizePosition = new Coordinate(3, 3);
        $this->imagePath = $ctrl->path();
    }

    public static function create($width, $height)
    {
        $creator = new TrueColor($width, $height);
        $ctrl = $creator->create();
        return new self($ctrl, true);
    }

    public static function createFromImage(string $path)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        
        $factory = new ReaderFactory($ext);
        $reader = $factory->create();
        $ctrl = $reader->create($path);
        return new self($ctrl, false);
    }

    public function setText(Text|string $text, $fontPath = null, $angle = 0): Creator
    {
        if ($text instanceof Text) {
            $this->text = $text;
            $this->fitText = false;
        } else {
            $this->text = new Text($text, 8, $fontPath, $angle);
            $this->fitText = true;
        }

        return $this;
    }

    public function setImageSizeTextPosition(int|Coordinate|string $x, int $y = null)
    {
        if ($x instanceof Coordinate) {
            $this->imageSizeTextPosition = $x;
        } elseif (is_int($x) && is_int($y)) {
            $this->imageSizeTextPosition = new Coordinate($x, $y);
        } elseif (is_string($x)) {
            $this->imageSizeTextPosition = $x;
        } else {
            throw new Exception("please set x and y");
        }

        return $this;
    }

    public function setImageSizeText(string $fontPath = null, float $parcent = 0.3): Creator
    {
        if ($fontPath === null && $this->text instanceof Text) {
            $this->imageSizeText = new Text('', 8, $this->text->fontPath, 0);
        } elseif (is_string($fontPath)) {
            $this->imageSizeText = new Text('', 8, $fontPath, 0);
        } else {
            throw new Exception("If don't set text, set the fontPath to setImageSizeText");
        }
        $this->imageSizeTextSize = $parcent;
        if ($this->imageSizeTextPosition === null) {
            $this->setImageSizeTextPosition(new Coordinate(8, 8));
        }

        return $this;
    }

    public function setBackgroundColor(Color|int $r, $g = null, $b = null, $a = 0): Creator
    {
        if ($r instanceof Color) {
            $this->backgroundColor = $r;
        } else {
            $this->backgroundColor = new Color($r, $g, $b, $a);
        }
        return $this;
    }

    public function setTextColor(Color|int $r, $g = null, $b = null, $a = 0): Creator
    {
        if ($r instanceof Color) {
            $this->textColor = $r;
        } else {
            $this->textColor = new Color($r, $g, $b, $a);
        }

        return $this;
    }

    public function save($savePath, $autoMakeDir = false)
    {
        $ctrl = $this->controller;
        $writer = $ctrl->createWriter($savePath);

        if ($autoMakeDir === true) {
            $dirname = dirname($savePath);
            $basename = basename($savePath);
            if ($dirname === '.') {
                $dirname = __DIR__;
            }
            $dir = new Directory($dirname);
            $dir->make();
            $savePath = $dir->path($basename);
        }


        $writer->saveAs($savePath);

        return $this;
    }

    public function before(callable $callback)
    {
        $this->callbacks['before'] = $callback;

        return $this;
    }

    public function after(callable $callback)
    {
        $this->callbacks['after'] = $callback;

        return $this;
    }

    private function executeCallback($type)
    {
        if (isset($this->callbacks[$type])) {
            $ctrl = $this->callbacks[$type]($this->controller);
            if (!($ctrl instanceof Controller)) {
                throw new Exception("callback is must return Sample\\Image\\Controller instance");
            }
            $this->controller = $ctrl;
        }
        return $this->controller;
    }

    public function destroy()
    {
        $this->controller->destroy();
    }

    public function __destruct()
    {
        $this->destroy();
    }

    public function execute()
    {
        $ctrl = $this->executeCallback('before');

        $width = $ctrl->width();
        $height = $ctrl->height();

        if ($this->isFill) {
            $ctrl->fill(0, 0, $this->backgroundColor);
        }

        if ($this->text !== null) {
            $bbox = new BBox($this->text);
            if ($this->fitText === true) {
                $bbox = $bbox->fit($width, $height);
            }

            $size = $bbox->create();
            $freeTextFit = $size->fit($width, $height)->center();

            if (!($this->textColor instanceof Color)) {
                $picker = Picker::createFromController($ctrl);
                $picker->setTextSize($size->size(), $freeTextFit);
                $textColor = $picker->createColor();
            } else {
                $textColor = $this->textColor;
            }

            $ctrl->writeText($bbox->info, $freeTextFit, $textColor);
        }

        if ($this->imageSizeText !== null) {
            $imW = $width * $this->imageSizeTextSize;
            $imH = $height * $this->imageSizeTextSize;
            $imText = $this->imageSizeText->changeText("{$width} x {$height}");
            $imBBox = new BBox($imText);
            $imRBox = $imBBox->fit($imW, $imH);
            $imSize = $imRBox->create();
            if (is_string($this->imageSizeTextPosition)) {
                $imSizeFit = $imSize->fit($width, $height)->fromString($this->imageSizeTextPosition);
            } else {
                $imSizeFit = $imSize->fit($width, $height)->fromCoordinate($this->imageSizeTextPosition);
            }
            
            $picker = Picker::createFromController($ctrl);
            $picker->setTextSize($imSize->size(), $imSizeFit);
            $imColor = $picker->createColor();

            $imNewPos = new Coordinate($imSizeFit->x, $imSizeFit->y);
            $ctrl->writeText($imRBox->info, $imNewPos, $imColor);
        }
        
        $ctrl = $this->executeCallback('after');

        return $this;
    }
}
