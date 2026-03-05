<?php
# This code is written by Finlay Metcalfe, encrappified by malware8148 held under the AGPL-3.0 License

declare(strict_types=1);

function overlayOnBase(Imagick $baseImage, int $id, int $x, int $y, float $scale) : Imagick {
    // Load the overlay image from the objects directory
    $overlayImage = new Imagick(__DIR__."/obj/{$id}.png");
    
    // Ensure the overlay image has an alpha channel
    $overlayImage->setImageAlphaChannel(Imagick::ALPHACHANNEL_SET);

    $newWidth = (int)($overlayImage->getImageWidth() * $scale);
    $newHeight = (int)($overlayImage->getImageHeight() * $scale);

    if($id==8){
        $overlayImage->scaleImage((int)($newWidth*2), (int)($newHeight*2));
        $newWidth = (int)($newWidth+100);
        $newHeight = (int)($newHeight+52);
    } elseif($id==7){
        $overlayImage->scaleImage((int)($newWidth*1.75), (int)($newHeight*1.75));
        $newWidth = (int)($newWidth+72);
        $newHeight = (int)($newHeight+40);
    } else{
    $overlayImage->scaleImage($newWidth, $newHeight);
    $newWidth = (int)($newWidth);
    $newHeight = (int)($newHeight+4);
    }

    $x -= (int)($newWidth / 2)+8;
    $y -= (int)($newHeight / 2)-4;

    // Composite the overlay image onto the base image at the specified coordinates
    $baseImage->compositeImage($overlayImage, Imagick::COMPOSITE_OVER, $x, $y);
    
    return $baseImage;
}

function generate2DShooterImage(SimpleXMLElement $xml) : array {
    $color = (string) $xml->playfield['color'];
    $playfieldString = (string)$xml->objects;
    $playfieldArray = explode('|', $playfieldString);

    list($r, $g, $b) = sscanf($color, "%02x%02x%02x");

    $coordsString = (string) $xml->playfield;
    $coordsArray = explode('|', $coordsString);

    $points = [];
    foreach ($coordsArray as $coord) {
        list($x, $y) = explode(',', $coord);
        $points[] = ['x' => (int) $x, 'y' => (int) $y];
    }

    $width = 880;
    $height = 880;

    $minX = min(array_column($points, 'x'));
    $maxX = max(array_column($points, 'x'));
    $minY = min(array_column($points, 'y'));
    $maxY = max(array_column($points, 'y'));

    $scaleMin = min($minX, $minY);
    $scaleMax = max($maxX, $maxY);

    $scale = $width / ($scaleMax - $scaleMin) * 0.85;
    $offsetX = ($width - ($maxX - $minX) * $scale) / 2;
    $offsetY = ($height - ($maxY - $minY) * $scale) / 2;
    
    foreach ($points as &$point) {
        $point['x'] = ($point['x'] - $minX) * $scale + $offsetX;
        $point['y'] = ($point['y'] - $minY) * $scale + $offsetY;
    }
    unset($point);

    $newImage = new Imagick();
    $newImage->newImage($width, $height, new ImagickPixel('black'));
    $newImage->setImageFormat('png');

    $draw = new ImagickDraw();
    $polygonColor = sprintf("rgb(%d,%d,%d)", $r, $g, $b);

    $draw->setFillColor(new ImagickPixel($polygonColor));
   
    $draw->polygon($points);
    $borderDraw = new ImagickDraw();
    $borderDraw->setStrokeColor(new ImagickPixel('white')); 
    $borderDraw->setStrokeWidth(0.5);
    $borderDraw->setFillOpacity(0);
    $borderDraw->polygon($points);

    $newImage->drawImage($draw);
    $newImage->drawImage($borderDraw);
    
    // Track object 1's position
    $object1X = null;
    $object1Y = null;
    
    foreach ($playfieldArray as $object) {
        list($id, $x, $y) = explode(',', $object);
        $id = (int) $id;
        $x = ($x - $minX) * $scale + $offsetX;
        $y = ($y - $minY) * $scale + $offsetY;
        
        // Save position of object 1
        if ($id === 1) {
            $object1X = (int) $x;
            $object1Y = (int) $y;
        } else if ($id === 101) {
            // Object 101 is treated as object 1 for thumbnail purposes
            $object1X = (int) $x;
            $object1Y = (int) $y;
        }
    
        $newImage = overlayOnBase($newImage, max(1, $id), (int) $x, (int) $y, $scale*0.6125);
    }
    
    // Create zoomed thumbnail centered on object 1
    $thumbnailImage = new Imagick();
    $thumbnailImage->newImage(80, 80, new ImagickPixel('black'));
    $thumbnailImage->setImageFormat('png');
    
    if ($object1X !== null && $object1Y !== null) {
        // Use a 10x10 crop centered on object 1
        $cropSize = 240;
        $halfCropSize = $cropSize / 2;
        
        // Calculate crop coordinates to center on object 1
        $cropStartX = max(0, $object1X - $halfCropSize)+5;
        $cropStartY = max(0, $object1Y - $halfCropSize)+5;
        
        // Ensure we don't go outside image boundaries
        if ($cropStartX + $cropSize > $width) {
            $cropStartX = $width - $cropSize;
        }
        if ($cropStartY + $cropSize > $height) {
            $cropStartY = $height - $cropSize;
        }
        
        // Create a crop from the original image
        $cropImage = clone $newImage;
        $cropImage->cropImage($cropSize, $cropSize, $cropStartX, $cropStartY);
        
        // Resize to 80x80 with high quality settings
        $cropImage->resizeImage(80, 80, Imagick::FILTER_LANCZOS, 0.9);
        
        // Sharpen the image slightly to preserve detail
        $cropImage->sharpenImage(0, 1.0);
        
        // Copy the resized image to our thumbnail canvas
        $thumbnailImage->compositeImage($cropImage, Imagick::COMPOSITE_OVER, 0, 0);
        
        // Clean up temporary image
        $cropImage->clear();
        $cropImage->destroy();
    } else {
        // If object 1 not found, just take center
        $cropSize = 10;
        $cropImage = clone $newImage;
        $cropImage->cropImage($cropSize, $cropSize, ($width - $cropSize) / 2, ($height - $cropSize) / 2);
        $cropImage->resizeImage(80, 80, Imagick::FILTER_LANCZOS, 0.9);
        $cropImage->sharpenImage(0, 1.0);
        
        $thumbnailImage->compositeImage($cropImage, Imagick::COMPOSITE_OVER, 0, 0);
        
        $cropImage->clear();
        $cropImage->destroy();
    }

    // Downscale newImage to 220x220
    $newImage->resizeImage(220, 220, Imagick::FILTER_LANCZOS, 0.9);
    
    return [
        'full' => $newImage,
        'thumbnail' => $thumbnailImage
    ];
}
function generateImageFromXML(string $xml, string $outputFilePath): void {
    $xml = simplexml_load_string($xml);
    $images = generate2DShooterImage($xml);
    
    // Save full image
    $images['full']->writeImage($outputFilePath."image.png");
    
    // Save cropped thumbnail
    $images['thumbnail']->writeImage($outputFilePath."thumbnail.png");
    
    // Clean up resources
    $images['full']->clear();
    $images['full']->destroy();
    $images['thumbnail']->clear();
    $images['thumbnail']->destroy();
}