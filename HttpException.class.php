<?php
/**
 * HttpException
 * @author Nate Good <me@nategood.com>
 */
class HttpException extends Exception {
    
    const DEFAULT_STATUS = 400;
    
    public $status;
    public $details;
    
    public function __construct($status = self::DEFAULT_STATUS, $details = null) {
        if (!isset(Http::$codes[$status]))
            $status = self::DEFAULT_STATUS;
        
        $this->status = $status;
        $err = Http::$codes[$this->status];
        
        parent::__construct($err, $status);
        
        //Associate this exception with an HTTP Status Code
        $this->status = $status;
        $this->details = $details;
    }
    
    public function responseStatus() {
        return Http::httpStatus($this->status);
    }
}