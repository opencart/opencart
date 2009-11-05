<?php
/*
* CKFinder
* ========
* http://ckfinder.com
* Copyright (C) 2007-2009, CKSource - Frederico Knabben. All rights reserved.
*
* The software, this file and its contents are subject to the CKFinder
* License. Please read the license.txt file before using, installing, copying,
* modifying or distribute this file or part of its contents. The contents of
* this file is part of the Source Code of CKFinder.
*/

/**
 * @package CKFinder
 * @subpackage CommandHandlers
 * @copyright CKSource - Frederico Knabben
 */

/**
 * Handle Thumbnail command (create thumbnail if doesn't exist)
 *
 * @package CKFinder
 * @subpackage CommandHandlers
 * @copyright CKSource - Frederico Knabben
 */
class CKFinder_Connector_CommandHandler_Thumbnail extends CKFinder_Connector_CommandHandler_CommandHandlerBase
{
    /**
     * Command name
     *
     * @access private
     * @var string
     */
    var $command = "Thumbnail";

    /**
     * Send response
     * @access public
     *
     */
    function sendResponse()
    {
        if (!function_exists('ob_list_handlers') || !ob_list_handlers()) {
            @ob_end_clean();
        }
        header("Content-Encoding: none");

        $this->checkConnector();
        $this->checkRequest();

        $_config =& CKFinder_Connector_Core_Factory::getInstance("Core_Config");

        $_thumbnails = $_config->getThumbnailsConfig();
        if (!$_thumbnails->getIsEnabled()) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_THUMBNAILS_DISABLED);
        }

        if (!$this->_currentFolder->checkAcl(CKFINDER_CONNECTOR_ACL_FILE_VIEW)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
        }

        if (!isset($_GET["FileName"])) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }

        $fileName = CKFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding($_GET["FileName"]);
        $_resourceTypeInfo = $this->_currentFolder->getResourceTypeConfig();

        if (!CKFinder_Connector_Utils_FileSystem::checkFileName($fileName)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }
        $sourceFilePath = CKFinder_Connector_Utils_FileSystem::combinePaths($this->_currentFolder->getServerPath(), $fileName);

        if ($_resourceTypeInfo->checkIsHiddenFile($fileName) || !file_exists($sourceFilePath)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_FILE_NOT_FOUND);
        }

        $thumbFilePath = CKFinder_Connector_Utils_FileSystem::combinePaths($this->_currentFolder->getThumbsServerPath(), $fileName);

        // If the thumbnail file doesn't exists, create it now.
        if (!file_exists($thumbFilePath)) {
            if(!$this->createThumb($sourceFilePath, $thumbFilePath, $_thumbnails->getMaxWidth(), $_thumbnails->getMaxHeight(), $_thumbnails->getQuality(), true, $_thumbnails->getBmpSupported())) {
                $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
            }
        }

        $size = filesize($thumbFilePath);
        $sourceImageAttr = getimagesize($thumbFilePath);
        $mime = $sourceImageAttr["mime"];

        $rtime = isset($_SERVER["HTTP_IF_MODIFIED_SINCE"])?strtotime($_SERVER["HTTP_IF_MODIFIED_SINCE"]):0;
        $mtime =  filemtime($thumbFilePath);
        $etag = dechex($mtime) . "-" . dechex($size);

        $is304 = false;

        if (isset($_SERVER["HTTP_IF_NONE_MATCH"]) && $_SERVER["HTTP_IF_NONE_MATCH"] === $etag) {
            $is304 = true;
        }
        else if($rtime == $mtime) {
            $is304 = true;
        }

        if ($is304) {
            header("HTTP/1.0 304 Not Modified");
            exit();
        }

        //header("Cache-Control: cache, must-revalidate");
        //header("Pragma: public");
        //header("Expires: 0");
        header('Cache-control: public');
        header('Etag: ' . $etag);
        header("Content-type: " . $mime . "; name=\"" . CKFinder_Connector_Utils_Misc::mbBasename($thumbFilePath) . "\"");
        header("Last-Modified: ".gmdate('D, d M Y H:i:s', $mtime) . " GMT");
        //header("Content-type: application/octet-stream; name=\"{$file}\"");
        //header("Content-Disposition: attachment; filename=\"{$file}\"");
        header("Content-Length: ".$size);
        readfile($thumbFilePath);
        exit;
    }

    /**
     * Create thumbnail
     *
     * @param string $sourceFile
     * @param string $targetFile
     * @param int $maxWidth
     * @param int $maxHeight
     * @param boolean $preserverAspectRatio
     * @param boolean $bmpSupported
     * @return boolean
     * @static
     * @access public
     */
    function createThumb($sourceFile, $targetFile, $maxWidth, $maxHeight, $quality, $preserverAspectRatio, $bmpSupported = false)
    {
        $sourceImageAttr = @getimagesize($sourceFile);
        if ($sourceImageAttr === false) {
            return false;
        }
        $sourceImageWidth = isset($sourceImageAttr[0]) ? $sourceImageAttr[0] : 0;
        $sourceImageHeight = isset($sourceImageAttr[1]) ? $sourceImageAttr[1] : 0;
        $sourceImageMime = isset($sourceImageAttr["mime"]) ? $sourceImageAttr["mime"] : "";
        $sourceImageBits = isset($sourceImageAttr["bits"]) ? $sourceImageAttr["bits"] : 8;
        $sourceImageChannels = isset($sourceImageAttr["channels"]) ? $sourceImageAttr["channels"] : 3;

        if (!$sourceImageWidth || !$sourceImageHeight || !$sourceImageMime) {
            return false;
        }

        $iFinalWidth = $maxWidth == 0 ? $sourceImageWidth : $maxWidth;
        $iFinalHeight = $maxHeight == 0 ? $sourceImageHeight : $maxHeight;

        if ($sourceImageWidth <= $iFinalWidth && $sourceImageHeight <= $iFinalHeight) {
            if ($sourceFile != $targetFile) {
                copy($sourceFile, $targetFile);
            }
            return true;
        }

        if ($preserverAspectRatio)
        {
            // Gets the best size for aspect ratio resampling
            $oSize = CKFinder_Connector_CommandHandler_Thumbnail::GetAspectRatioSize($iFinalWidth, $iFinalHeight, $sourceImageWidth, $sourceImageHeight );
        }
        else {
            $oSize = array($iFinalWidth, $iFinalHeight);
        }

        CKFinder_Connector_Utils_Misc::setMemoryForImage($sourceImageWidth, $sourceImageHeight, $sourceImageBits, $sourceImageChannels);

        switch ($sourceImageAttr['mime'])
        {
            case 'image/gif':
                {
                    if (@imagetypes() & IMG_GIF) {
                        $oImage = @imagecreatefromgif($sourceFile);
                    } else {
                        $ermsg = 'GIF images are not supported';
                    }
                }
                break;
            case 'image/jpeg':
                {
                    if (@imagetypes() & IMG_JPG) {
                        $oImage = @imagecreatefromjpeg($sourceFile) ;
                    } else {
                        $ermsg = 'JPEG images are not supported';
                    }
                }
                break;
            case 'image/png':
                {
                    if (@imagetypes() & IMG_PNG) {
                        $oImage = @imagecreatefrompng($sourceFile) ;
                    } else {
                        $ermsg = 'PNG images are not supported';
                    }
                }
                break;
            case 'image/wbmp':
                {
                    if (@imagetypes() & IMG_WBMP) {
                        $oImage = @imagecreatefromwbmp($sourceFile);
                    } else {
                        $ermsg = 'WBMP images are not supported';
                    }
                }
                break;
            case 'image/bmp':
                {
                    /*
                    * This is sad that PHP doesn't support bitmaps.
                    * Anyway, we will use our custom function at least to display thumbnails.
                    * We'll not resize images this way (if $sourceFile === $targetFile),
                    * because user defined imagecreatefrombmp and imagecreatebmp are horribly slow
                    */
                    if ($bmpSupported && (@imagetypes() & IMG_JPG) && $sourceFile != $targetFile) {
                        $oImage = CKFinder_Connector_Utils_Misc::imageCreateFromBmp($sourceFile);
                    } else {
                        $ermsg = 'BMP/JPG images are not supported';
                    }
                }
                break;
            default:
                $ermsg = $sourceImageAttr['mime'].' images are not supported';
                break;
        }

        if (isset($ermsg) || false === $oImage) {
            return false;
        }


        $oThumbImage = imagecreatetruecolor($oSize["Width"], $oSize["Height"]);
        //imagecopyresampled($oThumbImage, $oImage, 0, 0, 0, 0, $oSize["Width"], $oSize["Height"], $sourceImageWidth, $sourceImageHeight);
        CKFinder_Connector_Utils_Misc::fastImageCopyResampled($oThumbImage, $oImage, 0, 0, 0, 0, $oSize["Width"], $oSize["Height"], $sourceImageWidth, $sourceImageHeight, (int)max(floor($quality/20), 1));

        switch ($sourceImageAttr['mime'])
        {
            case 'image/gif':
                imagegif($oThumbImage, $targetFile);
                break;
            case 'image/jpeg':
            case 'image/bmp':
                imagejpeg($oThumbImage, $targetFile, $quality);
                break;
            case 'image/png':
                imagepng($oThumbImage, $targetFile);
                break;
            case 'image/wbmp':
                imagewbmp($oThumbImage, $targetFile);
                break;
        }

        $_config =& CKFinder_Connector_Core_Factory::getInstance("Core_Config");
        if (file_exists($targetFile) && ($perms = $_config->getChmodFiles())) {
            $oldUmask = umask(0);
            chmod($targetFile, $perms);
            umask($oldUmask);
        }

        imageDestroy($oImage);
        imageDestroy($oThumbImage);

        return true;
    }



    /**
     * Return aspect ratio size, returns associative array:
     * <pre>
     * Array
     * (
     *      [Width] => 80
     *      [Heigth] => 120
     * )
     * </pre>
     *
     * @param int $maxWidth
     * @param int $maxHeight
     * @param int $actualWidth
     * @param int $actualHeight
     * @return array
     * @static
     * @access public
     */
    function getAspectRatioSize($maxWidth, $maxHeight, $actualWidth, $actualHeight)
    {
        $oSize = array("Width"=>$maxWidth, "Height"=>$maxHeight);

        // Calculates the X and Y resize factors
        $iFactorX = (float)$maxWidth / (float)$actualWidth;
        $iFactorY = (float)$maxHeight / (float)$actualHeight;

        // If some dimension have to be scaled
        if ($iFactorX != 1 || $iFactorY != 1)
        {
            // Uses the lower Factor to scale the oposite size
            if ($iFactorX < $iFactorY) {
                $oSize["Height"] = (int)round($actualHeight * $iFactorX);
            }
            else if ($iFactorX > $iFactorY) {
                $oSize["Width"] = (int)round($actualWidth * $iFactorY);
            }
        }

        if ($oSize["Height"] <= 0) {
            $oSize["Height"] = 1;
        }
        if ($oSize["Width"] <= 0) {
            $oSize["Width"] = 1;
        }

        // Returns the Size
        return $oSize;
    }
}
