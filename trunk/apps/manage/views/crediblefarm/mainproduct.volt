
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
    <div class="main_right">

        <div class="bt2">修改可信农场信息</div>
        <div align="left" style="margin-top:20px;margin-left:20px">
          <ul class="zhibiaoTab">
            <li><a href="/manage/crediblefarm/edit/{{user_id}}">农场信息</a></li>
            <li><a href="/manage/crediblefarm/advert/{{user_id}}">宣传图</a></li>
            <li><a href="/manage/crediblefarm/present/{{user_id}}">农场介绍</a></li>
            <li><a href="/manage/crediblefarm/footprint/{{user_id}}">发展足迹</a></li>
            <li class="active"><a href="/manage/crediblefarm/mainproduct/{{user_id}}">产品推荐</a></li>
            <li><a href="/manage/crediblefarm/productprocess/{{user_id}}">种植过程</a></li>
            <li><a href="/manage/crediblefarm/qualifications/{{user_id}}">资质认证</a></li>
            <li><a href="/manage/crediblefarm/picturewall/{{user_id}}">图片墙</a></li>
            <div style="clear:both;"></div>
          </ul>
        </div>
        <div id="fade" class="black_overlay"></div>
        <div class="cx">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              
                <tr>
                    <td class="cx_content paddingtd">
                      <em class="f-db main-recomm">
                        <select name="maxcategory" id="maxcategory" class="selectCate"  data-target="#msg">
                          <option value="">选择分类</option>
                        </select>
                        <select name="category" class="selectCate" id="selectcate" data-target="#msg">
                          <option value="">选择分类</option>
                        </select>
                        <select name="product" class="selectCate" id="product" data-target="#msg">
                          <option value="">选择产品</option>
                        </select>
                        <a href="javascript:;" onclick="farmrecom();">推荐</a>
                        <font>最多可推荐6个商品到农场首页</font>
                      </em>
                    </td>
                </tr>
                <tr>
                    <td class="cx_content paddingtd">
                      <table class="table_22">
                          <tr>
                              <td width="20%">图片</td>
                              <td width="10%">产品名</td>
                              <td width="5%">操作</td>
                          </tr>
                          {% if data %}
                         {% for key,val in data %}
                          <tr>
                              <td>
                                {% if val['picture_path'] %}
                                <img src="{{constant('IMG_URL')}}{{ val['picture_path'] }}" height="86" width="166" />
                                {% else %}
                                <img src="http://yncstatic.b0.upaiyun.com//mdg/version2.4//images/detial_b_img.jpg" width="157" height="77">
                                {% endif %}
                              </td>
                              <td align="center">{{ val['goods_name'] }}</td>
                              <td align="center">
                                <a href="javascript:;" onclick="isrecom({{ val['id'] }})">取消推荐</a>
                              </td>
                          </tr>
                          {% endfor %}
                          {% endif %}
                      </table>
                    </td>
                </tr>
            </table>
  {{ form("crediblefarm/picturewall", "method":"get") }}
  <div class="fenye">
    <div class="fenye1">
      <span>{{ pages }}</span> <em>跳转到第
        <input type="text" class='input' name='p' <?php if(isset($_GET['p'])&&$_GET['p']!=''){ echo "value='".$_GET['p']."'" ;}else{ echo "value='1'"; } ?>/>页</em>
          <?php unset($_GET['p']);
              foreach ($_GET as $key => $val) {

          echo "<input type='hidden' name='{$key}' value='{$val}'>";
        }?>
      <input class="sure_btn"  type='submit' value='确定'></div>
  </div>
    </div>
    </div>
    <!-- main_right 结束  -->
</div>
<input type="hidden" name="userId" id="userId" value="{{ userId }}">

<style>
td.paddingtd{ padding:20px 0;}
.upload_btn {width: 121px;height: 31px;line-height: 31px;text-align: center;background: url({{ constant('STATIC_URL') }}mdg/images/yz_btn.png) no-repeat;background-position: 0 0;top: 0;left: 88px;color: #7f7f7f;}
.table_22{ width:100%; border-collapse: collapse;}
.table_22 tr td{ border:1px solid #ccc; text-align: center;}
</style>

<script>
jQuery(document).ready(function(){
  var uid=$("#userId").val();
  $(".selectCate").ld({ajaxOptions : {"url" : "/ajax/getfarmcate?uid="+uid},
     defaultParentId : 0,
     style : {"width" : 140}
  });
});
</script>
<script>
// 取消推荐
function isrecom(id) {
  var userId = $("#userId").val();
  $.ajax({
    type:"POST",
    url:"/manage/crediblefarm/recommendCansel",
    data:{userId:userId,id:id},
    dataType:"json",
    success:function(msg){
      if(msg['code'] == 3) {
        alert(msg['result']);
        window.location.reload();
        
      } else if( msg['code'] == 2 ){
        alert(msg['result']);
        window.location.reload();
      } else {
        alert(msg['result']);
        return;
      }
    }
  });
}
// 推荐
function farmrecom() {
  var userId = $("#userId").val();
  var cidone  = $("#maxcategory").val();
  var cidtwo  = $("#category").val();
  var sell_id = $("#product").val();
  $.ajax({
    type:"POST",
    url:"/manage/crediblefarm/recommfram",
    data:{userId:userId,sell_id:sell_id,cidone:cidone,cidtwo:cidtwo},
    dataType:"json",
    success:function(msg){
      if(msg['code'] == 3) {
        alert(msg['result']);
        window.location.reload();
      } else {
        alert(msg['result']);
        return;
      }
    }
  });
  
}
</script>
<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
</body>
</html>
<style>
.zhibiaoTab li{ float: left; width:100px; height: 32px; text-align: center; line-height: 32px; }
.zhibiaoTab li.active{ border:1px solid #ccc; border-bottom: none; height: 32px; background:#fff;}
.cx{ margin-top:0;}
.zhibiaoTab li.active{ }
.zhibiaoTab li.active a{ color:#8AAF29; font-weight: bold;}
.icon{ background: none;}
</style>