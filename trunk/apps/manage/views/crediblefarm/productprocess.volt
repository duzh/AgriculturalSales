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
            <li><a href="/manage/crediblefarm/mainproduct/{{user_id}}">产品推荐</a></li>
            <li  class="active"><a href="/manage/crediblefarm/productprocess/{{user_id}}">种植过程</a></li>
            <li><a href="/manage/crediblefarm/qualifications/{{user_id}}">资质认证</a></li>
            <li><a href="/manage/crediblefarm/picturewall/{{user_id}}">图片墙</a></li>
            <div style="clear:both;"></div>
          </ul>
        </div>
        <div id="fade" class="black_overlay"></div>
        <div class="cx">
           <form action="/manage/crediblefarm/plantsave" method="post" id="newpur">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
             
                <tr>
                    <td class="cx_title">新增种植产品：</td>
                    <td class="cx_content paddingtd">
                      <input class="txt" type="text" placeholder="请输入产品名称" name="goods_name" data-rule="产品名称:required;" data-target="#product"/>
                    <input type="hidden" name="user_id" id="user_id" value="{{ user_id }}">
                    <input class="btn" type="submit" value="新增" />
                    <span id="product"></span>
                    </td>
                </tr>
              
                <tr>
                    <td class="cx_title"></td>      
                    <td class="cx_content paddingtd">
                      <table class="table_22" >
                          <tr>
                              <td width="30%">序号</td>
                              <td width="25%">产品名称</td>
                              <td width="25%">添加时间</td>
                              <td width="20%">操作</td>
                          </tr>
                          <?php $i=1 ?>
                          {% for key,item in planting %}
                          <tr>
                              <td><?php echo $i++ ?></td>
                              <td>{{item.goods_name}}</td>
                              <td>{{date('Y-m-d H:i:s',item.add_time)}}</td>
                              <td>
                                <a href="javascript:;" onclick="delgraphic(this,{{ item.goods_id }},2);">删除</a>
                                <a href="/manage/crediblefarm/farmplant/{{item.goods_id}}/{{user_id}}">查看过程</a> 

                            </td>
                          </tr>
                          {% endfor %}
                      </table>
                    </td>
                </tr>
            </table>
            </form>
        </div>
        <div align="center" style="margin-top:20px;">
            <td>{{ hidden_field("id") }}</td>
             <input type="hidden" name="user_id" value="{{user_id}}">
             <input type="submit" value="修改" class="sub"/>
        </div>
    </div>
    <!-- main_right 结束  -->
</div>


<style>
td.paddingtd{ padding:20px 0;}
.upload_btn {width: 121px;height: 31px;line-height: 31px;text-align: center;background: url({{ constant('STATIC_URL') }}mdg/images/yz_btn.png) no-repeat;background-position: 0 0;top: 0;left: 88px;color: #7f7f7f;}
.table_22{ width:702px; border-collapse: collapse;}
.table_22 tr td{ border:1px solid #ccc; text-align: center;}
</style>
<script>
//删除图文介绍、足迹、资质
function delgraphic(obj, id, type) {
    $.getJSON('/manage/crediblefarm/delgraphic', {id : id,type : type}, function(data) {
        alert(data.msg);
        if(data.state) {
            if(type==3){
                $(obj).parent('span').remove();  
            }else{
              $(obj).parent().parent().remove();  
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