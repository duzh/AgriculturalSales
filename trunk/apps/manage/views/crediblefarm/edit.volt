{{ form("crediblefarm/save", "method":"post","id":"editfarm") }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
    <div class="main_right">

        <div class="bt2">修改可信农场信息</div>
        <div align="left" style="margin-top:20px;margin-left:20px">
          <ul class="zhibiaoTab">
            <li class="active"><a href="/manage/crediblefarm/edit/{{user_id}}">农场信息</a></li>
            <li><a href="/manage/crediblefarm/advert/{{user_id}}">宣传图</a></li>
            <li><a href="/manage/crediblefarm/present/{{user_id}}">农场介绍</a></li>
            <li><a href="/manage/crediblefarm/footprint/{{user_id}}">发展足迹</a></li>
            <li><a href="/manage/crediblefarm/mainproduct/{{user_id}}">产品推荐</a></li>
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
                    <td class="cx_title">农场名：</td>
                    <td class="cx_content paddingtd"><input type="text" name="farm_name" value="{{farmname}}" ></td>
                </tr>
            </table>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="cx_title">农场简介：</td>
                    <td class="cx_content paddingtd">
                        <div class="area-box" id="textArea">
                                <textarea name="desc" cols="100" rows="10">{{farmdesc}}</textarea>
                                <i>您还可以输入<label>100</label>个字</i>
                                <input type="hidden" id="srNum" name="srNum" value="" />
                            </div>
                    </td>
                </tr>
            </table>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="top" class="cx_title">
                        <font style="display:block;margin-top:12px;">农场logo：</font>
                    </td>
                    <td class="cx_content paddingtd">
                        <div class="loadImg-box">
                            <div class="file-btn">
                                <input class="btn2" type="file" id='ent_logo_pic' />
                                <input id="ent_logo_pic_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value="" />
                                <i id='ent_logo_pic_tip' style="position:absolute; left:176px; top:-6px;"></i>  
                            </div>
                            图片规格157 X 77,图片大小不超过2M，支持jpg、png、gif格式（使用高质量图片，可提高成交的机会）
                            <ul class="clearfix">
                                <li class="f-fl imgs" id="ent_show_logo_pic">
                                    
                                    {% if crediblefarminfo.logo_pic %}
                                    <img src="{{ constant('IMG_URL') }}{{crediblefarminfo.logo_pic}}" height="77" width="157">
                                    <a class="f-db" href="javascript:;" onclick="closeImgup(this, {{crediblefarminfo.id}},3);">删除</a>
                                    {% else %}
                                    <input type="hidden" name="yanz" value='' data-rule="图片:required;">
                                    {% endif %}
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>           
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="top" class="cx_title">
                        <font style="display:block;margin-top:12px;">农场图片：</font>
                    </td>
                    <td class="cx_content paddingtd">
                        <div class="loadImg-box">
                            <div class="file-btn">
                                    <input class="btn2" type="file" id='ent_img_pic' />
                                    <input id="ent_img_pic_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value="" />
                                    <i id='ent_img_pic_tip' style="position:absolute; left:176px; top:-6px;"></i>  
                                </div>
                            图片规格220 X 220,图片大小不超过2M，支持jpg、png、gif格式（使用高质量图片，可提高成交的机会）
                            <ul class="clearfix">
                                    <li class="f-fl imgs" id="ent_show_img_pic">
                                        {% if crediblefarminfo.img_pic %}
                                        <img src="{{ constant('IMG_URL') }}{{crediblefarminfo.img_pic}}" height="77" width="157">
                                        <a class="f-db" href="javascript:;" onclick="closeImgup(this, {{crediblefarminfo.id}},6);">删除</a>
                                        {% else %}
                                        <input type="hidden" name="yanz" value='' data-rule="图片:required;">
                                        {% endif %}
                                    </li>
                                </ul>
                        </div>
                    </td>
                </tr>           
            </table>

        </div>
        <div align="center" style="margin-top:20px;">
             <input type="hidden" name="user_id" value="{{user_id}}">
             <input type="submit" value="修改" class="sub"/>
        </div>
    </div>
</form>
    <!-- main_right 结束  -->
</div>

<script type="text/javascript">
    $("#editfarm").validator({
     fields:  {
         farm_name:"农场名:required;",
         desc:"农场简介:required;",
         
     },   
});
</script>
<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
</body>
</html>

<script>
// 删除图片
function closeImgup(obj, id, type) {
    $.getJSON('/manage/crediblefarm/delgraphic', {id : id,type : type}, function(data) {
        alert(data.msg);
        if(data.state) {
            $(obj).parent('li').remove();  
        }
    });
}

// 删除图片
function closeImg(obj, id) {

    $.getJSON('/upload/deltmpfile', {
        id: id
    }, function(data) {
        alert(data.msg);
        if (data.state) {
            $(obj).parents('.imgBox').slideUp();
        }
    });
}


$(function(){
            //字数统计
            $(document).keyup(function() {
                var text=$("#textArea textarea").val();
                var counter=text.length;
                if(counter > 100){
                    $('#srNum').val('');
                    return ;
                }else{
                    $("#textArea label").text(100-counter);
                    $('#srNum').val(100-counter);
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
 // var timer22 = setTimeout(bankImg($('#ent_logo_pic'),35,$('#ent_show_logo_pic'),$('#ent_logo_pic_hide')),10);
    bankImg($('#ent_logo_pic'),35,$('#ent_show_logo_pic'),$('#ent_logo_pic_hide'));
    bankImg($('#ent_img_pic'),42,$('#ent_show_img_pic'),$('#ent_img_pic_hide'));
})
</script>
<style>
td.paddingtd{ padding:20px 0;}
.table_22{ width:702px; border-collapse: collapse;}
.table_22 tr td{ border:1px solid #ccc; text-align: center;}
.upload_btn { width:88px; height:28px; line-height:28px; text-align: center; background: #f7f7f7; border: solid 1px #e4e4e4; color:#666;
  font-family: '微软雅黑';
  cursor: pointer;
  position: relative;}

.zhibiaoTab li{ float: left; width:100px; height: 32px; text-align: center; line-height: 32px; }
.zhibiaoTab li.active{ border:1px solid #ccc; border-bottom: none; height: 32px; background:#fff;}
.cx{ margin-top:0;}
.zhibiaoTab li.active{ }
.zhibiaoTab li.active a{ color:#8AAF29; font-weight: bold;}
.icon{ background: none;}
</style>