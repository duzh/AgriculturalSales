<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.css" />
<form  action="/purchasequotation/create/" method="post" id="baojia" >
<div class="dialog">
   
    <div class="message">
        <p class="p1">
            采购{{Purchase.title}} 果径:70mm以上 15000斤<br />
            采购编号：{{Purchase.pur_sn}}   发布时间：{{date("Y-m-d H:i:s",Purchase.createtime)}}
        </p>
        <p class="p2"><font>已有：<strong class="color1"><?php echo Mdg\Models\PurchaseQuotation::oredercount($Purchase->id)?></strong>&nbsp;家报价</font><font>报价截止：<strong class="color2"><?php echo Mdg\Models\PurchaseQuotation::humandate($Purchase->endtime)?></strong></font></p>
    </div>
    <ul>
        <li><span>您的报价：</span><em><input type="text" data-target="#price" name="price" /></em><i class="f-fl mt10" id="price"></li>
        <li><span>规格描述：</span><em><input type="text" data-target="#spec"  name="spec" /></em><i class="f-fl mt10" id="spec"></li>
       
        <li>
            <span>供应地：</span>
            <em>
                
                <select name="province" class="selectAreas" id="province">
                    <option value="0" selected>省</option>
                </select>
                <select name="city" class="selectAreas" id="city">
                    <option value="0">市</option>
                </select>
                <select name="sareas" class="selectAreas" id="town" data-target="#town">
                    <option value="0">区/县</option>
                </select>
                  <input class="w1" type="text" "name":"saddress" />
                 <i class="f-fl mt10" id="town"></i>
               
            </em>
        </li>
        <li><span>姓名：</span><em><input type="text" name="sellname" data-target="#sellname" /></em><i class="f-fl mt10" id="sellname"></li>
        <li><span>电话：</span><em><input type="text" name="sphone"   data-target="#sphone" /></em><i class="f-fl mt10" id="sphone"></li>
    </ul>
    <input type="hidden" name="selectid" value="{{Purchase.id}}">
    <div class="btn"> <input type="submit" value="确认" class="submit-btn" onclick="closeDialog()">
       <span>不合理的报价会被系统屏蔽</span></div>
</div>
</form>
<script>

 $(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareas"},
         defaultParentId : 0,
     });   

 $('#baojia').validator({
        fields: {
            'price': 'required; digits;',
            'spec':'required;',
            'sellname' :'required;Chinese',
            'sphone':'required;phone',
            'town':'required;'
        }
   })
</script>