<div class="center-nav f-fl">
    <div class="all-message">
        <dl class="clearfix">
            <dt class="f-fl" id='avatar-view'>
                 <?php $path= Mdg\Models\UsersExt::getPath($this->session->user["id"]);?>
                                {% if path %}
                                <img src="{{path}}" alt="Avatar" id="showimg">
                                {% else %}
                                <img id="showimg" src="{{ constant('STATIC_URL') }}mdg/images/trust-farm-img.jpg" alt="Avatar">
                                {% endif %}
            </dt>
            <dd class="name f-fr">{% if session.user['name'] %}{{ session.user['name'] }}{% else %}{{ session.user['mobile'] }}{% endif %}</dd>
            <dd class="has-list f-fr">
                <a class="zh" href="javascript:;">账户信息&gt;</a>
                <div class="list">
                    <span></span>
                    <a href="/member/perfect/index">账户信息</a>
                    <a href="/member/messageuser/list">消息管理</a>
                    <a href="/member/repwd/index">修改密码</a>
                </div>
            </dd>
            <dd class="modify f-fr">
                <a onclick="javascript:newWindows('change', '个人信息-头像', '/member/perfect/uploadfile');" >修改头像</a>
            </dd>
        </dl>
        <!--判断经纪人-->
      
        {% if is_brokeruser.is_broker == 0 %}
        <div class="my-level f-oh">
            <font>
                我的身份：{% if user_hc == false and user_if == false and user_pe == false  %}<i>暂无</i>{% endif %}
            </font>
            <a class="authen-link" href="/member/userfarm/index">身份认证</a>
        </div>
        <div class="my-level-icon f-oh">
         {% if user_if == 1 %}
            <span class="icon1"><a href="/member/userfarm/certification/if">可信农场</a></span>
        {% endif %}
        {% if user_hc == 1 %}
            <span class="icon2"><a href="/member/userfarm/certification/hc">县域服务中心</a></span>
        {% endif %}
        {% if user_lwtt == 1 %}
              <span class="icon5">产地服务站</span>   
        {% endif %}
        {% if user_pe and user_pe.type==1 and user_pe.status==1  %}
            <span class="icon3"><a href="/member/userfarm/certification/pe">采购企业</a></span>
        {% endif %}
        {% if user_pe and user_pe.type==0 and user_pe.status==1 %}
            <span class="icon4"><a href="/member/userfarm/certification/pe">采购经纪人</a></span>
        {% endif %}
        </div>
        {% endif %}
    </div>


    <!-- 导航 -->
    {% for key, val in navLeft %}
    <div class="center-nav-list">
      <h6>{{ val['title'] }}</h6>
      <ul>
        {% for m, nav in val['child'] %}
        <li {% if curId == nav['id'] or 
            (curId=='33' and nav['id']=='34') or
            (curId=='37' and nav['id']=='38') or 
            (curId=='46' and nav['id']=='47') or 
            (curId=='26' and nav['id']=='27')%}class="active"{% endif %}>
          
          <a href="/{{ nav['models'] }}/{{ nav['controller'] }}/{{ nav['action'] }}" 
             {% if nav['controller'] == "shop" AND shopVia == 1  or nav['action'] == 'buyNew'%}
             target="_blank"{% endif %} >
             {{ nav['title'] }} {% if m == 17 and messagecount %}({{messagecount}}){% endif %}
          </a>
          {% if nav['controller'] == "sell" %}
          <em style="margin-right:4px;">|</em><a class="add-btn" onclick="member_new_sell()" >添加</a>
          {% endif %}
          {% if nav['controller'] == "purchase" %}
          <em style="margin-right:4px;">|</em><a class="add-btn" href="/member/purchase/new/">添加</a>
          {% endif %}

        </li>
        {% endfor %}
      </ul>
    </div>
    {% endfor %}
</div>

<?php if(isset($_SESSION["user"]["mobile"])&&isset($_SESSION["user"]["id"]))
 { $lwttinfos=Mdg\Models\UserInfo::getlwttlist($_SESSION["user"]["id"],$_SESSION["user"]["mobile"]); } else{$lwttinfos=false;} ?>
<!--弹窗start-->
{% if lwttinfos and lwttinfos['lwttstate'] %}
<div class="member_fsh_tongzhiCon" style="display: none;">
     <div class="wms-alert wms-alert2">
      <a class="close-btn" href="javascript:;" onclick="memberwarnPageClose();" ></a>
      <div class="title">提示</div>
      <font class="f-db">禁止发布整合供应信息：</font>
        {% if lwttinfos['lwttstate']==5  %}<div class="tips f-oh"><span class="f-fl">-您的产地服务站认证正在审核中,不可发布整合供应信息</span> </div>{% else %}{% if lwttinfos['lwttcount']<0  %}<div class="tips f-oh"><span class="f-fl">-您当前整合的可信农场未达1家,不可发布整合供应信息</div>{% else %}{% if !lwttinfos['cate_id'] %}<div class="tips f-oh "><span class="f-fl">-您当前整合的可信农场还未成功发布任何供应信息，不可发布整合供应信息</span></div>{% endif %}{% endif %}{% endif %}
      <div class="btns">
        <input type="button" value="知道了" onclick="memberwarnPageClose();">
      </div>
  </div>
  <div class="member_fsh_tongzhiBg"></div>
</div>
<style>
    .member_fsh_tongzhiCon{ z-index:99;}
    .member_fsh_tongzhiCon, .member_fsh_tongzhiBg{ position:fixed; width:100%; height:100%; left:0; top:0; right:0; bottom:0;}
    .member_fsh_tongzhiBg{ background:#000; opacity: 0.4; filter: alpha(opacity=40); z-index:99;}
</style>
{% endif %}
<script>
    function   member_new_sell(){
      {% if !lwttinfos or !lwttinfos['lwttstate'] %}
           location.href="/member/sell/new";
      {% else %}
          {% if lwttinfos['lwttstate']==5 %}
          $('.member_fsh_tongzhiCon').show();
          {% else %}
          location.href="/member/sell/new";
          {% endif %}
      {% endif %}
    }
    function memberwarnPageClose(){
         $('.member_fsh_tongzhiCon').hide();
    }
</script>

