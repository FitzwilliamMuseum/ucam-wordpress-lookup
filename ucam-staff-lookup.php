<?php
/**
 * Plugin Name: UCAM Staff Lookup
 * description: Search staff and students directory, use a shortcode for display of data
 * Version: 1.0
 * Author: Daniel Pett <dejp3@cam.ac.uk>
 * License: GPLv2 or later
 * Text Domain: ucam-staff-lookup
 */

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Require the correct libraries to load data.
 */
require_once "vendor/ibisclient/client/IbisClientConnection.php";
require_once "vendor/ibisclient/methods/InstitutionMethods.php";
require_once "vendor/ibisclient/methods/PersonMethods.php";

function get_display_name($user_id) {
    if (!$user = get_userdata($user_id))
        return false;
    return $user->data->user_login;
}
function ucam_profile_func($atts = [ ]) {
    $conn = IbisClientConnection::createConnection();
    $full = new PersonMethods($conn);
    $bpid = bp_displayed_user_id();
    $crsid = get_display_name($bpid);
    $b = $full->getAttributes('crsid', $crsid, 'all_attrs');
    $data = array();
    foreach ($b as $c) {
      $data[$c->scheme] = $c->value;
    }
    foreach ($data as $k => $v) {
      echo ucfirst($k) . ' : ' . $v;
      echo '<br >';
    }
    echo '<img src="https://www.lookup.cam.ac.uk/person/crsid/'. $crsid. '/photo-1.jpg" />';
}

add_shortcode( 'ucamprofile', 'ucam_profile_func');
