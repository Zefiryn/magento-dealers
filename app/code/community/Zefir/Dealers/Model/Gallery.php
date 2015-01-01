<?php

/**
 * Base dealer gallery model class
 *
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Model_Gallery extends Mage_Core_Model_Abstract {

    /**
     * Event prefix name used in magento object related events
     *
     * @var string
     */
    protected $_eventPrefix = 'dealer_gallery';

    /**
     * Event argument name
     *
     * @var string
     */
    protected $_eventObject = 'gallery';


    /**
     * Constructor function
     */
    public function _construct() {
        $this->_init('zefir_dealers/gallery');
        parent::_construct();
    }

    /**
     * Get full url path to the image
     *
     * @return string
     */
    public function getUrlPath() {
        return $this->_getConfig()->getMediaUrl($this->getFile());
    }

    public function saveDealerGallery(Zefir_Dealers_Model_Dealer $dealer) {
        $galleryPost = $dealer->getGallery();
        if(array_key_exists('block_id', $galleryPost)) {
            $prefix = $galleryPost['block_id'];
            $images = $dealer->getData($prefix . '_save');
            $data = json_decode($images['images'], true);
            foreach($data as $image) {
                if($image['removed']) {
                    $this->removeImage($image);
                    continue;
                }
                elseif(!array_key_exists('image_id', $image)) {
                    $image['file'] = $this->copyImage($image);
                }
                try {
                    $image['dealer_id'] = $dealer->getId();
                    $imageObject = Mage::getModel(get_class($this));
                    $imageObject->setData($image);
                    $imageObject->save();
                }
                catch (Exception $e) {
                    Mage::logException($e);
                }
            }
        }
    }

    /**
     * Remove image
     *
     * @param array $image
     */
    public function removeImage($image) {
        $ioObject = new Varien_Io_File();
        $file = $image['file'];
        if(array_key_exists('image_id', $image)) {
            //remove file from disc and record from db
            $ioObject->rm($this->_getConfig()->getMediaPath($file));
            Mage::getModel('zefir_dealers/gallery')->load($image['image_id'])->delete();
        }
        else {
            //remove temp file
            $file = substr($file, 0, strlen($file) - 4);
            $ioObject->rm($this->_getConfig()->getTmpMediaPath($file));
        }
    }

    /**
     * Copy image from temp folder
     *
     * @param array $image
     * @return string
     */
    public function copyImage($image) {
        $ioObject = new Varien_Io_File();
        $file = $image['file'];
        $destDirectory = dirname($this->_getConfig()->getMediaPath($file));
        try {
            $ioObject->open(array('path' => $destDirectory));
        }
        catch (Exception $e) {
            $ioObject->mkdir($destDirectory, 0777, true);
            $ioObject->open(array('path' => $destDirectory));
        }

        if(strrpos($file, '.tmp') == strlen($file) - 4) {
            $file = substr($file, 0, strlen($file) - 4);
        }
        $destFile = $this->_getUniqueFileName($file, $ioObject->dirsep());

        /** @var $storageHelper Mage_Core_Helper_File_Storage_Database */
        $storageHelper = Mage::helper('core/file_storage_database');

        if($storageHelper->checkDbUsage()) {
            $storageHelper->renameFile(
                $this->_getConfig()->getTmpMediaShortUrl($file), $this->_getConfig()->getMediaShortUrl($destFile));

            $ioObject->rm($this->_getConfig()->getTmpMediaPath($file));
            $ioObject->rm($this->_getConfig()->getMediaPath($destFile));
        }
        else {
            $ioObject->mv(
                $this->_getConfig()->getTmpMediaPath($file), $this->_getConfig()->getMediaPath($destFile)
            );
        }

        return str_replace($ioObject->dirsep(), '/', $destFile);
    }

    /**
     * Check whether file to move exists. Getting unique name
     *
     * @param string  $file
     * @param  string $dirsep
     * @return string
     */
    protected function _getUniqueFileName($file, $dirsep) {
        if(Mage::helper('core/file_storage_database')->checkDbUsage()) {
            $destFile = Mage::helper('core/file_storage_database')
                ->getUniqueFilename(
                    $this->_getConfig()->getBaseMediaUrlAddition(), $file
                );
        }
        else {
            $destFile = dirname($file) . $dirsep
                . Mage_Core_Model_File_Uploader::getNewFileName($this->_getConfig()->getMediaPath($file));
        }

        return $destFile;
    }

    /**
     * Get config model object
     *
     * @return Zefir_Dealers_Model_Media_Config
     */
    protected function _getConfig() {
        return Mage::getSingleton('zefir_dealers/media_config');
    }
}
