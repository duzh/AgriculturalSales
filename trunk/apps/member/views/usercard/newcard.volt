<!--头部 start-->
{{ partial('layouts/page_header') }}
<!--头部 end-->	

<!--主体 start-->
<div class="center-wrapper pb30">
    <div class="bread-crumbs w1185 mtauto">
        <a href="/">首页</a>
        >
        <a href="/member">个人中心</a>
        > 可信农场
    </div>
    <div class="w1185 mtauto clearfix">
        <!-- 左侧 start-->
        {{ partial('layouts/navs_left') }}
        <!-- 左侧 end-->
        <form action="/member/bankcard/savecard" method="post" id='userForm'>
            <!-- 右侧 -->
            <div class="center-right f-fr">
                <div class="buyer-common" >
                    <div class="m-title mt20">认证信息</div>
                    <div class="message clearfix">
                        <font>银行卡开户行</font>
                        <div class="select-box lang-select">
                            <select name='ent_bank_name'  data-rule="required;" >
                                <option value=''>请选择</option>
                                {% for key , item in  bankList %}
                                <option value='{{ item['gate_id']}}'>{{ item['bank_name']}}</option>
                                {% endfor %}
                            </select>
                        </div>
                    </div>

                    <div class="message clearfix">
                        <font>开户行所在地</font>
                        <div class="select-box lang-select">
                            <select name='ent_bank_province_id' class='ent_class_bank_address'>
                                <option value=''>请选择</option>
                            </select>
                            <select name='ent_bank_city_id'  class='ent_class_bank_address' >
                                <option value=''>请选择</option>
                            </select>
                            <select name='ent_bank_district_id'  class='ent_class_bank_address' data-rule="required;" >
                                <option value=''>请选择</option>
                            </select>
                        </div>
                    </div>
                    <div class="message clearfix">
                        <font>开户名</font>
                        <div class="input-box">
                            <input type="text" name='ent_bank_account'  data-rule="required;chinese" />
                        </div>
                    </div>
                    <div class="message clearfix">
                        <font>卡号</font>
                        <div class="input-box">
                            <input type="text"  name='ent_bank_cardno' data-rule="required;mark" />
                        </div>
                    </div>
				</div>
            </div>
        <input class="buyer-btn classSubmit" type="submit" value="提交申请" />
		</form>
    </div>
</div>

<!--尾部 start-->
{{ partial('layouts/footer') }}
<!--尾部 end-->
<style>
.upload_btn {width:89px; height:25px; border: solid 1px #99be20; color:#99be20; background: #fff; text-align: center; line-height:25px;
  font-family: '微软雅黑';
  cursor: pointer;
  position: relative;}
.edui-default .edui-editor{ margin:10px auto;}
</style>