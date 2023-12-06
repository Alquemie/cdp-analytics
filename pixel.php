<?php

namespace Alquemie\CDP;

// remove header
header_remove('ETag');
header_remove('Pragma');
header_remove('Cache-Control');
header_remove('Last-Modified');
header_remove('Expires');

// set header
header('Expires: Thu, 1 Jan 1970 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0',false);
header('Pragma: no-cache');

require_once("./lib/autoload.php");
use Segment\Segment;

if (isset($_GET['ttd_puid']) && isset($_GET['ttd_id'])) {

    // Segment::init("5MKmgcAc61NGaYGvJeMzyuEFVnyfkpmG"); // Junk Drawer
    Segment::init("dFzOhuNo9KFKC9jL6vhAax7ZnCkPEQZI");  // Production Source
    $anonId = htmlspecialchars($_GET['ttd_puid']);
    $tdid = htmlspecialchars($_GET['ttd_id']);

    // $anonId = (isset($_COOKIE['ajs_anonynmous_id'])) ? $_COOKIE['ajs_anonynmous_id'] : htmlspecialchars($_GET['ttd_puid']);
    // $userId = (isset($_COOKIE['ajs_user_id'])) ? $_COOKIE['ajs_user_id'] : null;
    if ($anonId != 'undefined' && $anonId != 'empty') {
        Segment::track(array(
            "anonymousId" => $anonId,
            "event" => "TradeDeskID Assigned",
            "properties" => array(
                "ttd_id" => $tdid // New TDID Value
            )
        ));
    
        Segment::identify(array(
            "anonymousId" => $anonId,
            "traits" => array(
                "ttd_id" => $tdid // New TDID Value
            )
        ));
        setcookie('ttdid', $tdid, time()+60*60*24*7, '/', 'unlock.com' );
    } else {
        Segment::track(array(
            "anonymousId" => 'unlock-admin',
            "event" => "TradeDeskID Error",
            "properties" => array(
                "ttd_id" => $tdid, // New TDID Value  
                "search" => json_encode($_GET)             
            )
        ));
    }
}

header('Content-Type: image/png');
header('Content-Disposition: inline; filename="unlktdid.png"');
// echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=');
die("\x89\x50\x4e\x47\x0d\x0a\x1a\x0a\x00\x00\x00\x0d\x49\x48\x44\x52\x00\x00\x00\x01\x00\x00\x00\x01\x01\x03\x00\x00\x00\x25\xdb\x56\xca\x00\x00\x00\x03\x50\x4c\x54\x45\x00\x00\x00\xa7\x7a\x3d\xda\x00\x00\x00\x01\x74\x52\x4e\x53\x00\x40\xe6\xd8\x66\x00\x00\x00\x0a\x49\x44\x41\x54\x08\xd7\x63\x60\x00\x00\x00\x02\x00\x01\xe2\x21\xbc\x33\x00\x00\x00\x00\x49\x45\x4e\x44\xae\x42\x60\x82");


// echo "hello";

function guidv4($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
