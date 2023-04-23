<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<style>
ul li {line-height: 37px; list-style: none; }
ul li em{font-style:normal;}
</style>
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.css" />
<div style="width:450px; height:240px;">
<form action="/member/orderssell/send" method="post" id="editprice">
    <div class="dialog">
        <input type="hidden" value="{{order_id}}" name="oid">
        <div class="message">
            <ul>
                <li><span class="label">发货方式：</span>
                    <em>
                        <select  name="fahuo" id="fahuo" onchange="dev()">
                            {% for key,val in dev_name%}
                            <option value='{{key}}'>{{val}}</option>
                            {% endfor %}
                        </select>
                    </em>
                </li>
            </ul>
                <div id='wuliu' style='display:block'>
                <ul>
                    <li>
                        <span class="label">物流公司：</span>
                        <em><input class="mr10" type="text" name="wuliu_gongsi" id='wuliu_gongsi' value=""  /></em>
                    </li>
                    <li>
                        <span class="label">物流单号：</span>
                        <em><input class="mr10" type="text" name="wuliu_sn" id='wuliu_sn' value=""  /></em>
                    </li>
                </ul>
               </div>

                <div id='huoyun' style='display:none'>
                <ul>
                    <li>
                        <span class="label">司机姓名：</span>
                        <em><input class="mr10" type="text" name="driver_name"  id='driver_name' value=""   /></em>
                    </li>
                    <li>
                        <span class="label">司机电话：</span>
                        <em><input class="mr10" type="text" name="driver_phone" id='driver_phone' value=""  /></em>
                    </li>
                </ul>
               </div>
               <div id='ziti'  style='display:none' >
                <ul>
                    <li>
                        <span class="label">提货人姓名：</span>
                        <em><input class="mr10" type="text" name="name" id='name'  value=""  /></em>
                    </li>
                      <li>
                        <span class="label">提货人电话：</span>
                        <em><input class="mr10" type="text" name="mobile" id='mobile'  value=""   /></em>
                    </li>
                </ul>
               </div>
               <ul>
                <li><span class="label">&nbsp;</span>
                    <em>
                        <input class="submit_btn" type="submit" style="background: #f9ab14;border-radius: 2px;border: none; margin-left:0;" value="确定" />
                    </em>
                </li>
            </ul>
            
        </div>
    </div>
</form>
</div>

<script>
var api = frameElement.api, W = api.opener;
function dev(){
    var fahuo=$("#fahuo").val();
    if(fahuo=="1"){
     $("#wuliu").show();
     $("#huoyun").hide();
     $("#ziti").hide();

    }
    if(fahuo=='2'){
     $("#huoyun").show();
     $("#wuliu").hide();
     $("#ziti").hide();


    }
    if(fahuo=='3'){
     $("#huoyun").hide();
     $("#wuliu").hide();
     $("#ziti").show();
    }
}
$("#editprice").validator({
     ignore: ':hidden',
     rules: {
      xx:[/^1[3-9]\d{9}$/, "手机号格式不正确"]
     },
     fields:  {

         wuliu_sn:"required;[/^[0-9a-zA-Z]{8,30}$/, '请输入字母数字']",
         driver_name:"required;chinese;length[2~5]",
         driver_phone:"required;xx",
         name:"required;chinese;length[2~5]",
         mobile:"required;xx",
         wuliu_gongsi:'required;'
     }
    
});

     window.parent.dialog.size(520,235);
</script>