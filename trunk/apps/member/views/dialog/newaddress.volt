<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.css" />
<div class="dialog">
        {{ form("#", "method":"post","id":"newaddress") }}
        <title>申请负责区域</title>
        <div class="apply-service-station f-oh f-fs14" style="height:164px; border-bottom:solid 2px #69ae6c;">
            <div class="box province f-fl">
                <select name="province" multiple  class="selectAreas f-fs14" id="provinceId" onchange="removeCalm()">
                    <option value="" >省</option>
                </select>
            </div>

            <div class="box city f-fl">
                <select name="city" multiple class="selectAreas f-fs14" id="city">
                    <option value="">市</option>
                </select>
            </div>

            <div class="box county f-fl">
                <select name="county" multiple class="selectAreas f-fs14" id="town" onchange="selectCalm(this.value)"   >
                    <option value="">县</option>
                </select>
            </div>

            <div class="box town f-fl" style="width:169px; height:160px; overflow-y:auto; ">
                <div  id='calmId'>
                <?php if(isset($chidData)){
                    echo "<input type='checkbox' name='town'  value='' onclick='checkBoxAll(this)' />全选";
                    foreach ($chidData as $val) {
                        $checked = '';
                        if(in_array($val['id'], $town) ) {
                            $checked = " checked ";
                        }
                        echo "<label class='f-db f-oh' style='margin-bottom:4px;'><input type='checkbox' name='town[]' value='{$val['id']}' {$checked} >{$val['name']}</label>";
                    }
                }?>
                </div>
            </div>
            <i id="address_tip"></i>
        </div>
        </form>
</div>
<script type="text/javascript">

function removeCalm(e) {
    $("#calmId").empty();

}

// 地区联动
$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getfullAddress"},
    defaultParentId : 0,
    <?php if(isset($areas)&&$areas) {
        echo "texts:[{$areas}],";
    }?>
    style : {"width" : 169, "height": 160}
});

//验证
$('#newaddress').validator({
        fields:  {
             calm:"服务区域:required;",
        }
});

//全选
function checkBoxAll(obj){
    //alert($(obj).prop('checked'));
    var val = $(obj).prop('checked');
    $(obj).parent().find('input').each(function(index, element){
        $(obj).parent().find('input').eq(index).prop('checked', val);
    });
};

function selectCalm(e){


   jQuery.getJSON('/member/ajax/getaddress', {parent_id:e}, function(data) {

        var tt= " <input type='checkbox' name='town'  value='' onclick='checkBoxAll(this)' />全选";
        $.each(data, function(k, v) {
            tt +="<label class='f-db f-oh'><input type='checkbox' name='town[]' data-rule='checked' value='"+ data[k].region_id+"'>"+data[k].region_name+" </label>";
        });
     $("#calmId").html(tt);
    });

}

/* 下面的代码为内容页value04.html页面里的代码，请自行打开此文件查看代码 */
var api = frameElement.api, W = api.opener;

api.button({
    id:'valueOk',
    name:'确定',
    callback:ok
});
/* 函数ok即为上面添加按钮方法中callback回调函数调用的函数 */
function ok()
{
    var str=document.getElementsByName("town[]");
    var objarray=str.length;
    var chestr="";
    var strText = '';

    for (i=0;i<objarray;i++)
    {
      if(str[i].checked == true)
      {
       chestr+=str[i].value+",";
       strText+=str[i].nextSibling.nodeValue+",";
   
      }
    }
    
if(!chestr){alert('请选择负责地区');return false;}
  W.document.getElementById('service_area').value = chestr;
  W.document.getElementById('showAreas').innerHTML = strText;

};
</script>
<style>
option, select{ margin-bottom:4px;}
.ui_buttons input{ padding:0;}
input[type=submit], input[type=button]{ height:auto; line-height:auto; text-align:center; background:#69AE6C; color:#fff;}
</style>
</body>
</html>
