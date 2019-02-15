#!/usr/bin/php
<?php

require_once "ibisclient/client/IbisClientConnection.php";
require_once "ibisclient/methods/InstitutionMethods.php";

$conn = IbisClientConnection::createConnection();
$im = new InstitutionMethods($conn);

$people = $im->getMembers("UIS");
$im->get

print("Members of University Information Services:\n");
foreach ($people as $person)
{
    print("\n");
    print_r($person);
}

?>
