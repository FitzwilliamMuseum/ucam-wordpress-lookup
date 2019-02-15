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

function getConnection() {
    $conn = IbisClientConnection::createConnection();
    return $conn;
}

function ucam_staff_func($atts = []) {
    $conn = IbisClientConnection::createConnection();
    $im = new InstitutionMethods($conn);
    $full = new PersonMethods($conn);
    $people = $im->getMembers("FITZM");
    $b = $full->getAttributes('crsid', 'dejp3', 'all_attrs');
    $data = array();
    foreach ($b as $c) {
        $data[$c->scheme] = $c->value;
    }
//    foreach ($data as $k => $v) {
//        echo ucfirst($k) . ' : ' . $v;
//        echo '<br >';
//    }
//    echo '<img src="https://www.lookup.cam.ac.uk/person/crsid/dejp3/photo-1.jpg" />';

    print("<h3>Fitz Museum Staff</h3>");
    foreach ($people as $person) {
        $p = $person;
        $d = array($p)[0];
        echo '<br />';
        echo '<h4>' . $d->registeredName . '</h4>';
        echo 'CRSID: ' . $p->identifier->value;
        echo '<br />';
        $full = new PersonMethods($conn);
        $a = $full->getPerson('crsid', $p->identifier->value, 'all_attrs');
        echo 'Visible name: ' . $a->visibleName;
        echo '<br />';
        echo 'Surname: ' . $a->surname;
        echo '<br />';
        echo 'Affiliation: ' . $a->misAffiliation;
        echo '<br />';
        echo '<img src="https://www.lookup.cam.ac.uk/person/crsid/' . $p->identifier->value . '/photo-1.jpg" width="100"/>';
    }


}

function ucam_profile_func($atts = [ ]) {
    $conn = IbisClientConnection::createConnection();
    $full = new PersonMethods($conn);
    var_dump($atts);
    $crsid = bp_displayed_user_id();
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

add_shortcode('ucamlookup', 'ucam_staff_func');
add_shortcode( 'ucamprofile', 'ucam_profile_func');
