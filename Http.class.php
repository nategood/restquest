<?php
/**
 * Http
 * @author Nate Good <me@nategood.com>
 */
class Http {
    
    const HTML = "text/html";
    const JAVASCRIPT = "text/javascript";
    const JSON = "application/json";
    const URL_ENCODED = "application/x-www-form-urlencoded";
    const XML = "text/xml";
    const PLAIN = "text/plain";
    const XML_RPC = "text/xml";// Should be "application/xml+rpc" but some lack support...
    const RSS = "application/rss+xml";
    const ATOM = "application/atom+xml";
    const FORM_DATA = "multipart/form-data";
    const CSV = "text/csv";
    const CSV_EXTENDED = "text/comma-separated-values";
    const YAML = 'application/x-yaml'; // there appears to be controversy here http://stackoverflow.com/questions/332129/yaml-mime-type
    
    const GET = "GET";
    const PUT = "PUT";
    const POST = "POST";
    const DELETE = "DELETE";
    const HEAD = "HEAD";
    const OPTIONS = "OPTIONS";
    const TRACE = "TRACE";
    
    // Method Lookup Dictionary
    public static $methods = array(
        Http::GET => 1, 
        Http::PUT => 1, 
        Http::POST => 1, 
        Http::DELETE => 1, 
        Http::HEAD => 1, 
        Http::OPTIONS => 1, 
        Http::TRACE => 1
    );
    
    public static $codes = array(
        "200" => "HTTP/1.1 200 OK",
        "201" => "HTTP/1.1 201 Created",
        "204" => "HTTP/1.1 204 No Content",
        "304" => "HTTP/1.1 304 Not Modified",
        "400" => "HTTP/1.1 400 Bad Request",
        "401" => "HTTP/1.1 401 Unauthorized",
        "403" => "HTTP/1.1 403 Forbidden",
        "404" => "HTTP/1.1 404 Not Found",
        "405" => "HTTP/1.1 405 Method Not Allowed",
        "418" => "HTTP/1.1 418 I'm a Teapot", // http://en.wikipedia.org/wiki/Hyper_Text_Coffee_Pot_Control_Protocol
        "500" => "HTTP/1.1 500 Internal Server Error"
    );
    
    /**
     * @return HTTP Status Code Header
     */
    public static function httpStatus($code) {
        return Http::$codes[$code];
    }
    
    /**
     * @return bool Is this a valid/support HTTP method?
     */
    public static function isValidMethod($method) {
        return array_key_exists($method, self::$methods);
    }
    
}