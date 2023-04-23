<body >
<style>ul li {line-height: 37px;}</style>
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.css" />
<!-- 报价弹框 start -->
<div class="dialog" style="width:500px;">
    <form action="/member/dialog/savelookgoods" method="post" id="newquo" class="nice-validator n-default" novalidate="novalidate">

        <ul>
            
                    <li>
                        <span class="label">姓名：</span>
                        <em>
                            <input type="text" name="name"  data-target="#c_spec" value='{{ user['name'] }}'  aria-required="true" > <i id="c_spec"></i>
                        </em>
                    </li>
                    <li>
                        <span class="label">手机：</span>
                        <em>
                            <input type="text" name="mobile"  data-target="#c_sellname" value='{{ user['mobile'] }}' aria-required="true" >
                            <i id="c_sellname"></i>
                        </em>
                    </li>
                    

                    <li>
                        <span class="label">预计采购数量：</span>
                        <em>
                            <input type="text" name="number" value="" data-target="#c_sphone" aria-required="true">
                            <?php echo isset($unit[$data->goods_unit]) ? $unit[$data->goods_unit] : ''; ?>

                            <i id="c_sphone"></i>
                        </em>
                    </li>

                    <li>
                        <span class="label">采购商品：</span>
                        <em>
                            <input type="text" name="goods_name" value="" data-target="#c_goods_name" aria-required="true">
                            <i id="c_goods_name"></i>
                        </em>
                    </li>

                    <li>
                        <span class="label">采购规格：</span>
                        <em>
                            <textarea name='sepc' aria-required="true" ></textarea>
                            <!-- <i id="c_sepc"></i> -->
                        </em>
                    </li>
                   
     

        </ul>
        <em>
            <font>
                <div class="btn">
                <input type="hidden" name="sellid" value="{{data.id}}">
                    <input class="submit_btn" type="submit" value="确认">
                </div>

            </font>
        </em>
    </form>
</div>
<em>
    <font>
        <!-- 报价弹框 end -->
        <script type="text/javascript" src="http://yncstatic.b0.upaiyun.com/js/jquery/jquery.form.js"></script>

        <script>



$(function(){
parent.setTitle('申请看货服务');


    $('#newquo').validator({
        rules: {
            select: function(element, param, field) {
                return element.value > 0 || '请选择';
            },
            nimei  : [/^([0-9])+(\.([0-9])+)?$/, '请输入数字'],
            xx:[/^1[3-9]\d{9}$/, "手机号格式不正确"],
        },
        fields:  {
         name:"required;chinese;length[2~5]",
         mobile:"required;xx",
         number:"required;nimei",
         goods_name:"required;",
         address:"required;",
         sepc:"required;",
     },
    });

   // window.parent.dialog.size(570,650);
})
</script>

    </font>
</em>
<div id="__if72ru4sdfsdfrkjahiuyi_once" style="display:none;"></div>
<div id="__hggasdgjhsagd_once" style="display:none;"></div>
</body>