<?php

require_once 'Pedido.php';

class UploadManager{

    //--- Attributes ---//
    private $_DIR_TO_SAVE;
    private $_fileExtension;
    private $_newFileName;
    private $_pathToSaveImage;

    //--- Constructor ---//
    public function __construct($dirToSave, $order_id, $array)
    {
        self::createDirIfNotExists($dirToSave);
        $this->setDirectoryToSave($dirToSave);
        $this->saveFileIntoDir($order_id, $array);
    }
    
    //--- Setters ---//

    public function setDirectoryToSave($dirToSave){
        $this->_DIR_TO_SAVE = $dirToSave;
    }

    public function setFileExtension($fileExtension = 'png'){
        $this->_fileExtension = $fileExtension;
    }

    public function setNewFileName($newFileName){
        $this->_newFileName = $newFileName;
    }

    /**
     * Set the path to save the image.
     */
    public function setPathToSaveImage(){
        $this->_pathToSaveImage = $this->getDirectoryToSave().'Mesa_'.$this->getNewFileName().'.'.$this->getFileExtension();
    }
    
    //--- Getters ---//
    
    public function getFileExtension(){
        return $this->_fileExtension;
    }

    public function getNewFileName(){
        return $this->_newFileName;
    }

    public function getPathToSaveImage(){
        return $this->_pathToSaveImage;
    }

    public function getDirectoryToSave(){
        return $this->_DIR_TO_SAVE;
    }

    //--- Methods ---//

    public static function getOrderImageNameExt($fileManager, $id){
        $fullpath = $fileManager->getPathToSaveImage();
        return $fullpath;
    }

    private static function createDirIfNotExists($dirToSave){
        if (!file_exists($dirToSave)) {
            mkdir($dirToSave, 0777, true);
        }
    }

    public function saveFileIntoDir($order_id, $array):bool{
        $success = false;
        
        try {
            $this->setNewFileName($order_id);
            $this->setFileExtension();
            $this->setPathToSaveImage();
            if ($this->moveUploadedFile($array['foto_mesa']['tmp_name'])) {
                $success = true;
            }
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }finally{
            return $success;
        }
    }

    public function moveUploadedFile($tmpFileName){
        return move_uploaded_file($tmpFileName, $this->getPathToSaveImage());
    }

    public static function moveImageFromTo($oldDir, $newDir, $fileName){
        self::createDirIfNotExists($newDir);
        return rename($oldDir.$fileName, $newDir.$fileName);
    }
}
?>