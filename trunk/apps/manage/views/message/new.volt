<div class="main_right">
    <div class="bt2">新建消息</div>
    <div class="chaxun">
   
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td   align="right" width="15%">消息类型：</td>
          <td   align="left" width="85%">
            <input type="radio" value="1" name="object" class="chec" checked/>
            采购推荐
            <input  type="radio" value=""   value="0" name="object" class="chec" <?php if(isset($_SESSION['message']['message']['type']) && $_SESSION['message']['message']['type']=='供应推荐'){echo 'checked';}?>/>
            供应推荐</td>
        </tr>
      </table>
      <form action="/manage/message/new_session/1" class="new" method="post">
      <div id="id1" <?php if(isset($_SESSION['message']['message']['theme']) || !isset($_SESSION['message']['message']['themee'])){ echo "style='display:block'";}else{echo "style='display:none'";}?>>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tr>
          <td  width="15%" height="35" align="right">主题：</td>
          <td  width="85%" height="35" align="left"><input name="theme" type="text" value="<?php if(isset($_SESSION['message']['message']['theme'])){echo $_SESSION['message']['message']['theme'];}?>"/>
        </tr>
          <tr>
            <td   align="right" height="35" >采购商名称：</td>
            <td   align="left" height="35" ><input name="buyer_name" type="text" value="<?php if(isset($_SESSION['message']['message']['buyer_name'])){echo $_SESSION['message']['message']['buyer_name'];}?>"/></td>
          </tr>
          <tr>
            <td   align="right" height="35" >联系人：</td>
            <td   align="left" height="35" ><input name="buyer_contact" type="text" value="<?php if(isset($_SESSION['message']['message']['buyer_contact'])){echo $_SESSION['message']['message']['buyer_contact'];}?>"/></td>
          </tr>
          <tr>
            <td   align="right" height="35" >联系电话：</td>
            <td   align="left" height="35" ><input name="buyer_phone" type="text" value="<?php if(isset($_SESSION['message']['message']['buyer_phone'])){echo $_SESSION['message']['message']['buyer_phone'];}?>"/></td>
          </tr>
          <tr>
            <td   align="right" height="35" >采购商品名称：</td>
            <td   align="left" height="35" ><input name="buyer_goods" type="text" value="<?php if(isset($_SESSION['message']['message']['buyer_goods'])){echo $_SESSION['message']['message']['buyer_goods'];}?>"/></td>
          </tr>
          <tr>
            <td   align="right" height="35" >采购要求</td>
            <td   align="left" height="35" ><textarea name="buyer_desc" cols="" rows="" ><?php if(isset($_SESSION['message']['message']['buyer_desc'])){echo $_SESSION['message']['message']['buyer_desc'];}?></textarea></td>
          </tr>
        </table>
         <div class="btn">
        <input type="submit" value="下一步" class="sub4"/>
      </div>
      </div>
      </form>
      <form action="/manage/message/new_session/2" class="new1" method="post">
      <div id="id2" <?php if(isset($_SESSION['message']['message']['themee'])){ echo "style='display:block'";}else{echo "style='display:none'";}?>>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">

         <tr>
          <td  width="15%" height="35" align="right">主题：</td>
          <td  width="85%" height="35" align="left"><input name="themee" type="text" value="<?php if(isset($_SESSION['message']['message']['themee'])){echo $_SESSION['message']['message']['themee'];}?>"/>
        </tr>
          <tr>
            <td   align="right" height="35" >供应商名称：</td>
            <td   align="left" height="35" ><input name="supplier_name" type="text" value="<?php if(isset($_SESSION['message']['message']['supplier_name'])){echo $_SESSION['message']['message']['supplier_name'];}?>"/></td>
          </tr>
          <tr>
            <td   align="right" height="35" >联系人：</td>
            <td   align="left" height="35" ><input name="supplier_contact" type="text" value="<?php if(isset($_SESSION['message']['message']['supplier_contact'])){echo $_SESSION['message']['message']['supplier_contact'];}?>"/></td>
          </tr>
          <tr>
            <td   align="right" height="35" >联系电话：</td>
            <td   align="left" height="35" ><input name="supplier_phone" type="text" value="<?php if(isset($_SESSION['message']['message']['supplier_phone'])){echo $_SESSION['message']['message']['supplier_phone'];}?>"/></td>
          </tr>
          <tr>
            <td   align="right" height="35" >供应商品名称：</td>
            <td   align="left" height="35" ><input name="supplier_goods" type="text" value="<?php if(isset($_SESSION['message']['message']['supplier_goods'])){echo $_SESSION['message']['message']['supplier_goods'];}?>"/></td>
          </tr>
          <tr>
            <td   align="right" height="35" >商品描述</td>
            <td   align="left" height="35" ><textarea name="supplier_desc" cols="" rows="" ><?php if(isset($_SESSION['message']['message']['supplier_desc'])){echo $_SESSION['message']['message']['supplier_desc'];}?></textarea></td>
          </tr>
        </table>
         <div class="btn">
        <input type="submit" value="下一步" class="sub4"/>
      </div>
      </div>
     
      <script type="text/javascript">
window.onload = function() {
    var radios = document.getElementsByName('object');
    for (var i = 0; i < radios.length; i++) {
          radios[i].indexs = i + 1;
        radios[i].onchange = function () {
            if (this.checked) {
                document.getElementById("id1").style.display="none";
                document.getElementById("id2").style.display="none";
                document.getElementById("id" + this.indexs).style.display="block";
            } 
        }
    }
}
</script> 
    </div>
    </form>
  </div>
  <!-- main_right 结束  --> 
</div>
<!--中间结束   --> 

<!--底部开始   -->
<div class="footer"> Copyright © 2013-2014 ync365.com All rights reserved. </div>
<!--底部结束   -->
<script>  
            $(".new").validator({
             fields:  {
          'theme' :'主题:required',
                  'buyer_name' :'采购商名称:required',
          'buyer_goods' :'采购商品名称:required',
          'buyer_require' :'采购要求:required',
          'buyer_contact' :'联系人:required;',
          'buyer_phone' :'联系电话:required;mobile',

             }
           });
        $(".new1").validator({
             fields:  {
          'theme' :'主题:required',
          'supplier_name' :'供应商名称:required',
          'supplier_goods' :'供应商品名称:required',
          'supplier_desc' :'商品描述:required',
          'supplier_contact' :'联系人:required;',
          'supplier_phone' :'联系电话:required;mobile'

             }
           })
</script>
</body>
</html>
