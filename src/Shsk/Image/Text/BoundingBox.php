<?php

namespace Shsk\Image\Text;

use Shsk\Property\Size;
use Shsk\Property\Coordinate;
use Shsk\Property\ReadOnlyProperty;

class BoundingBox extends Size
{
    public function __construct($box)
    {
        $ary = $this->init($box);
        $this->setProperties($ary);
    }

    private function init($box): array
    {
        $lftpx = $box[6];
        $lftpy = $box[7];
        $lfbmx = $box[0];
        $lfbmy = $box[1];
        $rttpx = $box[4];
        $rttpy = $box[5];
        $rtbmx = $box[2];
        $rtbmy = $box[3];
        $lftp = new Coordinate($lftpx, $lftpy);
        $lfbm = new Coordinate($lfbmx, $lfbmy);
        $rttp = new Coordinate($rttpx, $rttpy);
        $rtbm = new Coordinate($rtbmx, $rtbmy);
        $lf = new ReadOnlyProperty(['top' => $lftp, 'bottom' => $lfbm]);
        $rt = new ReadOnlyProperty(['top' => $rttp, 'bottom' => $rtbm]);

        if ($lfbmy < $rtbmy) {
            $max_y = $rtbmy;
        } else {
            $max_y = $lfbmy;
        }

        if ($lftpy < $rttpy) {
            $min_y = $lftpy;
        } else {
            $min_y = $rttpy;
        }

        if ($rttpx < $rtbmx) {
            $max_x = $rtbmx;
        } else {
            $max_x = $rttpx;
        }

        if ($lftpx < $lfbmx) {
            $min_x = $lftpx;
        } else {
            $min_x = $lfbmx;
        }

        if ($lfbmy < $rtbmy) {
            $baseline = $rtbmy - 1;
        } else {
            $baseline = $lfbmy - 1;
        }

        if ($lftpx < $lfbmx) {
            $spacing = $lftpx;
        } else {
            $spacing = $lfbmx;
        }

        return [
            'left' => $lf,
            'right' => $rt,
            'width' => $max_x - $min_x,
            'height' => $max_y - $min_y,
            'baseline' => $baseline,
            'spacing' => $spacing,
        ];
    }

    public function coordinator($width = null, $height = null): Coordinator
    {
        return new Coordinator($this, $width, $height);
    }

    public function size(): Size
    {
        return new Size($this->width, $this->height);
    }
}
