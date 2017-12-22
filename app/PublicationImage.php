<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;

class PublicationImage extends Model
{
    /** Directorio para imagenes subidas a las publicaciones */
    const DIR_UPLOAD_PUBLICATION_NORMAL = '/uploads/publication/normal/';
    const DIR_UPLOAD_PUBLICATION_SMALL = '/uploads/publication/small/';
    const DIR_UPLOAD_PUBLICATION_ORIGINAL = '/uploads/publication/original/';

    /** Formatos de imagenes */
    const EXTENSION_PNG = 'png';
    const EXTENSION_JPG = 'jpeg';

    /** Parametros */
    const FILENAME_PREFIX = 'IMG-';

    /** Tamaños de imagenes */
    const SIZE_LAND_WIDTH = 1000;
    const SIZE_LAND_HEIGHT = 450;
    const SIZE_THUMB_WIDTH = 277;
    const SIZE_THUMB_HEIGHT = 180;

    protected $table = 'publication_images';

    protected $fillable = [
        'publication_id', 'url',
    ];

    /**
     * Publicacion a la que pertenece la imagen
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function publication()
    {
        return $this->belongsTo('App\Publication', 'publication_id');
    }

    /**
     * Valida si la imagen cumple los parametros para subir al servidor
     *
     * @param $image
     * @return string|null
     */
    public static function hasErrorImage($image)
    {
        if ($image->extension() !== self::EXTENSION_JPG) {
            return 'La imagen debe estar en formato .jpg';
        }

        if (filesize($image) > (1024 * 1024)) {
            return 'La imagen no puede pesar mas de 1MB';
        }

        $info = getimagesize($image->path());

        if (($info[0] < 1000 || $info[0] > 3000) || ($info[1] < 500 || $info[1] > 1600)) {
            return 'La imagen no cumple las dimensiones permitidas';
        }

        return null;
    }

    /**
     * Genera la url hacia la carpeta uploads para las imagenes de la publicacion
     * Remimensionadas
     *
     * @param $publicationId
     * @return string
     */
    public static function generateUploadImageNormalPath($publicationId)
    {
        return public_path() . self::DIR_UPLOAD_PUBLICATION_NORMAL . $publicationId;
    }

    /**
     * Genera la url hacia la carpeta uploads para las imagenes de la publicacion
     * en miniatura
     *
     * @param $publicationId
     * @return string
     */
    public static function generateUploadImageThumbPath($publicationId)
    {
        return public_path() . self::DIR_UPLOAD_PUBLICATION_SMALL . $publicationId;
    }

    /**
     * Genera la url hacia la carpeta uploads para las imagenes de la publicacion
     * originales
     *
     * @param $publicationId
     * @return string
     */
    public static function generateUploadImageOriginalPath($publicationId)
    {
        return public_path() . self::DIR_UPLOAD_PUBLICATION_ORIGINAL . $publicationId;
    }

    /**
     * Genera un filename unico para la imagen
     *
     * @param $image
     * @return string
     */
    public static function generateImageFileName($image)
    {
        $filename = self::FILENAME_PREFIX . strtotime( (new \DateTime())->format('Y-m-d h:i:s') );
        $filename .= '.' . $image->extension();

        return $filename;
    }

    /**
     * En base a una imagen original genera las imagenes redimensionadas
     *
     * @param $path
     * @param $filename
     * @param $publicationId
     */
    public static function generateImagesSizes($path, $filename, $publicationId)
    {
        $origin = $path . '/' . $filename;

        // Land
        self::generateImageSize($origin, self::generateUploadImageNormalPath($publicationId), $filename, self::SIZE_LAND_WIDTH, self::SIZE_LAND_HEIGHT);
        // Thumb
        self::generateImageSize($origin, self::generateUploadImageThumbPath($publicationId), $filename, self::SIZE_THUMB_WIDTH, self::SIZE_THUMB_HEIGHT);
    }

    /**
     * Guarda una copia de la imagen subida en distintos tamaños
     *
     * @param $origin
     * @param $targetDirectory
     * @param $filename
     * @param $width
     * @param $height
     * @throws \Exception
     */
    public static function generateImageSize($origin, $targetDirectory, $filename, $width, $height)
    {
        $temp = tempnam('tmp/', 'tmp');
        $target = $targetDirectory . '/' . $filename;
        $fs = new Filesystem();

        if (! $fs->exists($origin)) {
            throw new \Exception('Image no exists');
        }

        // crear una imagen desde el original
        $img = ImageCreateFromJPEG($origin);
        // crear una imagen nueva
        $newImg = imagecreatetruecolor($width, $height);
        // redimensiona la imagen original copiandola en la imagen
        ImageCopyResized($newImg, $img, 0, 0, 0, 0, $width, $height, ImageSX($img), ImageSY($img));
        // guardar la nueva imagen redimensionada
        ImageJPEG($newImg, $temp, 80);
        ImageDestroy($img);
        // Copio la imagen a su nueva ruta
        if (! $fs->exists($targetDirectory)) {
            $fs->makeDirectory($targetDirectory);
        }

        $fs->copy($origin, $target);
        // guardamos la imagen
        $fp = fopen($target, 'w');
        fputs($fp,fread(fopen($temp,'r'), filesize($temp)));
        fclose($fp);
    }
}
