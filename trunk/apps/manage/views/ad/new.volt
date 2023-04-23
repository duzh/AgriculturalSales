{{ form("ad/create", "method":"post","id":"myad") }}

<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/pingdao.css" />
<table width="100%">
    <tr>
        <td align="left">{{ link_to("/sell", "返回") }}</td>
        <td align="right">{{ submit_button("Save") }}</td>
    </tr>
</table>
{{ content() }}
<div align="center">
    <h1>新增广告位</h1>
</div>

<table>
    <tr>
        <td align="right">
            <label for="title">广告位标题</label>
        </td>
        <td align="left">{{ text_field("title") }}</td>
    </tr>
  
       <td align="right">
            <label for="category">上传图片:</label>
        </td>
        <td align="left">
                <div class="gy_step">
              
                    <div class="load_img f-fl" id="show_img">
                        <div class="load_btn">
                            <span style="float: left;">上传产品图片</span>
                            <input type="file" value="浏览" id="img_upload" data-rule="required;" >
                        </div>
                        <p>图片大小不超过2M，支持jpg、png、gif格式（使用高质量图片，可提高成交的机会）</p>
                        {% for key, file in tfile %}
                        <dl>
                            <dt><img src="{{ file.file_path }}" width="200" height="200"><a href="javascript:;" onclick="closeImg(this, {{ file.id }});">关闭</a></dt>
                        </dl>
                        {% endfor %}
                    </div>
                  </div>
           </td>
         </tr>
         
     <tr>
        <td align="right">
            <label for="role">是否显示</label>
        </td>
        <td align="left">
          {{ radio_field("is_show", "value" : "1") }}是
          {{ radio_field("is_show", "value" : "0") }}否
          
        </td>
    </tr>
     <tr>
        <td align="right">
            <label for="title">广告位链接</label>
        </td>
        <td align="left">{{ text_field("adsrc") }}</td>
    </tr>
    <tr>

        <td></td>
        <td>{{ submit_button("确定添加") }}</td>
    </tr>
</table>

</form>
<script>
 function fun(){
       var opts = document.getElementById("u69_input");
       var opts1 = document.getElementById("u72_input");
       for(var i=0;i<opts.options.length;i++){
          if("11"==opts.options[i].value){
              opts.options[i].selected = 'selected';     
          }
       }
       for(var i=0;i<opts1.options.length;i++){
          if("123"==opts1.options[i].value){
              opts1.options[i].selected = 'selected';     
          }
       }       
}
function change1(){
      var selectvalue = $("#category").val();
       $("#categorys  option").each(function() {
        $(this).remove();
       });
      $.ajax({type: "post",url:"http://mdg.ync365.com/manage/sell/showcategory",data:{selectvalue: selectvalue},dataType: "text",
      success: function(data) {
        if(data){
            jQuery(data).appendTo(jQuery("#categorys"));
        }else{
            jQuery("<option value='0'>请选择</option>").appendTo(jQuery("#categorys"));
        }
        
      }
  });
      
}
</script>
<script>
$("#checkuser").bind("click",
    function() {
        var str = $("#user").val();
        if (str=="") {
            alert("请填写查询内容");
        }else {
            $('#showuser').load("http://mdg.ync365.com/manage/sell/showuser", {str:str},function() {
             
            })
        }
});
</script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
<script>
    
$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareas"},
    defaultParentId : 0,
    style : {"width" : 140}
});

</script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/inputFocus.js"></script>
<script type="text/javascript" src="/uploadify/jquery.uploadify.min.js?var=<?= rand(1, 9999) ?>" ></script>
<link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css">
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}validator/jquery.validator.css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/jquery.validator.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/local/zh_CN.js"></script>
<script>
jQuery(document).ready(function(){
    var gyInput = $('.gy_step li input');
    inputFb(gyInput);

    $(".gy_step").bind("click",function(){
        $(this).addClass("active").siblings().removeClass("active");
    })

    $('#goods_unit').change(function() {
        var cur_unit = $(this).find('option:selected').text();
        cur_unit = cur_unit.split('/');
        $('#cur_unit').html(cur_unit[1]);
    })

    $('#img_upload').uploadify({
        'swf'      : '/uploadify/uploadify.swf',
        'uploader' : '/upload/tmpfile',
        'formData' : {
            'sid' : '{{ sid }}',
        },
        'buttonClass' : 'upload_btn',
        'buttonText'  : '浏览',
        'multi'       : false,
        onDialogOpen : function() {
            $('.gy_step').eq(1).addClass("active").siblings().removeClass("active");
        },

        onUploadSuccess  : function(file, data, response) {
            data = $.parseJSON(data);
            alert(data);
            if(data.status) {
                $('#show_img').append(data.html);
            }
        }
    });
});

function selectUnit(val) {
    alert(val);
}



// 删除图片
function closeImg(obj, id) {
    $.getJSON('/upload/deltmpfile', {id : id}, function(data) {
        alert(data.msg);
        if(data.state) {
            $(obj).parents('dl').slideUp();
        }
    });
}
$("#myad").validator({
     fields:  {
         title:"required;",
         adsrc:"required;url;",
     },
    
});
</script>

<style>
.upload_btn {width: 121px;height: 31px;line-height: 31px;text-align: center;background: url({{ constant('STATIC_URL') }}mdg/images/yz_btn.png) no-repeat;background-position: 0 0;top: 0;left: 88px;color: #7f7f7f; margin-left:75px;}
</style>
