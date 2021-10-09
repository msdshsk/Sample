<?php

namespace Shsk\Property;

class Size extends ReadOnly
{
    public function __construct(int $width, int $height)
    {
        parent::__construct(compact('width', 'height'));
    }

    /**
     * 縦横のうち長い方のサイズを返す
     *
     * @return integer
     */
    public function max(): int
    {
        return $this->width > $this->height ? $this->width : $this->height;
    }

    /**
     * 縦横のうち、短い方のサイズを返す
     *
     * @return integer
     */
    public function min(): int
    {
        return $this->width < $this->height ? $this->width : $this->height;
    }

    /**
     * 縦長の画像か真偽を返す
     *
     * @return boolean
     */
    public function portrait(): bool
    {
        return $this->height > $this->width;
    }

    /**
     * 横長の画像か真偽を返す
     *
     * @return boolean
     */
    public function landscape(): bool
    {
        return $this->width > $this->height;
    }

    public function expand($ratio)
    {
        return new Size($this->width * $ratio, $this->height * $ratio);
    }

    public function expandPixcel($width, $height)
    {
        $width = $this->width + ($width * 2);
        $height = $this->height + ($height * 2);
        return new Size($width, $height);
    }
}
