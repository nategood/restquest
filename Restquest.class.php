<?php
/**
 * Restquest Object
 * 
 * @author Nate Good <me@nategood.com>
 */

require('Http.class.php');
require('HttpException.class.php');

class Restquest {
    const DEFAULT_EXTENSION     = 'html';
    const HTTP_HEADER_PREFIX    = 'HTTP_';
    
    /**
     * @var bool allow the method to be defined in the query string 
     *  via `_method=DELETE`.  This is intended to help older browsers
     *  that lack support for HTTP methods beyond GET and POST.
     */
    const USE_METHOD_OVERRIDE   = true; 
        
    public $uri, $path, $method, $body, $data, $headers, $content_type, $query_string, $session, $files, $extension = self::DEFAULT_EXTENSION;
    
    /**
     * Constructors for Restquest
     * @throws HttpException
     */
    public function __construct() {
        $this->method = self::determineMethod();
        $this->content_type = self::determineContentType();
        
        $this->parseBody();
        
        $this->session = $_SESSION;
        
        $this->domain = $_SERVER['SERVER_NAME'];
        $this->protocol = 'http';
        $this->query_string = $_GET;
        // Should cover Apache mod_php and common fast_cgi installs.  If you have a custom/unique fast_cgi
        // installation, you may need to adjust the header name
        $this->path = array_key_exists("REDIRECT_SCRIPT_URI", $_SERVER) ? 
            $_SERVER["REDIRECT_SCRIPT_URI"] : 
            array_key_exists("REQUEST_URI", $_SERVER) ? 
                $_SERVER["REQUEST_URI"] : 
                $_SERVER["SCRIPT_URI"];
        $this->uri = $this->protocol . '://' . $this->domain . $this->path; 
        
        // Apache/PHP Prepends SERVER vars with HTTP_ when they are passed as request headers
        foreach ($_SERVER as $k=>$v) {
            if (substr($k, 0, strlen(self::HTTP_HEADER_PREFIX)) === self::HTTP_HEADER_PREFIX) 
                $this->headers[strtolower(substr($k, strlen(self::HTTP_HEADER_PREFIX)))] = $v;
        }
        
        // Grab the "extension" from the uri
        $dotpos = strrpos($this->path, '.');
        if ($dotpos != -1)
            $this->extension = substr($this->path,$dotpos+1);
    }
    
    /**
     * Sets the Http Method for this request
     * 
     * For now, while browsers lack support for PUT and DELETE
     * Allow for the _method param to be used to override
     * USE_METHOD_OVERRIDE constant can be used to turn this feature
     * off
     * @return string Http Method
     * @throws HttpException
     */
    public static function determineMethod() {
        if (self::USE_METHOD_OVERRIDE && isset($_GET["_method"]))
            $method = $_GET["_method"];
        else
            $method = $_SERVER["REQUEST_METHOD"];
        if (!Http::isValidMethod($method))
            throw new HttpException(405);  //Method Not Allowed/Unsupported
        return $method;
    }
    
    /**
     * Sets content type for this request
     * @return Removes "; charset=UTF-8" etc.
     */
    public static function determineContentType() {
        // e.g. text/json text/javascript text/xml application/xml
        $type = $_SERVER['CONTENT_TYPE'];
        $pos = strpos($type, ';');
        return trim(substr($type, 0, $pos === false ? strlen($type) : $pos));
    }
    
    
    /**
     * Parse the request into PHP datatype depending on content type
     */
    private function parseBody() {
        $this->body = file_get_contents('php://input');
        
        switch ($this->content_type) {
            case Http::FORM_DATA: 
                $this->files    = $_FILES;
            case Http::URL_ENCODED:
                $this->data     = $_POST;
                $this->body     = http_build_query($_POST);
                break;
            case Http::JSON:
            case Http::JAVASCRIPT:
                $this->data = json_decode($this->body, true);
                break;
            // Not yet supported.  Requires external lib.
            // case Http::YAML:
            //     break;
            // Not yet supported will add SimpleXML support soon
            // case Http::XML:
            //     break;
            default:
                $this->data = $this->body;
        }
    }
    
    /**
     * Throws an exception if this request does not match what the 
     * server/script is expecting.  Cuts down on boiler plate.
     * 
     * @param mixed string or array of strings of acceptable HTTP methods, if null accept everything
     * @param mixed string or array of strings of acceptable content types, if null accept everything
     * @throws HttpException
     */
    public function expect($methods = null, $types = null) {
        if (!is_array($methods))
            $methods = array($methods);        
        if (!empty($this->method) && !in_array($this->method, $methods))
            throw new HttpException(405);
        
        if (!is_array($types))
            $types = array($types);        
        if (!empty($this->content_type) && !in_array($this->content_type, $types))
            throw new HttpException(415);
    }
    
    // Safe and Idempotent
    public function isRead() {
        return ($this->method == Http::GET);
    }
    
    // Not Safe
    public function isWrite() {
        return ($this->method == Http::POST || $this->method == Http::PUT || $this->method == Http::DELETE);
    }
    
    // Idempotent
    public function isIdempotent() {
        return ($this->method != Http::POST);
    }
    
    // Meta Request
    public function isMeta() {
        return ($this->method == Http::HEAD || $this->method == Http::OPTIONS);
    }
    
}
