<!-- 头部开始 -->
{{ partial('layouts/page_header') }}
<!-- 头部结束 -->

<!-- 主体内容开始 -->
<div class="ur_here w960">
    <span>{{ partial('layouts/ur_here') }}我的云农宝</span>
</div>
<div class="personal_center w960 mb20">
    <!-- 左侧 start -->
    {{ partial('layouts/navs_left') }}
    <!-- 左侧 end -->
    <!-- 右侧 start -->
    <div class="center_right f-fr">
        <div class="bind_account">
            <ul class="title clearfix">
                <li class="f-db f-fl active">1、验证手机</li>
                <li class="f-db f-fl">2、完成</li>
            </ul>
            <div class="bindForm">
                <form action="/member/bind/save" method="post" id="bindAccForm" name="bindAccForm">
                    <div class="formBox clearfix"> <font class="f-db f-fl">请输入您的手机号：</font>
                        <div class="inputTxt">

                            <span id='telChange'><i>{{data['mobile']}}</i></span>
                            <input type="hidden" name="mobile" id='mobile'  value="{{ data['mobile']}}" />
                        </div>
                    </div>
                    <div class="formBox clearfix"> <font class="f-db f-fl">&nbsp;</font>
                        <div class="inputBtn clearfix">
                            <input class="yzBtn f-db f-fl" type="button" value="获取手机验证码" id='getvcode' />
                        </div>
                    </div>
                    <div class="formBox clearfix">
                        <font class="f-db f-fl">输入短信验证码：</font>
                        <div class="inputTxt">
                            <input type="text" name="msg_yz" value='' />
                        </div>
                    </div>
                    <div class="formBox clearfix">
                        <font class="f-db f-fl">验证码：</font>
                        <div class="yzTxt clearfix f-pr">
                            <input class="f-fl" type="text" name="img_yz" data-target="#yzTips" />
                            <img class="f-fl" src="/member/code/index"  id='codeimg' onclick="javascript:this.src='/member/code/index?tm='+Math.random();" />
                            <a class="f-fl" href="javascript:;" onclick="fun()" >看不清换一张</a> <i class="pa" id="yzTips"></i>
                        </div>
                    </div>
                    <input type="submit" class="bindAccBtn" value="提交" />
                </form>
            </div>
        </div>
    </div>
    <!-- 右侧 end -->
</div>
<!-- 主体内容结束 -->

<!-- 底部开始 -->
{{ partial('layouts/footer') }}
<!-- 底部结束 -->

<script>



$(function(){
    var text = $('#telChange i').text();
    var val = text.substring(0,3) + '****' + text.substring(8,11);
    $('#telChange i').text(val);
   
});

var limit_time = times = 60;
var _setInterval = null;
$(function(){
    $('#bindAccForm').validator({
        //stopOnError : true,
        messages: {
            required : "{0}不能为空"
        },
        rules: {
            
        },
        fields: {
            mobile: "手机号:required;mobile;",
            msg_yz: "短信验证码:required;remote[/member/bind/checkcode ]",
            img_yz: "验证码:required;"
        }
    });
    $('input[name="mobile"]').on('valid.field', function(e, result, me){
        $('#getvcode').removeAttr('disabled');
    });

    $('input[name="vcode"]').on('invalid.field', function(e, result, me){
        $('#codemsg').show();
    });

    $('input[name="vcode"]').on('valid.field', function(e, result, me){
        $('#codemsg').hide();
    });

    $('#getvcode').click(function(){

        if(_setInterval!=null) return;
        var mo = $('#mobile').val();
   
        $.getJSON('/member/bind/getcode', {mobile:mo},function(data) {
            if(data.ok=="1"){
                $(this).attr('disabled','disabled');
                _setInterval = setInterval('clock()',1000);
            }else{
                alert(data.error);
            }
        });
    });

    // //获取短信验证码
    // (function(){
    //  $('.yzBtn').on('click', function(){
    //      var num = 60,
    //          timer = null;
    //      $(this).attr('disabled', 'disabled');
    //      function dao(){
    //          $('.yzBtn').val(num + 'S后重新获取');
    //          num -- ;

    //          if(num == -1){
    //              clearInterval(timer);
    //              $('.yzBtn').val('获取手机验证码');
    //              $('.yzBtn').removeAttr('disabled');
    //          }
    //      }
    //      dao();
    //      timer=setInterval(dao,1000);                
    //  })
    // })();

});
//验证码切换
function fun(){
    var tm=Math.random();
    $("#codeimg").attr("src","/member/code/index?tm="+tm);
}
function clock(){
    times--;
    if(times==0){
        clearInterval(_setInterval);
        _setInterval = null;
        $('#getvcode').removeAttr('disabled');
        times = limit_time;
        $('#getvcode').val("获取手机验证码");
        return;
    }
    var tpl = "S后重新获取";
    $('#getvcode').val(times+tpl);
}
</script>
</body>
</html>