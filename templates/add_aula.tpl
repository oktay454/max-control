<h3>Añadir aula</h3>


<form action='{$urlform}' method='post'> 
    <table class='formTable'> 
        <tr> 
            <td class='tright'><span class="ftitle">Nombre del aula:</span></td> 
            <td>
                <input type='text' class='inputText' name='cn' id='cn' autocomplete="off" onblur='javascript:usedcn(this.value);' />
                <span class="error" style="display:none;" id='groupnotvalid'>El identificador está ocupado</span>
                <span class="note" style="display:none;" id='groupvalid'>El identificador está libre</span>
                <span class="error" style="display:none;" id='groupempty'>El identificador no puede estar vacío</span>
            </td> 
        </tr> 

        <tr> 
            <td class='tright'><span class='ftitle'>Comentario:</span></td> 
            <td><input type='text' class='inputText' name='description' autocomplete="off" /></td> 
        </tr> 

        <tr> 
            <td></td> 
            <td> 
            <input class='inputButton' type='submit' name='add' value="Añadir" alt="Añadir" /> 
            </td> 
        </tr> 
    </table> 
</form>

<script type="text/javascript">
    var ajaxurl="{$baseurl}/index.php?ajax=1";
</script>

{literal}
<script type="text/javascript">
<!--
function usedcn(cn) {
    if ( cn == '' ) {
        $('#groupempty')[0].style.display='';
        return false;
    }
    $('#groupempty')[0].style.display='none';
    $.ajax({
      type: "POST",
      url: ajaxurl,
      data: "accion=usedaula&cn="+cn,
      success: function(data) {
        if (data == 'used') {
            $('#groupnotvalid')[0].style.display='';
            $('#groupvalid')[0].style.display='none';
            return false;
        }
        else if (data == 'free') {
            $('#groupnotvalid')[0].style.display='none';
            $('#groupvalid')[0].style.display='';
            //alert('usuario disponible');
        }
        else {
            alert('Error servicio AJAX');
        }
      }
    });
}
-->
</script>
{/literal}