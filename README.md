Restquest
=========

SYN. SYN-ACK?
-------------

ReST is a beautiful thing.  Building ReSTful apps in PHP is not.  Restquest is a small set of classes that make building ReSTful apps in PHP less complicated.

Restquest abstracts away the ugliness of HTTP in PHP (the headers, parsing the body, PUT & DELETE requests) into a clean simple interface.  By no means is Restquest only for ReSTful APIs.  Restquest can be used in any PHP application to simplify your HTTP life.

Installation
------------

Restquest is intended to be drop in place and go.  It has been tested with Apache in mod_php mode and nginx in fast cgi + php fpm mode.  It *should* work with other fast cgi setups assuming they their HTTP header setup follows the common.

Though tempting, in order to be backwards compatible, Restquest does not use 5.3 namespacing.  Restquest should be compatible with PHP 5.1+ and though not throughly tested, earlier versions as well.

A **Quick** Peak
----------------

Here is a sneak peak of how you can handle a `PUT` request with json data in the request body.  *This particular URL requires a bit of URL rewrite magic.*

The HTTP Request

    PUT /test.json HTTP/1.1
    User-Agent: curl/7.19.5 (i386-apple-darwin8.11.1) libcurl/7.19.5 OpenSSL/0.9.7l zlib/1.2.3 libidn/1.15
    Host: localhost
    Accept: */*
    Content-type: application/json
    Content-Length: 87
    
    {"coolThings":["Open Source Code", "Rogue Chocolate Stout", "The Pittsburgh Steelers"]}

The Restquest Code

    <?php
        require ('Restquest.php');
        $req = new Restquest;
        
        // Optionally specify that we only are interested in JSON PUT requests
        $req->expect(Http::PUT, Http::JSON);
        var_dump($req);

The output

    object(Restquest)#1 (13) {
      ["uri"]=>
      string(43) "http://localhost/test.json"
      ["path"]=>
      string(23) "/test.json"
      ["method"]=>
      string(3) "PUT"
      ["body"]=>
      string(87) "{"coolThings":["Open source code", "Rogue Chocolate Stout", "the Pittsburgh Steelers"]}"
      ["data"]=>
      array(1) {
        ["coolThings"]=>
        array(3) {
          [0]=>
          string(16) "Open Source Code"
          [1]=>
          string(21) "Rogue Chocolate Stout"
          [2]=>
          string(23) "The Pittsburgh Steelers"
        }
      }
      ["headers"]=>
      array(3) {
        ["user_agent"]=>
        string(90) "curl/7.19.5 (i386-apple-darwin8.11.1) libcurl/7.19.5 OpenSSL/0.9.7l zlib/1.2.3 libidn/1.15"
        ["host"]=>
        string(13) "localhost"
        ["accept"]=>
        string(3) "*/*"
      }
      ["content_type"]=>
      string(16) "application/json"
      ["query_string"]=>
      array(0) {
      }
      ["session"]=>
      NULL
      ["files"]=>
      NULL
      ["extension"]=>
      string(4) "json"
      ["domain"]=>
      string(13) "localhost"
      ["protocol"]=>
      string(4) "http"
    }

**More examples available in the `examples` directory.**

Classes
-------

 - Restquest: The meat. The class that abstracts the Http request into a clean PHP class.
 - Http: Class that constants mainly constants pertaining to the [HTTP Spec][1].
 - HttpException: Exception class that follows the [Http Response Error Codes][2].
 
License
-------

Restquest is released under the [MIT License][4].  Have fun.  ReST easy.
 
[1]: http://www.w3.org/Protocols/rfc2616/rfc2616.html
[2]: http://en.wikipedia.org/wiki/List_of_HTTP_status_codes#4xx_Client_Error
[3]: http://www.infoq.com/articles/rest-introduction
[4]: https://github.com/nategood/restquest/blob/master/license.txt