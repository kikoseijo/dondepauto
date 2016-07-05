<?php

namespace App\Repositories\File;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Image;

/**
 * Created by PhpStorm.
 * User: Desarrollador 1
 * Date: 28/06/2016
 * Time: 10:43 AM
 */
class SpaceImagesRepository extends ImagesRepository
{
    protected $path = "images/marketplace";

    /**
     * @param Image $image
     * @param $name
     * @return Image
     */
    public function saveBigImage(Image $image, $name)
    {
        $bigImage = $this->resizeWidthProportional($image, 500);
        return $this->saveImageMorePath($bigImage, 'big', $name);
    }

    /**
     * @param Image $image
     * @param $name
     * @param int $size
     * @return Image
     */
    protected function generateThumbImage(Image $image, $name, $size = 45)
    {
        if($image->getWidth() >= $image->getHeight()) {
            return $this->resizeHeightProportional($image, $size);
        }
        else {
            return $this->resizeWidthProportional($image, $size);
        }
    }

    /**
     * @param Image $image
     * @param $name
     */
    protected function saveThumbImage(Image $image, $name)
    {
        $thumb = $this->generateThumbImage($image, $name, 45);
        $this->saveImageMorePath($thumb, 'thumb', $name);        
    }

    /**
     * @param Image $image
     * @param $name
     */
    protected function saveDetailThumbImage(Image $image, $name)
    {
        $thumb = $this->generateThumbImage($image, $name, 219);
        $this->saveImageMorePath($thumb, 'detailThumbs', $name);
    }

    /**
     * @param UploadedFile $photoFile
     * @param $name
     * @param bool $detailThumb
     * @return mixed
     */
    public function saveSpaceImage(UploadedFile $photoFile, $name, $detailThumb = false)
    {
        $image = \Image::make($photoFile);
        $this->saveBigImage($image, $name);

        if($detailThumb) {
            $this->saveDetailThumbImage($image, $name);
        }

        $this->saveThumbImage($image, $name);
        
        return $image;
    }
}