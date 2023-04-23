<link rel="stylesheet" type="t_GET/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main_right">

    <!--  
    **  代码从这开始
    -->
    <link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/manage/css/manage-2.4.css">
    <div class="bt2">IF会员</div>

    <div class="tab2">
        <ul id="test2_li_now_">
            <a href='/manage/corporate/if?show=1'><li <?php if(empty($_GET['show']) || $_GET['show']==1){?>class="now"<?php }?>>会员列表</li></a>
            <a href='/manage/corporate/if?show=2'><li <?php if(!empty($_GET['show']) && $_GET['show']==2){?> class="now"<?php }?>>待审核</li></a>
        </ul>
    </div>
<?php if(empty($_GET['show']) || $_GET['show']==1){?>
    <div id="test2_1" class="tablist block">
        <!-- 查询 -->
        {{ form("corporate/if", "method":"get", "autocomplete" : "off") }}
        <div class="chaxun vip-list">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr height="40">
                    <th width="15%">用户ID</th>
                    <td width="35%">
                        <input type="text" id="user_id" name="user_id" value="<?php echo isset($_GET['user_id']) && $_GET['user_id'] ? $_GET['user_id'] : '';?>"/>
                    </td>
                    <th width="15%">名称</th>
                    <td width="35%">
                        <input type="text" id="ext_name" name="ext_name" value="<?php echo isset($_GET['ext_name']) && $_GET['ext_name'] ? $_GET['ext_name'] : '';?>"/>
                    </td>
                </tr>
                
                <tr height="40">
                    <th width="15%">用户类型</th>
                    <td width="35%">
                        <select name="type" id="type">
                            <option value="">请选择</option>
                            <option value="99" <?php if(isset($_GET['type']) && $_GET['type']=='99'){echo 'selected';} ?>>个人</option>
                            <option value="1" <?php if(isset($_GET['type']) && $_GET['type']=='1'){echo 'selected';} ?>>企业</option>
                        </select>
                    </td>
                    <th width="15%">申请时间</th>
                    <td width="35%">
                        <input type="text" id="expire_time" name="expire_time" value="<?php echo isset($_GET['expire_time']) && $_GET['expire_time'] ? $_GET['expire_time'] : '';?>"> —
                        <input type="text" id="expire_etime" name="expire_etime" value="<?php echo isset($_GET['expire_etime']) && $_GET['expire_etime'] ? $_GET['expire_etime'] : '';?>">
                    </td>
                </tr>
                <tr height="40">
                    <th width="15%">身份ID</th>
                    <td width="35%">
                        <input type="text" id="credit_id" name="credit_id" value="<?php echo isset($_GET['credit_id']) && $_GET['credit_id'] ? $_GET['credit_id'] : '';?>">
                    </td>
                    <th width="15%">手机号</th>
                    <td width="35%">
                        <input type="text" id="username" name="username" value="<?php echo isset($_GET['username']) && $_GET['username'] ? $_GET['username'] : '';?>">
                    </td>
                </tr>
                <tr height="40">
                    <th width="15%">农场面积</th>
                    <td width="35%"><input type="text" name="userareaa" value="<?php echo isset($_GET['userareaa']) && $_GET['userareaa'] ? $_GET['userareaa'] : '';?>">- <input type="text" name="userareab"  value="<?php echo isset($_GET['userareab']) && $_GET['userareab'] ? $_GET['userareab'] : '';?>">
                    </td>
                    <th width="15%">农场名</th>
                    <td width="35%">
                      <input type="text" id="userfarm" name="userfarm" value="<?php echo isset($_GET['userfarm']) && $_GET['userfarm'] ? $_GET['userfarm'] : '';?>">
                    </td>
                </tr>
                <tr height="40">
                   <!--  <th width="15%">所在地区</th>
                    <td width="35%">
                      <select name="start_pid" class='selectAreas' id="">
                        <option value="">请选择</option>
                      </select>
                      <select name="start_cid" class='selectAreas' id="">
                        <option value="">请选择</option>
                      </select>

                      <select name="start_did" class='selectAreas' id="">
                        <option value="">请选择</option>
                      </select>
                      <select name="start_eid" class='selectAreas' id="">
                        <option value="">请选择</option>
                      </select>
                    </td> -->
                    <th width="15%">审核状态</th>
                    <td width="35%">
                        <select name="status" id="status">
                           <option value="">请选择</option>
                           <option value="99" <?php if(isset($_GET['status']) && $_GET['status']=='99'){echo 'selected';} ?>>未审核</option>
                           <option value="1" <?php if(isset($_GET['status']) && $_GET['status']=='1'){echo 'selected';} ?>>审核通过</option>
                           <option value="2" <?php if(isset($_GET['status']) && $_GET['status']=='2'){echo 'selected';} ?>>审核未通过</option>
                           <option value="3" <?php if(isset($_GET['status']) && $_GET['status']=='3'){echo 'selected';} ?>>认证取消</option>
                        </select>
                    </td>
                </tr>
            </table>
            <div class="btn">
                <input type="submit" value="搜索" class="sub">              
            </div>
        </div>
          </form>
        <!-- 列表 -->
        <div class="neirong">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr align="center" class="alt">
                    <td width="9%" class="bj">用户ID</td>
                    <td width="9%" class="bj">手机号</td>
                    <td width="9%" class="bj">名称</td>
                    <td width="9%" class="bj">类型</td>
                    <td width="9%" class="bj">身份ID</td>
                    <td width="9%" class="bj">农场名称</td>
<!--                     <td width="10%" class="bj">所在地区</td> -->
                    <td width="9%" class="bj">农场面积</td>
                    <td width="9%" class="bj">申请时间</td>
                    <td width="9%" class="bj">状态</td>
                    <td width="9%" class="bj">操作</td>
                </tr>
              
            {% for key, item in users['items'] %}

                <tr align="center">
                  <td >{{ item['user_id']}}</td>
                  <td ><?=Mdg\Models\Users::getUserMobile($item['user_id'])?></td>
                  <td >{% if item and item['type']==1%}
                       {{item['company_name']}}
                       {% else %}
                       {% endif %}
                       {% if item and item['type']==0%}
                       {{item['name']}}
                       {% else %}
                       {% endif %}
                       {% if item and item['type']==3%}
                       {{item['company_name']}}
                       {% else %}
                       {% endif %}
                       {% if item and item['type']==2%}
                       {{item['company_name']}}
                       {% else %}
                       {% endif %}
                  </td>
                  <td ><?php if(isset($item)){echo Mdg\Models\UserInfo::$_type[$item['type']];}?>
                  </td>
                  <td >{% if item %}{{item['credit_id']}}{% else %}-{% endif %}</td>
                  <td >{% if item['userfarm'] %}{{item['userfarm']['farm_name']}}{% else %}-{% endif %}</td>
                 <!--  <td >{% if item %}{{item['province_name']}}{{item['city_name']}}{{item['district_name']}}{{item['town_name']}}{{item['address']}}{% else %}-{% endif %}</td> -->
                  <td >{% if item['userfarm'] %}{{item['userfarm']['farm_area']}}{% else %}-{% endif %}亩</td>
                  <td >{{ date('Y-m-d H:i:s', item['add_time'] ) }}</td>
                  <td >{% if item %}<?=Mdg\Models\UserInfo::$_status[$item['status']]?>{% else %}-{% endif %}</td>
                  <td >
                    <a href="/manage/corporate/hcpersonalinfo/{{ item['user_id']}}/8/if/{{item['credit_id']}}">查看</a>
                    <a href="/manage/corporate/ifedit/{{ item['user_id']}}/8/if/{{item['credit_id']}}">编辑</a>
                    {% if item and item['status']==1 %}
                      <a href="javascript:if(confirm('确认取消认证吗?'))window.location='/manage/corporate/get/{{ item['user_id']}}/8/if/{{item['credit_id']}}'">取消认证</a>
                    {% endif %}
                  </td>
                </tr>
                  {% endfor %}
            </table>
        </div>
  {{ form("corporate/index", "method":"get") }}
  <div class="fenye">
    <div class="fenye1">
      <span>{{ users['pages'] }}</span> <em>跳转到第
        <input type="t_GET" class='input' name='p' <?php if(isset($_GET['p'])&&$_GET['p']!=''){ echo "value='".$_GET['p']."'" ;}else{ echo "value='1'"; } ?>/>页</em>
      <?php unset($_GET['p']);
              foreach ($_GET as $key =>
      $val) {
          echo "<input type='hidden' name='{$key}' value='{$val}'>";
        }?>
      <input class="sure_btn"  type='submit' value='确定'></div>
  </div>
</form>
    </div>
<? }else{?>
    <div id="test2_2" class="tablist block">
        <!-- 列表 -->
         <div class="neirong">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr align="center" class="alt">
                    <td width="9%" class="bj">用户ID</td>
                    <td width="9%" class="bj">手机号</td>
                    <td width="9%" class="bj">名称</td>
                    <td width="9%" class="bj">类型</td>
                    <td width="9%" class="bj">身份ID</td>
                    <td width="9%" class="bj">农场名称</td>
                    <!-- <td width="10%" class="bj">所在地区</td> -->
                    <td width="9%" class="bj">农场面积</td>
                    <td width="9%" class="bj">申请时间</td>
                    <td width="9%" class="bj">状态</td>
                    <td width="9%" class="bj">操作</td>
                </tr>
              
            {% for key, item in userinfo['items'] %}

                <tr align="center">
                  <td >{{ item['user_id']}}</td>
                  <td ><?=Mdg\Models\Users::getUserMobile($item['user_id'])?></td>
                                    <td >{% if item and item['type']==1%}
                       {{item['company_name']}}
                       {% else %}
                       {% endif %}
                       {% if item and item['type']==0%}
                       {{item['name']}}
                       {% else %}
                       {% endif %}
                       {% if item and item['type']==3%}
                       {{item['company_name']}}
                       {% else %}
                       {% endif %}
                       {% if item and item['type']==2%}
                       {{item['company_name']}}
                       {% else %}
                       {% endif %}
                  </td>
                  <td ><?php if(isset($item)){echo Mdg\Models\UserInfo::$_type[$item['type']];}?>
                  </td>
                  <td >{% if item %}{{item['credit_id']}}{% else %}-{% endif %}</td>
                  <td >{% if item['userfarm'] %}{{item['userfarm']['farm_name']}}{% else %}-{% endif %}</td>
                  <!-- <td >{% if item %}{{item['province_name']}}{{item['city_name']}}{{item['district_name']}}{{item['address']}}{% else %}-{% endif %}</td> -->
                  <td >{% if item['userfarm'] %}{{item['userfarm']['farm_area']}}{% else %}-{% endif %}亩</td>
                  <td >{{ date('Y-m-d H:i:s', item['add_time'] ) }}</td>
                  <td >{% if item %}<?=Mdg\Models\UserInfo::$_status[$item['status']]?>{% else %}-{% endif %}</td>
                  <td >
                    <a href="/manage/corporate/hcpersonalinfo/{{ item['user_id']}}/8/if/{{item['credit_id']}}">查看</a>
                    <a href="/manage/corporate/ifedit/{{ item['user_id']}}/8/if/{{item['credit_id']}}">编辑</a>
                    {% if item and item['status']==2 %}
                      <a href="javascript:if(confirm('确认取消认证吗?'))window.location='/manage/corporate/get/{{ item['user_id']}}/8/if/{{item['credit_id']}}'">取消认证</a>
                    {% endif %}
                  </td>
                </tr>
                  {% endfor %}
            </table>
        </div>

  {{ form("corporate/index", "method":"get") }}
  <div class="fenye">
    <div class="fenye1">
      <span>{{ userinfo['pages'] }}</span> <em>跳转到第
        <input type="t_GET" class='input' name='page' <?php if(isset($_GET['p'])&&$_GET['p']!=''){ echo "value='".$_GET['p']."'" ;}else{ echo "value='1'"; } ?>/>页</em>
      <?php unset($_GET['p']);
              foreach ($_GET as $key =>
      $val) {
          echo "<input type='hidden' name='{$key}' value='{$val}'>";
        }?>
      <input class="sure_btn"  type='submit' value='确定'></div>
  </div>
</form>
    </div>
<? }?>
</div>
<!-- main_right 结束  --> 
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/i18n/jquery-ui-timepicker-zh-CN.js"></script>
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.css" />
<!-- <link rel="stylesheet" type="text/css" href="http://static.ync365.com/mdg/css/uibase.css" /> -->
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}validator/jquery.validator.css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/jquery.validator.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/local/zh_CN.js"></script>

<script type="text/javascript">
$(function(){

$('#expire_time').datepicker();
$('#expire_etime').datepicker();

$(".selectAreasa").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    {% if start_areasa %}
    texts:[{{ start_areasa}}],
    {% endif %}
    style : {"width" : 140}
});
$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    {% if start_areas %}
    texts:[{{ start_areas}}],
    {% endif %}
    style : {"width" : 140}
});

});



function tab(o, s, cb, ev){ //tab切换类
var $ = function(o){return document.getElementById(o)};
var css = o.split((s||'_'));
if(css.length!=4)return;
this.event = ev || 'onclick';
o = $(o);
if(o){
this.ITEM = [];
o.id = css[0];
var item = o.getElementsByTagName(css[1]);
var j=1;
for(var i=0;i<item.length;i++){
if(item[i].className.indexOf(css[2])>=0 || item[i].className.indexOf(css[3])>=0){
if(item[i].className == css[2])o['cur'] = item[i];
item[i].callBack = cb||function(){};
item[i]['css'] = css;
item[i]['link'] = o;
this.ITEM[j] = item[i];
item[i]['Index'] = j++;
item[i][this.event] = this.ACTIVE;
}
}
return o;
}
}
tab.prototype = {
ACTIVE:function(){
var $ = function(o){return document.getElementById(o)};
this['link']['cur'].className = this['css'][3];
this.className = this['css'][2];
try{
$(this['link']['id']+'_'+this['link']['cur']['Index']).style.display = 'none';
$(this['link']['id']+'_'+this['Index']).style.display = 'block';
}catch(e){}
this.callBack.call(this);
this['link']['cur'] = this;
}
}
</script> 
<script type="text/javascript">
// window.onload = function(){
// new tab('test2_li_now_');
// }
 </script>

<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.css" />
<script type="text/javascript" src="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.js"></script>
<script type="text/javascript" src="http://yncstatic.b0.upaiyun.com/js/validator/local/zh_CN.js"></script>
<div class="footer"> Copyright © 2013-2014 ync365.com All rights reserved. </div>
</body>
</html>

</body>
</html>

