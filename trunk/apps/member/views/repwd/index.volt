<!--头部-->
{{ partial('layouts/member_header') }}
<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com/mdg/version2.5/css/verfiy.css">
<div class="wrapper">
    <div class="w1190 mtauto f-oh">
        <div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}修改密码</span>
        </div>
        <!-- 左侧 -->
        {{ partial('layouts/navs_left') }}
        <!-- 右侧 -->
            <div class="center-right f-fr">
                
                <div class="change-password f-oh pb30" style="border-top:solid 1px #e4e4e4">

                    <div class="title f-oh">
                        <span>修改密码</span>
                    </div>
                    <form action="/member/repwd/save" method="post" id="repwd">
                        <div class="message clearfix">
                            <font>原密码：</font>
                            <div class="inputBox f-pr">
                                <input type="password" data-rule="required;oldpwd;remote[/member/repwd/checkpwd]" data-tip="请输入原始密码" name="oldpwd" >
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font>新密码：</font>
                            <div class="inputBox f-pr">
                                <input type="password" name="pwd" id='pwd' data-rule="required;password" data-tip="请输入新密码" value="" >
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font>密码确认：</font>
                            <div class="inputBox f-pr">
                                <input type="password" name="repwd" data-rule="required;repwd" data-tip="请再次输入新密码" value="" >
                            </div>
                        </div>
                        <input class="deter-btn" type="submit" value="确定" />
                   </form>
                </div>
                
            </div>
    </div>
</div>
<script>
 $('#repwd').validator({
        rules: {
            repwd: function(element, param, field) {
                return $('#pwd').val() == element.value || '两次输入的密码不一致';
            }
        }
       /* fields: {
          'oldpwd': '原密码:required;remote[/member/repwd/checkpwd]',
        }*/
});
</script>
<!--底部-->
{{ partial('layouts/footer') }}}

