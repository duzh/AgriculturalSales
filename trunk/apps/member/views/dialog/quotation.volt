<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.css" />
<div class="dialog">
    <form action="/member/dialog/savequo" method="post">
    <h6>确认采购信息</h6>
    <ul>
    	<li><span>收货人手机号：</span><em><input type="text" name="mobile" value="" /></em></li>
    	<li><span>采购数量：</span><em><input type="text" name="quantation" /><font>{{ goods_unit[sell.goods_unit] }}</font></em></li>
    	<li><span>收货人姓名：</span><em><input type="text" name="purname" /></em></li>
        <li>
        	<span>收货地址：</span>
            <em>
                <select class="selectAreas">
                    <option>省</option>
                </select>
                <select class="selectAreas">
                    <option>市</option>
                </select>
                 <select class="selectAreas">
                    <option>县/区</option>
                </select>
                 <select class="selectAreas">
                    <option>请选择</option>
                </select>
                 <select class="selectAreas" name="areas"  >
                    <option>请选择</option>
                </select>
            </em>
        </li>
    </ul>
    <input class="submit_btn" type="submit" value="确认" />
    </form>
</div>
<script>
$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    {% if (curAreas) %}
    texts : [{{ curAreas }}],
    {% endif %}
    style : {"width" : 120}
});
</script>