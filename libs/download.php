<?php

namespace florinp\messenger\libs;

use InvalidArgumentException;
class download {

    /**
     * File directory
     * @var string
     */
    protected $directory;

    /**
     * File name
     * @var string
     */
    protected $filename;

    /**
     * File pointer
     * @var resource
     */
    protected $file;

    /**
     * File full path
     * @var string
     */
    protected $full_path;

    /**
     * @param string $phpbb_root_path phpBB root path
     */
    public function __construct($phpbb_root_path) {
        $this->directory = $phpbb_root_path . 'store/messenger/files';
    }


    /**
     * Set the filename
     * @param string $filename File name
     * @throws InvalidArgumentException when the file not exist or is not readable
     */
    public function setFile($filename) {
        $file_full_path = $this->directory . '/'. $filename;
        if(!is_file($file_full_path)) {
            throw new InvalidArgumentException("File does not exist");
        } else if(!is_readable($file_full_path)) {
            throw new InvalidArgumentException("File to download is not readable.");
        }
        $this->filename = $filename;
        $this->file = fopen($file_full_path, 'rb');
        $this->full_path = $file_full_path;
    }

    /**
     * Get file mime type
     * @return string
     */
    private function getMimeType() {
        $fileExtension = pathinfo($this->filename, PATHINFO_EXTENSION);
        $mimeTypeHelper = Mimetypes::getInstance();
        $mimeType = $mimeTypeHelper->fromExtension($fileExtension);

        return !is_null($mimeType) ? $mimeType : 'application/force-download';
    }

    /**
     * Get file size
     * @return int
     */
    private function getFileSize() {
        $stat = fstat($this->file);
        return $stat['size'];
    }

    /**
     * Sends the download to browser
     * @param bool|true $forceDownload
     */
    public function sendDownload($forceDownload = true) {
        if(headers_sent()) {
            throw new \RuntimeException("Cannot send file to the browser, since the headers were already sent");
        }
        $mimeType = $this->getMimeType();
        $fileSize = $this->getFileSize();

        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header("Content-Type: application/octet-stream");

        if($forceDownload) {
            header("Content-Disposition: attachment; filename=\"{$this->filename}\";");
        } else {
            header("Content-Disposition: filename=\"{$this->filename}\";");
        }

        header("Content-Transfer-Encoding: binary");
        header("Content-Length: {$fileSize}");

        @ob_clean();

        rewind($this->file);
        fpassthru($this->file);
    }

}