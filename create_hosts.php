<?php
/*  generate default config  */

include('/etc/max-control/conf.inc.php');
include('modules/common.inc.php');

class GUI {
    function debug($txt) {
        if($txt == '') return;
        //fwrite(STDERR, "D: ".print_r($txt, true)." \n");
    }
    function debuga($txt) {
        if($txt == '') return;
        //fwrite(STDERR, "D: ".print_r($txt, true)." \n");
    }
    function session_info($txt) {
        $this->debug("SESSION INFO: ".$txt);
    }
    function session_error($txt) {
        $this->debug("SESSION ERROR:".$txt);
    }
}

$gui = new GUI();

include("classes/ldap.class.php");
include("classes/winexe.class.php");

/*
$LDAP_BASEDN=readLDAPFile('/etc/ldap.conf', 'base');
define('LDAP_BASEDN', $LDAP_BASEDN);
$LDAP_BINDDN=readLDAPFile('/etc/ldap.conf', 'rootbinddn');
define('LDAP_BINDDN', $LDAP_BINDDN);
$LDAP_BINDPW=file_get_contents('/etc/ldap.secret');
define('LDAP_BINDPW', $LDAP_BINDPW);
*/


/* usuario creado por max-control */
/*
define('LDAP_ADMIN', '$LDAP_ADMIN');
define('LDAP_PASS', '$LDAP_PASS');
*/

/*
exec("net getdomainsid | grep domain", $output);
$parts = preg_split ("/\s+/", $output[0]);
$LDAP_DOMAIN=$parts[3];
define('LDAP_DOMAIN', $LDAP_DOMAIN);
*/

$LDAP_ADMIN='max-control';
$LDAP_PASS=createPassword();





function create_host($hostname, $i) {
    $ldap=new LDAP();
    
    $data=Array("hostname" => $hostname,
                "ipHostNumber"=> "192.168.1.1$i",
                "macAddress" => "08:00:27:fc:4f:$i");
    $computer=new COMPUTER();
    if ( $computer->newComputer($data) )
        echo "Equipo '$hostname' creado\n";
    else
        echo "No se pudo crear el equipo '$hostname'\n";
}

$prefix="pc";
for($i=1; $i<10; $i++) {
    create_host("$prefix$i", $i);
}
die("fin\n");


