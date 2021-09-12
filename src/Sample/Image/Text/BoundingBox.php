<?php

namespace Sample\Image\Text;

use Sample\Exception\Exception;
use Sample\Property\ReadOnly;
use Sample\Image\Text;

abstract class BoundingBox extends ReadOnly
{
    public function __construct(Text $text)
    {
        parent::__construct([
            'info' => $text,
        ]);
    }

    abstract protected function createBox(int $fontSize, int $angle, string $fontPath, string $text): array;

    public function create(): Size
    {
        $box = $this->createbox(
            $this->info->fontSize,
            $this->info->angle,
            $this->info->fontPath,
            $this->info->text
        );
        return new Size($box);
    }

    public function resize($width, $height): BoundingBox
    {
        $box = $this;

        $fontSize = $this->info->fontSize;
        while (true) {
            $size = $box->create();

            $isW = $size->width < $width;
            $isH = $size->height < $height;

            if ($isW && $isH) {
                return $box;
            }

            $class = $this::class;
            $box = new $class($this->info->changeFontSize(--$fontSize));
            if ($fontSize === 0) {
                throw new Exception("cant resize");
            }
        }
    }
}
