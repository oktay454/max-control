<?php


/*
*
*  Modulo equipos
*
*/
global $gui;
global $permisos;
global $site;
global $module_actions;


$url=new URLHandler();

$active_module=$url->get("module");
$active_action=$url->get("action");
$active_subaction=$url->get("subaction");


if(pruebas) {
    error_reporting(E_ALL);
}

if ( ! $permisos->is_connected() ) {
    $url->ir("","");
}

if ( ! $permisos->is_admin() ) {
    $gui->session_error("Sólo pueden acceder al módulo de equipos los administradores.");
    $url->ir("","");
}
/*************************************************/


$module_actions=array(
        "ver" => "Ver equipos",
        "aulas" => "Ver aulas",
);


// si tiene permisos de administrador mostrar submenus

#if ($permisos->is_admin() ) {
#    $module_actions['backhardding']="Backharddi";
#}


/*************************************************/

if ($active_action == "") {
    $url->ir($active_module, "ver");
}

if ($active_action == "ver") {
    $button=leer_datos('button');
    $gui->debug("button='$button'");
    if( $button !='' && $button != "Buscar"){
        $url->ir($active_module, "update");
    }

    // mostrar lista de equipos
    $ldap=new LDAP();
    $filter=leer_datos('Filter');
    if ( ($filter != '') && (substr($filter, -1) != '$') )
        $filter.='$';
    $equipos=$ldap->get_computers( $filter );
    $urlform=$url->create_url($active_module, $active_action);
    $urleditar=$url->create_url($active_module,'editar');
    $urlborrar=$url->create_url($active_module,'borrar');
    
    $data=array("equipos" => $equipos, 
                "filter" => $filter, 
                "urlform" => $urlform, 
                "urleditar"=>$urleditar,
                "urlborrar"=>$urlborrar);
    $gui->add( $gui->load_from_template("ver_equipos.tpl", $data) );
}

if ($active_action == "editar") {
    $hostname=$url->get("subaction");
    $ldap=new LDAP();
    $equipo=$ldap->get_computers($hostname.'$');
    
    if( ! $equipo ){
        $gui->session_error("Equipo '$hostname' no encontrado");
        $url->ir($active_module, "ver");
    }
    
    $aulas=$ldap->get_aulas();
    $urlform=$url->create_url($active_module, 'guardar');
    
    $data=array("hostname"=>$hostname, 
                "aulas" => $aulas,
                "u"=>$equipo[0],
                "urlform"=>$urlform,
                "action" => "Editar");
    
    $gui->add( $gui->load_from_template("editar_equipo.tpl", $data ) );
}


if ($active_action == "update") {
    $data=array("urlaction"=>$url->create_url($active_module, 'updatedo'));
    $gui->add( $gui->load_from_template("update_equipos.tpl", $data) );
}

if ($active_action == "updatedo") {
    $ldap=new LDAP();
    $equipos=$ldap->get_computers();
    //$gui->debuga($equipos);
    foreach($equipos as $equipo) {
        $equipo->getMACIP();
    }
}

if ($active_action == "borrar") {
    $data=array(
            "urlaction"=>$url->create_url($active_module, 'borrardo'),
            "equipo" =>leer_datos('subaction')
                );
    $gui->add( $gui->load_from_template("borrar_equipo.tpl", $data) );
}

if ($active_action == "borrardo") {
    $equipo=leer_datos('equipo');
    $ldap=new LDAP();
    $equipos=$ldap->get_computers($equipo . '$');
    $gui->debuga($equipos);
    if ( isset($equipos[0]) ) {
        $gui->debuga($equipos[0]);
        //$equipos[0]->delComputer();
        $gui->session_info("Equipo '$equipo' borrado del dominio.");
    }
    else {
        $gui->session_error("El equipo '$equipo' no se ha encontrado");
    }
    $url->ir($active_module, "ver");
}


if ($active_action == "guardar") {
    $hostname=leer_datos('hostname');
    $ldap=new LDAP();
    $equipos=$ldap->get_computers($hostname.'$');
    if ( ! isset($equipos[0]) )
        $url->ir($active_module, "ver");
    
    $equipo=$equipos[0];
    //$gui->add( "<pre>". print_r($_POST, true) . "</pre>" );
    $equipo->set($_POST);
    $res=$equipo->save( array('sambaProfilePath', 
                         'ipHostNumber', 
                         'ipNetmaskNumber', 
                         'ipNetmaskNumber', 
                         'macAddress', 
                         'bootFile') );
    
    //$gui->add( "<pre>guardado=$res". print_r($equipo, true) . "</pre>" );
    if ($res) {
        $gui->session_info("Equipo guardado correctamente.");
        $url->ir($active_module, "ver");
    }
    else {
        $gui->session_error("Error guardando datos, por favor inténtelo de nuevo.");
        $url->ir($active_module, "editar", $hostname);
    }
}

/*****************   aulas   ************************/


if ($active_action == "aulas" && $active_subaction == '') {
    $button=leer_datos('button');
    $gui->debug("button='$button'");
    if( $button !='' && $button != "Buscar"){
        $url->ir($active_module, "aulas", "nueva");
    }
    
    // mostrar lista de aulas
    $ldap=new LDAP();
    $filter=leer_datos('Filter');
    $aulas=$ldap->get_aulas($filter);
    $urlform=$url->create_url($active_module, $active_action);
    $urlprofesores=$url->create_url($active_module,'aulas', 'miembros');
    $urlequipos=$url->create_url($active_module,'aulas', 'equipos');
    $urlborrar=$url->create_url($active_module,'aulas', 'borrar');
    
    $data=array("aulas" => $aulas, 
                "filter" => $filter,
                "urlform" => $urlform,
                "urlprofesores"=>$urlprofesores,
                "urlequipos"=>$urlequipos,
                "urlborrar" =>$urlborrar);
    $gui->add( $gui->load_from_template("ver_aulas.tpl", $data) );
}


if ($active_action == "aulas" && $active_subaction == 'miembros') {
    $aula=leer_datos('args');
    $ldap=new LDAP();
    $miembros=$ldap->get_teacher_from_aula($aula);
    //$gui->add( "<pre>". print_r($miembros, true) . "</pre>" );
    
    $urlform=$url->create_url($active_module, $active_subaction, 'guardar');
    
    $data=array("aula"=>$aula, 
                "miembros"=>$miembros, 
                "urlform" => $urlform);
    
    $gui->add( $gui->load_from_template("editar_aula.tpl", $data) );
}

if ($active_action == "miembros" && $active_subaction == 'guardar') {
    //$gui->add( "<pre>".print_r($_POST, true)."</pre>" );
    /*
    Array
        (
            [addtogroup] => Añadir usuarios al grupo
            [adduser] => profe3
            [aula] => grupoprueba
        )
    */
    $editaaula=leer_datos('aula');
    $adduser=leer_datos('adduser');
    /*
    Array
    (
        [deluser] => profe2
        [delfromgroup] => Quitar
        [aula] => grupoprueba
    )
    */
    $deluser=leer_datos('deluser');
    $ldap= new LDAP();
    
    if ( $adduser != '') {
        // añadir profesor al aula $editaaula
        $aula=$ldap->get_aula($editaaula);
        $aula->newMember($adduser);
        $url->ir($active_module, "aulas", "miembros/$editaaula");
    }
    elseif ( $deluser != '') {
        // quitar profesor al aula $editaaula
        $aula=$ldap->get_aula($editaaula);
        $aula->delMember($deluser);
        $url->ir($active_module, "aulas", "miembros/$editaaula");
    }
    else {
        $gui->session_error("No se ha seleccionado ningún profesor.");
        $url->ir($active_module, "aulas", "miembros/$editaaula");
    }
}


/****************************************************/
if ($active_action == "aulas" && $active_subaction == 'equipos') {
    $aula=leer_datos('args');
    $ldap=new LDAP();
    $all=$ldap->get_computers_in_and_not_aula($aula);
    
    //$gui->add( "<pre>". print_r($all, true) . "</pre>" );
    
    $urlform=$url->create_url($active_module, $active_subaction, 'guardar');
    
    $data=array("aula"=>$aula, 
                "equipos"=>$all, 
                "urlform" => $urlform);
    
    $gui->add( $gui->load_from_template("editar_aula_equipos.tpl", $data) );
}

if ($active_action == "equipos" && $active_subaction == 'guardar') {
    //$gui->add( "<pre>".print_r($_POST, true)."</pre>" );
    $aula=leer_datos('aula');
    /* Add computer
    Array
    (
        [addtogroup] => Añadir usuarios al grupo
        [addcomputer] => wxp64
        [aula] => grupoprueba
    )
    */
    $addcomputer=leer_datos('addcomputer');
    /* del computer
    Array
    (
        [delcomputer] => mario-desktop
        [delfromgroup] => Quitar
        [aula] => aula primaria 1
    )
    */
    $delcomputer=leer_datos('delcomputer');
    $ldap=new LDAP();
    
    if ( $addcomputer != '') {
        // equitar el sambaProfilePath del equipo con el aula
        $equipo=$ldap->get_computers($addcomputer .'$');
        if ( isset($equipo[0]) ) {
            $equipo[0]->sambaProfilePath=$aula;
            $equipo[0]->ldapdata['sambaProfilePath']=$aula;
            $res=$equipo[0]->save( array('sambaProfilePath') );
            if ($res) {
                $gui->session_info("Equipo $addcomputer añadido al aula $aula correctamente.");
                $equipo[0]->boot($aula);
            }
            else
                $gui->session_error("No se puedo añadir el equipo $addcomputer al aula $aula.");
            //$gui->add( "<pre>". print_r($equipo[0]->show(), true) . "</pre>" );
        }
        else {
            $gui->session_error("No se pudo encontrar el equipo '$addcomputer'");
        }
        
        $url->ir($active_module, "aulas", "equipos/$aula");
    }
    elseif ( $delcomputer != '') {
        // borrar el sambaProfilePath
        $equipo=$ldap->get_computers($delcomputer .'$');
        if ( isset($equipo[0]) ) {
            $res = $equipo[0]->empty_attr( 'sambaProfilePath' );
            if ($res) {
                $gui->session_info("Equipo $addcomputer quitado del aula $aula correctamente.");
                $equipo[0]->boot('default');
            }
            else
                $gui->session_error("No se puedo quitar el equipo $addcomputer del aula $aula.");
            //$gui->add( "<pre>". print_r($equipo[0]->show(), true) . "</pre>" );
        }
        else {
            $gui->session_error("No se pudo encontrar el equipo '$addcomputer'");
        }
        $url->ir($active_module, "aulas", "equipos/$aula");
    }
    else {
        $gui->session_error("No se ha seleccionado ningún equipo.");
        $url->ir($active_module, "aulas", "equipos/$aula");
    }
}



if ($active_action == "aulas" && $active_subaction == 'nueva') {
    $group=new GROUP();
    $url=new URLHandler();
    $urlform=$url->create_url($active_module, $active_action, 'aulaguardar');
    
    $data=array("u"=>$group,
                "urlform"=>$urlform,
                "action" => "Editar");
    
    $gui->add( $gui->load_from_template("add_aula.tpl", $data ) );
}

if ($active_action == "aulas" && $active_subaction == 'aulaguardar') {
    //$gui->add( "<pre>".print_r($_POST, true)."</pre>" );
    /*
    Array
    (
        [cn] => aaaaa
        [description] => 
        [add] => Añadir
    )
    */
    
    if ( leer_datos('cn') == '' ) {
        $gui->session_error("Error, identificador de aula vacío.");
        $url->ir($module, "nueva");
    }
    
    $group=new AULA($_POST);
    if ( $group->newAula() )
        $gui->session_info("Aula '".$group->cn."' añadida correctamente.");
    
    $url->ir($active_module, "aulas");
}


if ($active_action == "aulas" && $active_subaction == 'borrar') {
    $aula=leer_datos('args');
    $gui->add("borrar aula: $aula");
    $urlform=$url->create_url($active_module, $active_action, 'aulaborrar');
    $data=array("aula" => $aula,
                "urlform"=>$urlform);
    
    $gui->add( $gui->load_from_template("del_aula.tpl", $data) );
}

if ($active_action == "aulas" && $active_subaction == 'aulaborrar') {
    $gui->debug( "<pre>".print_r($_POST, true)."</pre>" );
    /*
    Array
    (
        [aula] => aula primaria 4
        [confirm] => Confirmar
    )
    */
    $aula=leer_datos('aula');

    if ($aula == '') {
        $gui->session_error("No se pudo encontrar el aula '$aula'");
        $url->ir($active_module, "aulas");
    }

    $ldap=new LDAP();
    $aulas=$ldap->get_aula($aula);
    
    $gui->debug( "<pre>". print_r($aulas, true) . "</pre>" );
    
    if ($aulas->cn != $aula) {
        $gui->session_error(" El aula '$aula' no existe.");
        $url->ir($active_module, "aulas");
    }
    
    if ( $aulas->delAula() )
        $gui->session_info("Aula '$aula' borrada.");
    
    $url->ir($module, "aulas");

}

?>
