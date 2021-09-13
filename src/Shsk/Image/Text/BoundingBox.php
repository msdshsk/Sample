<?php

namespace Shsk\Image\Text;

use Shsk\Exception\Exception;
use Shsk\Property\ReadOnly;
use Shsk\Image\Text;

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

    public function fit($width, $height): BoundingBox
    {
        $box = $this;
        $fontSize = $this->info->fontSize;

        $defaultSize = $this->create();

        if ($defaultSize->width < $width && $defaultSize->height < $height) {
            $increment = 1;
        } else {
            $increment = -1;
        }

        $class = $this::class;
        $candidate = $box;
        $loop = true;
        do {
            $box = new $class($this->info->changeFontSize($fontSize));
            $size = $box->create();

            $isOver = $size->width < $width && $size->height < $height;
            if ($isOver && $increment === 1) {
                $candidate = $box;
            } elseif (!$isOver && $increment === -1) {
                $candidate = $box;
            } else {
                $loop = false;
            }

            $fontSize += $increment;
        } while ($loop);

        return $candidate;
    }
}
