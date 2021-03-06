<?php
/**
 * PHP Class Image.php
 * PHP Version 5
 *
 * @category    : default
 * @package     : default
 * @license     : Not for free use
 * @date        : 29/07/12
 * @author      : aroy <contact@aroy.fr>
 */
require_once APPLICATION_PATH . '/../library/PhpThumb/ThumbLib.inc.php';
class App_View_Helper_DisplayImage
    extends Zend_View_Helper_Abstract
{
    /**
     * Retourne l'url vers une miniature de l'image en paramètre
     *
     * @param     $filename
     * @param     $fileCategory
     * @param int $width
     * @param int $height
     *
     * @return null|string
     */
    public function displayImage($filename, $fileCategory, $width = 800, $height = 600)
    {
        if (!is_null($filename)) {
            if (in_array($fileCategory, Llv_Constant_File_Category::getAssociativeArray())) {
                $fileParentPath = '';
                switch ($fileCategory) {
                    case Llv_Constant_File_Category::CARROUSEL:
                        $fileParentPath = Llv_Services_Cms_Helper_Carrousel::getCarrouselFilesPath();
                        $fileUrl = Llv_Services_Cms_Helper_Carrousel::getCarrouselFilesUrl();
                        break;
                    case Llv_Constant_File_Category::NEWS:
                        $fileParentPath = Llv_Services_News_Helper_News::getNewsFilesPath();
                        $fileUrl = Llv_Services_News_Helper_News::getNewsFilesUrl();
                        break;
                    case Llv_Constant_File_Category::ACTIVITY:
                        $fileParentPath = Llv_Services_Activity_Helper_Activity::getActivityFilesPath();
                        $fileUrl = Llv_Services_Activity_Helper_Activity::getActivityFilesUrl();
                        break;
                }
                $filename = $fileParentPath . $filename;

                if (file_exists($filename)) {
                    $fileParentPathThumb = $fileParentPath . '_thumb';
                    $fileUrl .= '_thumb/';
                    if (!is_dir($fileParentPathThumb)) {
                        mkdir($fileParentPathThumb);
                    }
                    $thumb = PhpThumbFactory::create($filename);
                    $newFile = basename($thumb->getFileName(), '.' . strtolower($thumb->getFormat()))
                        . '_thumb_'
                        . $width . '_'
                        . $height . '.'
                        . strtolower($thumb->getFormat());
                    $destPath = $fileParentPathThumb . '/' . ltrim($newFile, '/');

                    if (!file_exists($destPath)) {
                        $thumb->resize($width, $height);
                        $thumb->save($destPath);
                    }
                    $fileUrl .= ltrim($newFile, '/');
                    return $this->view->baseUrl() . $fileUrl;
                }
            }
        }
        return null;
    }
}