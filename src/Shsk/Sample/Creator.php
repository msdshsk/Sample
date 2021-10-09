<?php

namespace Shsk\Sample;

use Shsk\Coordinate\Calculator;
use Shsk\Image\Controller;
use Shsk\Image\Color;
use Shsk\Image\Text;
use Shsk\Property\Size;
use Shsk\Property\Coordinate;
use Shsk\Exception\Exception;
use Shsk\Image\Color\Picker;
use Shsk\FileSystem\Directory;

class Creator
{
    private $im;
    private $textColor;
    private $text;
    private $fitText = true;
    private $callbacks = [];
    private $imageSizeTextPosition = 'leftTop';
    private $imageSizeText;
    private $imageSizeTextSize;

    private function __construct(Controller $ctrl)
    {
        $this->controller = $ctrl;
        $this->imageSizePosition = new Coordinate(3, 3);
    }

    public static function create($width, $height, Color $baseColor = null)
    {
        if ($baseColor === null) {
            $baseColor = new Color(200, 200, 200);
        }
        $ctrl = Controller::create(new Size($width, $height), $baseColor);
        return new self($ctrl, true);
    }

    public static function createFromImage(string $path)
    {
        $ctrl = Controller::fromImage($path);
        return new self($ctrl, false);
    }

    public function setText($text, $fontPath = null, $angle = 0): Creator
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

    public function setImageSizeTextPosition($x, int $y = null)
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

    public function setBackgroundColor($r, $g = null, $b = null, $a = 0): Creator
    {
        if ($r instanceof Color) {
            $this->backgroundColor = $r;
        } else {
            $this->backgroundColor = new Color($r, $g, $b, $a);
        }
        return $this;
    }

    public function setTextColor($r, $g = null, $b = null, $a = 0): Creator
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
        
        $this->controller->save($savePath);

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

        if ($this->text !== null) {
            if ($this->fitText === true) {
                $newText = $this->text->fit($ctrl->size());
            }

            $bbox = $newText->boundingBox();
            $freeTextFit = $bbox->coordinator($width, $height)->center();

            if (!($this->textColor instanceof Color)) {
                $picker = new Picker();
                $textColor = $picker->pick($ctrl, $newText, $freeTextFit);
            } else {
                $textColor = $this->textColor;
            }

            // $ctrl->writeText($newText, $freeTextFit, $textColor);
            $ctrl->text($newText)->writePos(Calculator::CENTER, $textColor);
        }

        if ($this->imageSizeText !== null) {
            $imW = $width * $this->imageSizeTextSize;
            $imH = $height * $this->imageSizeTextSize;
            $imText = $this->imageSizeText->withText("{$width} x {$height}");
            $imNewText = $imText->fit(new Size($imW, $imH));
            $imBox = $imNewText->boundingBox();
            if (is_string($this->imageSizeTextPosition)) {
                $imSizeFit = $imBox->coordinator($width, $height)->fromString($this->imageSizeTextPosition);
            } elseif ($this->imageSizeTextPosition instanceof Coordinate) {
                $imSizeFit = $imBox->coordinator($width, $height)->fromCoordinate($this->imageSizeTextPosition);
            }
            

            $picker = new Picker();
            $imColor = $picker->pick($ctrl, $imNewText, $imSizeFit);

            //$imNewPos = new Coordinate($imSizeFit->x, $imSizeFit->y);
            //$ctrl->writeText($imNewText, $imNewPos, $imColor);
            $ctrl->text($imNewText)->write($imSizeFit, $imColor);
        }
        
        $ctrl = $this->executeCallback('after');

        return $this;
    }
}
