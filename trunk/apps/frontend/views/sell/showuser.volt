<script>
jQuery(document).ready(function(){
    var gyInput = $('.gy_step li input');
    inputFb(gyInput);
    
    /* 筛选条件 点击显示选项效果 */
    $('.list_xiang a').click(function(){
        if($(this).hasClass('all')){
            $(this).parents('.list').find('.xiang').hide();
        }else{
            $(this).parent().find('.all').css('background', '#05780a');
            $('.list_xiang a').removeClass('active');
            $(this).addClass('active');
            $(this).parents('.list').find('.xiang').show();
        };
    });
    
    /* 筛选条件 点击选中选项效果 */
    $('.xiang a').click(function(){
        $(this).parent().find('img').hide();
        $(this).find('img').show();
    });
     //鼠标悬停效果 
   $('.south-west-alt').powerTip({ placement: 'sw-alt' });
     /* 采购 点击弹层效果 */
    $('#cg_btn').click(function(){
       
       var id=$(this).attr("name");
        $("#sell_id").val(id);
        $('.layer').show();
        $('.cg_alerter').show();
    });
    /* 采购 点击弹层效果 */
    $('.cg_btn').click(function(){
      
        var id=$(this).attr("name");
        $("#sell_id").val(id);
        var price=$("#p"+id+"").val();
         $("#price_unit").text(price);
        $('.layer').show();
        $('.cg_alerter').show();
    });
  
    
    /* 关闭按钮 */
    $('.close_btn').click(function(){
        
        if(!$(this).parents('.alert_box')){
            $(this).parent().hide();
            $('.layer').hide();
        }else{
            $(this).parent().hide();
            $('.alert_box').hide();
            $('.layer').hide();
        };
    });
    /* 关闭按钮 */
    $('#close_btn').click(function(){
        
        if(!$(this).parents('.alert_box')){
            $(this).parent().hide();
            $('.layer').hide();
        }else{
            $(this).parent().hide();
            $('.alert_box').hide();
            $('.layer').hide();
        };
    });
    /* 关闭按钮 */
    $('.btn2 f-fl').click(function(){
      
        if(!$(this).parents('.alert_box')){
            $(this).parent().hide();
            $('.layer').hide();
        }else{
            $(this).parent().hide();
            $('.alert_box').hide();
            $('.layer').hide();
        };
    });
    
    /* 鼠标经过表格行的效果 */
    $('tr').hover(function(){
        if($(this).hasClass('title')){
            return;
        }else{
            $(this).addClass('bg_line');
            if($(this).prev('tr').index() == 0){
                return;
            }else{
                $(this).prev('tr').addClass('hover');
            };
        };  
    }, function(){
        if($(this).hasClass('title')){
            return;
        }else{
            $(this).removeClass('bg_line');
            if($(this).prev('tr').index() == 0){
                return;
            }else{
                $(this).prev('tr').removeClass('hover');
            };
        };  
    });
});
function loading(id,type){

     if(type=="showc"){
      $('#xiang').load("http://mdg.ync365.com/sell/showcategorys",{id:id},function(){});
     }
     if(type=="showa"){
      $('#xiangs').load("http://mdg.ync365.com/sell/showaddress",{id:id},function(){});
     }    
}
</script>

<script>
 $(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareas"},
         defaultParentId : 0,
     });   

$("#caigou").validator({

 fields:  {
    'mobile': 'required; mobile',
    'number':'required;digits',
    'price' :'required;digits',
    'username':'required;Chinese',
    
 },

});
</script>