

<h3>Editando usuario  <span class='stitle'>{$username}</span></h3> 
 

 <form action='{$urlform}' method='post' onsubmit="return checkpass();"> 
    <table class='formTable'> 
    <tr> 
        <td class='tright'><span class="ftitle">Identificador:</span></td>
        <td>
            {$u->attr('cn')}
        </td>
    </tr>
    <tr> 
        <td class='tright'><span class="ftitle">Nombre:</span></td>
        <td>
            <input type='text' class='inputText' name='givenname' id='givenname' value="{$u->attr('givenname')}" /> 
        </td>
    </tr>
    <tr> 
        <td class='tright'><span class="ftitle">Apellidos:</span></td>
        <td>
            <input type='text' class='inputText' name='sn' id='sn' value="{$u->attr('sn')}" /> 
        </td>
    </tr>

    <tr> 
        <td class='tright'><span class="ftitle">Comentario:</span></td>
        <td>
            <input type='text' class='inputText' name='description' id='description' size='70' value="{$u->attr('description')}" /> 
        </td>
    </tr>

    <tr>
        <td class='tright'><span class='ftitle'>Rol (permisos):</span></td> 
        <td> 
            <select name='role' id='role' > 
                <option value='' {if $u->get_role() == ''}selected='selected'{/if}>Alumno</option> 
                <option value='teacher' {if $u->get_role() == 'teacher'}selected='selected'{/if}>Profesor</option> 
                <option value='tic' {if $u->get_role() == 'tic'}selected='selected'{/if}>Coordinador TIC</option> 
                {if $permisos->is_admin() }
                <option value='admin' {if $u->get_role() == 'admin'}selected='selected'{/if}>Administrador</option> 
                {/if}
            </select> 
        </td> 
    <tr>


    <tr>
        <td class='tright'><span class='ftitle'>Acceso a consola:</span></td> 
        <td> 
            <select name='loginshell' id='loginshell' > 
                <option value='/bin/false' {if $u->attr('loginshell') == '/bin/false'}selected='selected'{/if}>Sin acceso a shell</option> 
                <option value='/bin/bash' {if $u->attr('loginshell') == '/bin/bash'}selected='selected'{/if}>Con acceso a shell (bash)</option> 
            </select> 
        </td> 
    <tr>

    <tr>
        <td class='tright'><span class='ftitle'>Cambiar contraseña:</span></td> 
        <td>
            <input type='password' class='inputText' name='newpwd' id='newpwd' value="" autocomplete="off" /> (dejar vacío para no cambiar)
        </td>
    <tr>
    <tr>
        <td class='tright'><span class='ftitle'>Confirmar contraseña:</span></td> 
        <td>
            <input type='password' class='inputText' name='newpwd2' id='newpwd2' value="" autocomplete="off" onblur='javascript:checkpass();' /> 
            <span class="error" style="display:none;" id='badpassword'>Las contraseñas no coinciden</span>
        </td>
    <tr>

    </tr> 
    <tr> 
        <td></td> 
        <td> 
        <input class='inputButton' type='submit' name='{$action}' value="Guardar" alt="Guardar" /> 
        <input type='hidden' name='cn' value='{$u->attr('cn')}' />
        <input class='inputButton' type='button' name='reset' value="Resetear perfil" 
         title="Borra todos los archivos personales de este usuario" onclick="javascript:resetProfile('{$username}');"/> 
        </td> 
    </tr> 
    </table> 
    </form> 

<script type="text/javascript">
    var ajaxurl="{$baseurl}/index.php?ajax=1";
    var baseurl="{$baseurl}/usuarios/";
</script>

{literal}
<script type="text/javascript">
function checkpass() {
    if ( $('#newpwd')[0].value !=  $('#newpwd2')[0].value) {
        $('#badpassword')[0].style.display='';
        return false;
    }
    else {
        $('#badpassword')[0].style.display='none';
        return true;
    }
}

function resetProfile(username) {
    document.location.href=baseurl+"resetprofile/"+username;
}
</script>
{/literal}

{*
{if $DEBUG}
{debug}
{/if}
*}
