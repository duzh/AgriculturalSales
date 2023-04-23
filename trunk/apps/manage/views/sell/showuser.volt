<table class="browse" align="center">
    {% if user   %}
    <thead>
        <tr>
            <th width="40%">联系电话</th>
            <th width="20%">姓名</th>
            <th width="40%">地区</th>
        </tr>
    </thead>
    <tbody>
        <?php   foreach ($user as $key => $value) { ?>
        <tr  align="center">
            <td ><input type="radio"  id='username' name="username" value="<?php echo $value['id']; ?>" onclick="checkuser(<?php echo $value['id']; ?>)"><?php echo $value['username']; ?></td>
            <td id="name<?php echo $value['id']; ?>"><?php echo Mdg\Models\Sell::uname($value['id']) ?></td>
            <td><?php echo Mdg\Models\Sell::uaddress($value['id']) ?></td>
        </tr>
        <?php } ?>
    </tbody>
    {% else %}
     <tr><td>该手机号没有注册！</td></tr> 
    {% endif %}
</table>
<script>
function checkuser(id){
    var api = frameElement.api, W = api.opener;

    W.location.href = "/manage/sell/new?u="+id;
   
     // W.jQuery('#user').val($("#name"+id+"").text());
     // W.jQuery('#userid').val(id);
     // W.closeDialog();
}
</script>
