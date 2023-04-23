<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />

<div class="contianer">
    <div class="bg">
        <img src="{{ constant('STATIC_URL') }}mdg/manage/images/login_bc_img1.jpg" />
        <img src="{{ constant('STATIC_URL') }}mdg/manage/images/login_bc_img2.jpg" />
        <img src="{{ constant('STATIC_URL') }}mdg/manage/images/login_bc_img3.jpg" />
        <img src="{{ constant('STATIC_URL') }}mdg/manage/images/login_bc_img4.jpg" />
        <img src="{{ constant('STATIC_URL') }}mdg/manage/images/login_bc_img5.jpg" />
        <img src="{{ constant('STATIC_URL') }}mdg/manage/images/login_bc_img6.jpg" />
        <img src="{{ constant('STATIC_URL') }}mdg/manage/images/login_bc_img7.jpg" />
        <img src="{{ constant('STATIC_URL') }}mdg/manage/images/login_bc_img8.jpg" />
        <img src="{{ constant('STATIC_URL') }}mdg/manage/images/login_bc_img9.jpg" />
        <img src="{{ constant('STATIC_URL') }}mdg/manage/images/login_bc_img10.jpg" />
    </div>
      <form method="post" action="/manage/login/validatelogin" id="login" autocomplete="off">
    <div class="login_box">
        <h3><img src="{{ constant('STATIC_URL') }}mdg/manage/images/dl.png"></h3>
         <div class="login_message">
            <ul>
                <li>
                    <span>用户名：</span>
                    <div>
                        <input type="text"   name="mobile" id="username" onblur="loginusername()"/>
                        <em class="tips1" id="my_name" style="display:none">正确</em>
                    </div>
                </li>
                <li>
                    <span>密码：</span>
                    <div>
                        <input type="password" name="password" id="password" onblur="loginpasswd()" />
                        <em class="tips1" id="my_pwd" style="display:none">正确</em>
                    </div>
                </li>
                <li>
                    <div style="color:red;padding-left:100px;" id="logintip" >{{ content() }}</div>
                </li>
                <li>
                    <span>&nbsp;</span>
                    <div>
                        <input class="login_btn mouseHover" type="button" onclick="zong()" value="登录" />
                    </div>
                </li>
            </ul>
        </div>
        <p>Copyright ©2014，ync365.com版权所有</p>
    </div>
     </form>
</div>

<script type="text/javascript">
(function(){
    var clientHeight = document.documentElement.clientHeight || document.body.clientHeight;
    
    $('.contianer').css('height', clientHeight + 'px');
    
    $('input[type="text"]').focus(function(){
        $(this).css('border', 'solid 1px rgb(139, 176, 42)');
    });
    $('input[type="text"]').blur(function(){
        $(this).css('border', 'solid 1px #676664');
    });
    $('input[type="password"]').focus(function(){
        $(this).css('border', 'solid 1px rgb(139, 176, 42)');
    });
    $('input[type="password"]').blur(function(){
        $(this).css('border', 'solid 1px #676664');
    });
    
    $('.mouseHover').hover(function(){
        $(this).addClass('hover');
    }, function(){
        $(this).removeClass('hover');
    });
})();
function loginusername(){
    var val=$("#username").val();
    var reg =/^\w{3,12}$/;
    if(val!=""){
        document.getElementById('my_name').style.display="block";
        return true;
    }else  if(reg.test(val)){
        alert("sadsad");
        document.getElementById('my_name').style.display="block";
        return true;
    }else{

      document.getElementById('my_name').style.display="none";
       return false;
    }
}
function loginpasswd(){
     var password=$("#password").val();
     var reg = /^[0-9a-zA-Z]{6,16}$/;
      if(password!=''){
         document.getElementById('my_pwd').style.display="block";
         return true;
      }else if(reg.test(password)){
   
          document.getElementById('my_pwd').style.display="block";
         return true;
      }else{

        document.getElementById('my_pwd').style.display="none";
        return false;
      }
}
function zong(){
      if(loginusername()&&loginpasswd()){
           $("#login").submit();
      }else{
         $("#logintip").text("请输入用户名和密码");
         return false;
      }
}
</script>
</body>
</html>
