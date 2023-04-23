<form action="/sell/save/" method="post" id="caigou">
<div class="cg_alerter" style="display:block">
    
    
    <ul>
        <li><span>收货人手机号：</span><em><input type="text" class="f-fl mr10"  data-target="#mobile" name="mobile"/></em><i class="f-fl mt10" id="mobile"></i></li>

        <li><span>采购数量：</span><em><input type="text"  name="number" data-target="#number"/>
                             </em><i class="f-fl mt10" id="number"></i></li>

         <li><span>采购价格：</span><em><input type="text"  name="price"   data-target="#price"/>
                           <font >
                           元/<?php echo Mdg\Models\Sell::$type3[$sell->goods_unit]?>
                           </font></em><i class="f-fl mt10" id="price"></i></li>               

        <li><span>收货人姓名：</span><em><input type="text" name="username"  data-target="#username" /></em><i class="f-fl mt10" id="username"></i></li>
        <li>
            <span>收货地址：</span>
            <em>
                <select name="province" class="selectAreas" id="province">
                    <option value="0" selected>省</option>
                </select>
                <select name="city" class="selectAreas" id="city">
                    <option value="0">市</option>
                </select>
                <select name="town" class="selectAreas" >
                    <option value="0">区/县</option>
                </select>
                 {{ text_field("areas", "type" : "numeric","class":"w1","name":"areas") }}
                 
                
            </em>

        </li>
    </ul>
    <input type="hidden" name="sell_id" value="{{sell.id}}">
    <input class="submit_btn" type="submit" value="确认" />
</div>
</form>
<!-- 采购弹框 end -->



{{ partial('sell/showuser') }} 