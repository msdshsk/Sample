<?php

namespace Sample;

use Sample\Image\Creator\GD\TrueColor;
use Sample\Image\Reader\Factory\GD as ReaderFactory;
use Sample\Image\Writer\Factory\GD as WriterFactory;
use Sample\Image\Controller\GD as Controller;
use Sample\Color\RGB as Color;
use Sample\Image\Text\BoundingBox\TTF as BBox;
use Sample\Image\Text;
use Sample\Image\Controller\Config\Resize as ResizeConfig;
use Sample\Property\Size;
use Sample\Property\Coordinate;
use Sample\Exception\Exception;
use Sample\Color\Picker;

class Creator
{
    private $im;
    private $backgroundColor;
    private $textColor;
    private $text;
    private $isFill;
    private $callbacks = [];
    private $imageSizePosition;
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

    public static function createFromImg(string $path)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        
        $factory = new ReaderFactory($ext);
        $reader = $factory->create();
        $ctrl = $reader->create($path);
        return new self($ctrl, false);
    }

    public function setText(Text $text): Creator
    {
        $this->text = $text;
        return $this;
    }

    public function setImageSizeTextPosition(int|Coordinate $x, int $y = null)
    {
        if ($x instanceof Coordinate) {
            $this->imageSizePosition = $x;
        } else {
            $this->imageSizePosition = new Coordinate($x, $y);
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

    public function save($savePath)
    {
        $ctrl = $this->controller;
        $writer = $ctrl->createWriter($savePath);
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
            $rbox = $bbox->resize($width, $height);

            $size = $rbox->create();
            $freeTextFit = $size->fit($width, $height)->center();

            if (!($this->textColor instanceof Text)) {
                $picker = Picker::createFromController($ctrl);
                $picker->setTextSize($size->size(), $freeTextFit);
                $textColor = $picker->createColor();
                unset($picker);
            } else {
                $textColor = $this->textColor;
            }

            $imPt = 100;
            $imW = $width / 3;
            $imH = $height / 3;
            $imSizeText = "{$width} x {$height}";
            $imText = new Text($imSizeText, $imPt, $this->text->fontPath, 0);
            $imBBox = new BBox($imText);
            $imRBox = $imBBox->resize($imW, $imH);
            $imSize = $imRBox->create();
            $imSizeFit = $imSize->fit($imW, $imH)->leftTop();
            $pos = $this->imageSizePosition;

            $imNewPos = new Coordinate($imSizeFit->x + $pos->x, $imSizeFit->y + $pos->y);

            $ctrl->writeText($rbox->info, $freeTextFit, $textColor);
            $ctrl->writeText($imRBox->info, $imNewPos, $textColor);
        }
        
        $ctrl = $this->executeCallback('after');

        return $this;
    }
}
