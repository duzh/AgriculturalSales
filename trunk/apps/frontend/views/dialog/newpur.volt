<!-- 发布采购弹框 start -->
<div class="fb_cg_alerter">
    <!-- <h6>发布采购- 帮助您找到物美价廉的商品</h6> -->
    <ul>
        <li><span>采购商品名称：</span><em><input type="text" /></em></li>
        <li>
            <span>所属分类：</span>
            <em>
                <select><option>请选择</option></select>
                <select><option>请选择</option></select>
            </em>
        </li>
        <li>
            <span>采购数量：</span>
            <em>
                <input type="text" />
                <select class="s1"><option>元/公斤</option></select>
            </em>
        </li>
        <li>
            <span>规格要求：</span>
            <em><textarea>写下您对商品的规格要求，收到后我们会立即给你回电确认，剩下的交给我们吧</textarea></em>
        </li>
    </ul>
    <p class="f-ff0">委托找货 > 选择报价 > 完成交易</p>
    <input class="submit_btn" type="submit" value="立即帮我找找" />
</div>
<!-- 发布采购弹框 end -->


<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>

<script>
    
$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareas"},
    defaultParentId : 0,
    style : {"width" : 140}
});

</script>