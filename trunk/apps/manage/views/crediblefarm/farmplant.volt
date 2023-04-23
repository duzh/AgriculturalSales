<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com/mdg/version2.4/css/trusted-farm/trusted-farm.css">
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
            <li class="active"><a href="/manage/crediblefarm/productprocess/{{user_id}}">种植过程</a></li>
            <li><a href="/manage/crediblefarm/qualifications/{{user_id}}">资质认证</a></li>
            <li><a href="/manage/crediblefarm/picturewall/{{user_id}}">图片墙</a></li>
            <div style="clear:both;"></div>
          </ul>
        </div>
        <div id="fade" class="black_overlay"></div>
        <div class="cx">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="cx_title">新增过程：</td>
                  <td class="cx_content paddingtd"><input onclick="showLayer()" class="f-db add-btn" type="button" value="新增" /></td>
                </tr>
                <tr>
                    <td class="cx_title"></td>  
                    <td class="cx_content paddingtd">
                      <table class="table_22">
                          <tr>
                            <td width="20%">时间</td>
                              <td width="20%">图片</td>
                              <td width="10%">标题</td>
                              <td width="45%">描述</td>
                              <td width="5%">操作</td>
                          </tr>
                          {% for key,item in data %}
                          <tr>
                            <td>{{date('Y-m-d H:i:s',item.add_time)}}</td>
                              <td><img src="{{constant('IMG_URL')}}{{item.picture_path}}" wdth="170px" height="50px"></td>
                              <td>{{item.title}}</td>
                              <td title="{{ item.desc}}"><?php echo isset($item->desc) ? Lib\Func::sub_str($item->desc,60) : ''; ?></td>
                              <td><a href="javascript:;" onclick="delgraphic(this,{{ item.id }},5);">删除</a></td>
                          </tr>
                          {% endfor %}
                      </table>
                    </td>
                </tr>
            </table>
        </div>
        <div align="center" style="margin-top:20px;">
            <td>{{ hidden_field("id") }}</td>
             
        </div>
    </div>
    <!-- main_right 结束  -->
</div>
<!-- 弹框 -->
  <div class="plant-layer"></div>
  <div class="plant-add-box" style="width:472px; margin-left:-236px;">
    <form action="/manage/crediblefarm/farmplantsave" method="post" id="perfect"> 
    <a class="close-btn" href="javascript:;"></a>
    <div class="title">新增种植过程</div>
    <div class="message clearfix">
      <font>日期</font>
      <div class="select-box">
      <select name="year1"></select>
      <select name="month1"></select>
      <select name="day1" data-rule="日期:required;"></select>
      </div>
    </div>
    <div class="message clearfix">
      <font>上传照片</font>
        <div class="loadImg-box" style="position:relative;">
          <div class="file-btn">
            <input class="btn2" type="file" id='ent_logo_pic' />
            <input id="ent_logo_pic_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value="" />  
          </div>
            <p style="margin-left:-270px">图片规格200 X 200</p>
          <dl id="ent_show_logo_pic">
            <input type="hidden" data-target="#ent_logo_pic_tip" name="yanz" value='' data-rule="图片:required;">
                      <i id='ent_logo_pic_tip' style="position:absolute; left:96px; top:0;"></i>
            <dt>
            </dt>
          </dl>
        </div>
    </div>
    <div class="message clearfix">
      <font>标题内容</font>
      <div class="input-box">
        <input type="text" name="title" data-rule="标题内容:required;length[1~20]"/>
      </div>
    </div>
    <div class="message clearfix">
      <font>内容</font>
      <div class="area-box" id="textArea">
        <textarea name="desc" data-rule="内容:length[1~400]"></textarea>
        <i>您还可以输入<label>400</label>个字</i>
        <input type="hidden" id="srNum" name="srNum" value="" />
      </div>
    </div>
    <input type="hidden" name="goods_id" value="{{goods_id}}">
    <input type="hidden" name="goods_name" value="{{goods_name}}">
    <input type="hidden" name="user_id" value="{{user_id}}">
    <input class="plant-layer-btn" type="submit" value="确定" />
    </form>
  </div>
<script language="javascript" src="/ymdclass/YMDClass.js"></script>

<style>
td.paddingtd{ padding:20px 0;}
.upload_btn {width: 121px;height: 31px;line-height: 31px;text-align: center;background: url({{ constant('STATIC_URL') }}mdg/images/yz_btn.png) no-repeat;background-position: 0 0;top: 0;left: 88px;color: #7f7f7f;}
.table_22{ width:702px; border-collapse: collapse;}
.table_22 tr td{ border:1px solid #ccc; text-align: center;}
</style>

<script>
function showLayer(){
      $('.plant-layer').show();
      $('.plant-add-box').show();
    };
  $(function(){
    $('.plant-add-box .close-btn').click(function(){
      $('.plant-layer').hide();
      $('.plant-add-box').hide();
    });
  });
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
  <script>
      function closeImgup(obj, id) {
        $.getJSON('/member/farm/deleteImg', {id : id}, function(data) {
            alert(data.msg);
            if(data.state) {
                $(obj).parents('.imgBox').slideUp();
            }
        });
    }
    $(function(){
      $('.plant-process-list .add-btn').click(function(){
        $('.plant-layer').show();
        $('.plant-add-box').show();
      });
      $('.plant-add-box .close-btn').click(function(){
        $('.plant-layer').hide();
        $('.plant-add-box').hide();
      });
      //字数统计
      $(document).keyup(function() {
            var text=$("#textArea textarea").val();
            var counter=text.length;
        if(counter > 400){
          $('#srNum').val('');
          return ;
        }else{
          $("#textArea label").text(400-counter);
          $('#srNum').val(400-counter);
        };
        });
      function bankImg(id,type,show_img,tip_id){
        //银行正面照
        id.uploadify({
          'swf'      : '/uploadify/uploadify.swf?ver=<?php echo rand(0,9999);?>',
          'width': '88',
          'height': '28',
          'uploader': '/upload/tmpfile',
          'fileSizeLimit': '1MB',
          'fileTypeExts': '*.jpg;*.png;*.jpeg;*.bmp;*.png',
          'formData': {
            'sid': '{{sid}}',
            'type': type
          },
          'buttonClass': 'upload_btn',
          'buttonText': '上传图片',
          'multi': false,
          onDialogOpen: function() {
            $('.gy_step').eq(1).addClass("active").siblings().removeClass("active");
          },
          onUploadSuccess: function(file, data, response) {
            data = $.parseJSON(data);
            alert(data.msg);
            if (data.status) {
              // show_img.attr("src":data.path);
              show_img.html(data.html);
            }
          }
        });
      }
    // var timer22 = setTimeout(bankImg($('#ent_logo_pic'),38,$('#ent_show_logo_pic'),$('#ent_logo_pic_hide')),10);
    bankImg($('#ent_logo_pic'),41,$('#ent_show_logo_pic'),$('#ent_logo_pic_hide'));
    });
  //new YMDselect('year1','month1');
  //new YMDselect('year1','month1',1990);
  //new YMDselect('year1','month1',1990,2);
  //new YMDselect('year1','month1','day1');
  new YMDselect('year1','month1','day1');
  //new YMDselect('year1','month1','day1',1990,2);
  //new YMDselect('year1','month1','day1',1990,2,10);
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