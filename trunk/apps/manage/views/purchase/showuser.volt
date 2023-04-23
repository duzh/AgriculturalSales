<table class="browse" align="center">
    {% if user %}
    <thead>
        <tr>
            <th>联系电话</th>
            <th>姓名</th>
            <th>地区</th>
        </tr>
    </thead>
    <tbody>
        <?php   foreach ($user as $key => $value) { ?>
        <tr>
         <td id="phone<?php echo $value['id']; ?>">
         <input type="radio"  id='username' name="username" value="<?php echo $value['id']; ?>" onclick="checkuser(<?php echo $value['id']; ?>)"><?php echo $value['username']; ?>
         </td>
            <td id="name<?php echo $value['id']; ?>"><?php echo Mdg\Models\Sell::uname($value['id']) ?></td>
            <td ><?php echo Mdg\Models\Sell::uaddress($value['id']) ?></td>
        </tr>
        <?php } ?>
    </tbody>
    {% else %}
    该手机号没有注册 ！    
    {% endif %}
</table>
<script>
function checkuser(id){
    var api = frameElement.api, W = api.opener;
     W.location.href = "/manage/purchase/new?u="+id;
}
</script>
