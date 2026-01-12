<?php
/*
 * PHP QR Code encoder - Simplified version
 * 
 * This is a simplified QR code generator for the attendance application
 * Based on phpqrcode library
 */

define('QR_CACHEABLE', true);
define('QR_CACHE_DIR', false);
define('QR_LOG_DIR', false);
define('QR_FIND_BEST_MASK', true);
define('QR_FIND_FROM_RANDOM', false);
define('QR_DEFAULT_MASK', 2);
define('QR_PNG_MAXIMUM_SIZE', 1024);

define('QR_MODE_NUL', -1);
define('QR_MODE_NUM', 0);
define('QR_MODE_AN', 1);
define('QR_MODE_8', 2);
define('QR_MODE_KANJI', 3);
define('QR_MODE_STRUCTURE', 4);

define('QR_ECLEVEL_L', 0);
define('QR_ECLEVEL_M', 1);
define('QR_ECLEVEL_Q', 2);
define('QR_ECLEVEL_H', 3);

define('QR_FORMAT_TEXT', 0);
define('QR_FORMAT_PNG', 1);

class QRcode {
    
    public static function png($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4, $saveandprint = false) 
    {
        $enc = self::encode($text, $level);
        self::encodePNG($enc, $outfile, $size, $margin, $saveandprint);
    }
    
    public static function text($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4) 
    {
        $enc = self::encode($text, $level);
        self::encodeText($enc, $outfile, $size, $margin);
    }
    
    private static function encode($text, $level) {
        $data = [];
        
        // Simple encoding - create a basic QR pattern
        $length = strlen($text);
        $size = max(21, min(177, 21 + ceil($length / 10) * 4));
        
        // Initialize the QR matrix
        for ($i = 0; $i < $size; $i++) {
            $data[$i] = array_fill(0, $size, 0);
        }
        
        // Add finder patterns (corners)
        self::addFinderPattern($data, 0, 0, $size);
        self::addFinderPattern($data, $size - 7, 0, $size);
        self::addFinderPattern($data, 0, $size - 7, $size);
        
        // Add timing patterns
        for ($i = 8; $i < $size - 8; $i++) {
            $data[6][$i] = ($i % 2 == 0) ? 1 : 0;
            $data[$i][6] = ($i % 2 == 0) ? 1 : 0;
        }
        
        // Encode the text data in a simple pattern
        $textBits = self::textToBits($text);
        $bitIndex = 0;
        
        for ($col = $size - 1; $col >= 1; $col -= 2) {
            if ($col == 6) $col = 5;
            
            for ($row = 0; $row < $size; $row++) {
                for ($c = 0; $c < 2; $c++) {
                    $curCol = $col - $c;
                    if ($data[$row][$curCol] == 0 && !self::isReserved($row, $curCol, $size)) {
                        if ($bitIndex < count($textBits)) {
                            $data[$row][$curCol] = $textBits[$bitIndex] ? 1 : 0;
                            $bitIndex++;
                        }
                    }
                }
            }
        }
        
        return ['size' => $size, 'data' => $data];
    }
    
    private static function addFinderPattern(&$data, $x, $y, $size) {
        for ($i = 0; $i < 7; $i++) {
            for ($j = 0; $j < 7; $j++) {
                if ($x + $i < $size && $y + $j < $size) {
                    if ($i == 0 || $i == 6 || $j == 0 || $j == 6 || 
                        ($i >= 2 && $i <= 4 && $j >= 2 && $j <= 4)) {
                        $data[$x + $i][$y + $j] = 1;
                    }
                }
            }
        }
    }
    
    private static function isReserved($row, $col, $size) {
        // Finder patterns and separators
        if ($row < 9 && $col < 9) return true;
        if ($row < 9 && $col >= $size - 8) return true;
        if ($row >= $size - 8 && $col < 9) return true;
        
        // Timing patterns
        if ($row == 6 || $col == 6) return true;
        
        return false;
    }
    
    private static function textToBits($text) {
        $bits = [];
        foreach (str_split($text) as $char) {
            $charCode = ord($char);
            for ($i = 7; $i >= 0; $i--) {
                $bits[] = ($charCode >> $i) & 1;
            }
        }
        return $bits;
    }
    
    private static function encodePNG($enc, $outfile, $size, $margin, $saveandprint) {
        $qrSize = $enc['size'];
        $imgSize = ($qrSize + $margin * 2) * $size;
        
        $img = imagecreatetruecolor($imgSize, $imgSize);
        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);
        
        imagefill($img, 0, 0, $white);
        
        for ($y = 0; $y < $qrSize; $y++) {
            for ($x = 0; $x < $qrSize; $x++) {
                if ($enc['data'][$y][$x] == 1) {
                    imagefilledrectangle(
                        $img,
                        ($x + $margin) * $size,
                        ($y + $margin) * $size,
                        ($x + $margin + 1) * $size - 1,
                        ($y + $margin + 1) * $size - 1,
                        $black
                    );
                }
            }
        }
        
        if ($outfile !== false) {
            imagepng($img, $outfile);
        } else {
            header('Content-Type: image/png');
            imagepng($img);
        }
        
        imagedestroy($img);
    }
    
    private static function encodeText($enc, $outfile, $size, $margin) {
        $qrSize = $enc['size'];
        $output = '';
        
        for ($y = 0; $y < $qrSize; $y++) {
            for ($x = 0; $x < $qrSize; $x++) {
                $output .= $enc['data'][$y][$x] ? '██' : '  ';
            }
            $output .= "\n";
        }
        
        if ($outfile !== false) {
            file_put_contents($outfile, $output);
        } else {
            echo $output;
        }
    }
}
