<?php
// Generate PWA icons using GD
function createIcon($size, $filename) {
    $img = imagecreatetruecolor($size, $size);
    $bg = imagecolorallocate($img, 13, 110, 253); // Bootstrap primary blue #0d6efd
    $white = imagecolorallocate($img, 255, 255, 255);
    imagefill($img, 0, 0, $bg);
    
    // Draw a simple "T" or graduation cap shape
    $fontSize = $size * 0.5;
    $x = $size * 0.35;
    $y = $size * 0.75;
    
    // Use built-in font if TTF not available
    $font = 5; // largest built-in font
    $text = 'T';
    
    // Center text approximately for built-in font
    $textX = ($size - imagefontwidth($font)) / 2;
    $textY = ($size - imagefontheight($font)) / 2;
    
    imagestring($img, $font, $textX, $textY, $text, $white);
    
    imagepng($img, $filename);
    imagedestroy($img);
    echo "Created: $filename ($size x $size)\n";
}

createIcon(192, __DIR__ . '/assets/icons/icon-192.png');
createIcon(512, __DIR__ . '/assets/icons/icon-512.png');

echo "Done.\n";
