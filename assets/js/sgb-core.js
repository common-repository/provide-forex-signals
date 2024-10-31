jQuery(document).ready(function($){
    "use strict";
var lastID = '';
var count = '';
   var signalbox_id = jQuery('#sbglist_result').data('id');

   if(typeof signalbox_id !== "undefined"){
   var i = setInterval(function() { updateView(signalbox_id) }, 5000);
   updateView(signalbox_id);
   }
$('.sbg-btn.gsbtn').click(function(){

    var id = $(this).data('id');
    var form = $(this).closest("form")[0];
    var checkVal = form.checkValidity();
    if(checkVal){
        adddeleteSignal('add',id,this);
    }else{
        form.reportValidity();
    }
    
})

$('.btn-sbg.dsbtn').click(function(){
    var id = $(this).data('id');
    adddeleteSignal('delete',id,this);
})
 function updateView(id) {

       $.ajax(
        {
            type: "GET",
            url: sbg_obj.rest_url+"sbg/v1/signals/"+id,
            dataType : 'json',
            success: function(resp){
                if (typeof resp['data'][0] === "undefined") {
                    $('#sbglist_result').html("No Signals !");
                    return;
                }
                if(lastID == resp['data'][0].id &&  count == resp['count']){
                    return;
                }
                lastID = resp['data'][0].id;
                count = resp['count'];

                var html ='';
                $.each(resp['data'], function(i,v) {
                    //console.log(resp);
                    html += '<div class="signalbox-widget sbg-sm-20" id="signalID'+v.id+'"><div>';
                    html +='<h3><i class="icon-arrow-'+v.direction+'"></i> '+v.pair+'</h3>';
                    html +='<div><h5>SR</h5>'+v.rate+'</div>';
                    html +='<p>'+v.description+'</p>';
                //    html +='<a href="'+v.tradeurl+'" class="btn-sbg">Trade</a>';
                    html+='</div></div>';
                    
                });

               	$('#sbglist_result').html(html).hide().fadeIn(500);
            }
        });
}

function adddeleteSignal(action,id,that=''){
        var data = '';
        if(action == 'add'){
            data = $(that).closest("form").serialize();
         }
       $.ajax(
            {
                type: "POST",
                url: sbg_obj.ajax_url,
                data:{action:'sbg_update_signals',method: action, id: id, data:data},
               // dataType : 'json',
                success: function(resp){
                    if(resp !="success"){
                        alert(resp);
                        return;
                    }
                    if(action == 'add'){
                        location.reload();
                    }else{
                        $(that).closest('.signalbox-widget').remove();
                    }
                }
            });
    }


});