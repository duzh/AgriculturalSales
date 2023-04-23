  <!-- main_right 开始  -->
  <div class="main_right">
    <div class="bt2">查看消息</div>
    <div class="fwz" id="tb">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr align="center">
          <td  width="15%" align="right">消息状态：</td>
          <td width="85%" align="left"><?php echo Mdg\Models\Message::$_is_status[$message->status];?></td>
        </tr>
        <tr align="center">
          <td align="right">主题：</td>
          <td align="left">{{message.comment}}</td>
        </tr>
        <tr align="center">
          <td align="right">消息类型：</td>
          <td align="left"><?php echo Mdg\Models\Message::$_type[$message->type];?></td>
        </tr>
        <tr align="center">
          <td align="right">{% if message.type ==1 %}采购商名称：{% else %}供应商名称：{% endif %}</td>
          <td align="left">{{message.buyer_name}}</td>
        </tr>
        <tr align="center">
          <td align="right">联系人：</td>
          <td align="left">{{message.contact_man}}</td>
        </tr>
        <tr align="center">
          <td align="right">联系电话：</td>
          <td align="left">{{message.contact_phone}}</td>
        </tr>
        <tr align="center">
          <td align="right">{% if message.type ==1 %}采购商品名称：{% else %}供应商品名称：{% endif %}</td>
          <td align="left">{{message.goods_name}}</td>
        </tr>
        <tr align="center">
          <td align="right">{% if message.type ==1 %}采购要求：{% else %}供应要求：{% endif %}</td>
          <td align="left">{{message.require}}</td>
        </tr>
      </table>
    </div>
   
    <div class="neirong" id="tb">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr align="center">
          <th>姓名</th>
          <th>联系方式</th>
          <th>主营</th>
          <th>所在地区</th>
          <th>身份类型</th>
          <th>状态</th>
        </tr>
        {% for v in messageusers %}
        <tr align="center">
          <td>{{v['name']}}</td>
          <td>{{v['phone']}}</td>
          <td>{{v['goods']}}</td>
          <td>{{v['areas']}}</td>
          <td><?php echo Mdg\Models\Message::$_user_identity[$v['type']];?></td>
          <td><?php echo Mdg\Models\Message::$is_new[$v['is_new']];?></td>
        </tr>
      {% endfor %}
      </table>
  </div>
  <?php if($message->status=='0'){?>
   <form method="get" action="/manage/message/updatestatussave" id="demo">
   <div class="btn">
      <input type='hidden' name='msg_id' value="{{message.msg_id}}">
          <input type="submit" value="发送" class="sub4" name='fasong'/>  
          <span id="product"></span>
        </div>
      </form>
<?php }?>
  <!-- main_right 结束  --> 
</div>
<!--中间结束   --> 

<!--底部开始   -->
<div class="footer"> Copyright © 2013-2014 ync365.com All rights reserved. </div>
<!--底部结束   -->
</body>
</html>
