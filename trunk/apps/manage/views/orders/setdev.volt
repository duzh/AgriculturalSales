<style>
ul li {line-height: 37px; list-style: none; }
ul li em{font-style:normal;}
</style>
</style>
<div class="dialog" style="width:400px; height:220px;">
    <form action="/manage/orders/savedev/" method="post" id="editprice">
        <input type="hidden" value="{{orderid}}" name="orderid">
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
            <div id='wuliu' style='display:block'>
                <li>
                    <span class="label">物流公司：</span>
                    <em><input class="mr10" type="text" name="wuliu_gongsi" id='wuliu_gongsi' value=""  /></em>
                </li>
                <li>
                    <span class="label">物流单号：</span>
                    <em><input class="mr10" type="text" name="wuliu_sn" id='wuliu_sn' value=""  /></em>
                </li>
           </div>
            <div id='huoyun' style='display:none'>
                <li>
                    <span class="label">司机姓名：</span>
                    <em><input class="mr10" type="text" name="driver_name"  id='driver_name' value=""   /></em>
                </li>
                <li>
                    <span class="label">司机电话：</span>
                    <em><input class="mr10" type="text" name="driver_phone" id='driver_phone' value=""  /></em>
                </li>
           </div>
           <div id='ziti'  style='display:none' >
                <li>
                    <span class="label">提货人姓名：</span>
                    <em><input class="mr10" type="text" name="name" id='name'  value=""  /></em>
                </li>
                  <li>
                    <span class="label">提货人电话：</span>
                    <em><input class="mr10" type="text" name="mobile" id='mobile'  value=""   /></em>
                </li>
           </div>
            <li><span class="label">&nbsp;</span>
                <input type='hidden' value="{{content}}" name="content">
                <em><input type="submit"  value="确定" /></em>
            </li>
        </ul>
        
    </div>
    </form>
</div>

<script>
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
      xx:[/^1[3-9]\d{9}$/, "手机号格式不正确"],
     },
     fields:  {

         wuliu_sn:"required;integer",
         driver_name:"required;chinese;length[2~5]",
         driver_phone:"required;xx",
         name:"required;chinese;length[2~5]",
         mobile:"required;xx",
         wuliu_gongsi:'required;chinese'
     },
    
});
</script>