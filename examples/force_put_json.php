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
 * `curl -X PUT -H "Content-type: application/json" -d '{"myKey":"myData"}' http://localhost/put_json.php/resource.json`
 */

require('../Restquest.class.php');

$req = new Restquest;

// We're only interested in PUT requests with JSON content-type 
$req->expect(Http::PUT, array(Http::JSON, Http::JAVASCRIPT));

var_dump($req);