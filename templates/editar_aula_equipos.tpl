<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Administración de los equipos del aula: <span class='stitle'>{$aula}</span></h1>
    </div>
    <!-- /.col-lg-12 -->
</div>

<div class="row">
    <form action='{$urlform}' method='post'> 
    
        <div class="col-lg-5">
            <label>Equipos del aula</label>
            <select name='delcomputer[]' size='15' multiple class="form-control" data-btn="btnremove"> 
                {foreach from=$equipos.ingroup item=o}
                    <option value="{$o}">{$o}</option> 
                {/foreach}
            </select> 
        </div>
        
        <div class="col-lg-2" style='height:250px;'>
            <div class="span6 text-center center" style='height:50%;'>
                <button type="submit" class="btn btn-primary btn-circle btn-xl btnremove disabled" style="margin-top: 50px;">
                    <i class="fa fa-arrow-right hidden-xs"></i>
                    <i class="fa fa-arrow-down visible-xs"></i>
                </button>
            </div>
            <div class="span6 text-center center" style='height:50%;'>
                <button type="submit" class="btn btn-primary btn-circle btn-xl btnadd disabled" style="margin-top: 50px;">
                    <i class="fa fa-arrow-left hidden-xs"></i>
                    <i class="fa fa-arrow-up visible-xs"></i>
                </button>
            </div>
        </div>
        
        <div class="col-lg-5">
            <label>Resto de equipos (sin aula asignada)</label>
            <select name='addcomputer[]' size='15' multiple class="form-control" data-btn="btnadd"> 
                {foreach from=$equipos.outgroup item=o}
                    <option value="{$o}">{$o}</option> 
                {/foreach}
            </select> 
            <input type="hidden" name="aula" value="{$aula}"> 
        </div>
    
    </form> 
</div>

{literal}
<script type="text/javascript">
<!--

$(document).ready(function() {
    $('select').on('change', function(event) {
        if( $(this).val() == null || $(this).val().length < 1 ) {
            $("."+ $(this).data('btn') ).addClass('disabled');
        }
        else {
            $("."+ $(this).data('btn') ).removeClass('disabled');
        }
    });
});
-->
</script>
{/literal}