<?php
/**
 * @author Nate Good <me@nategood.com>
 * Example demonstrating responding to a PUT
 * request, reading JSON data from the body
 * and automatically parsing it.
 * 
 * Try it out from cURL:
 *  NOTE: This URL takes advantage of Apache's default behavior to pass
 *  control to the first file in the URL that it can find.  This URL may 
 *  not work with other HTTP servers.
 *
 * `curl -X PUT -H "Content-type: text/javascript" -d '{"myKey":"myData"}' http://localhost/put_json.php/resource.json`
 */

require('../Restquest.php');

$request = new Restquest;

if ($request->method !== Http::PUT) {
    throw new HttpException(405);
}

var_dump($request);

// case Http::GET:
//     $response = 'GET-ing the resource identified by ' . $request->uri;
//     break;

// // Output
// header('Content-Type: text/plain');
// echo $response;