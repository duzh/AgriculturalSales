{{ content() }}
{{ form("admin/save", "method":"post") }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />

<div class="main">
  <div class="main_right">
    <div class="bt2">编辑管理员</div>
    <div class="cx">
         <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
                <tr>
                    <td class="cx_title">管理员账号：</td>
                    <td class="cx_content">
                        {{ username}}
                     </td>
                </tr>
                <tr>
                    <td class="cx_title">最后登录时间：</td>
                    <td class="cx_content">
                         {{ lastlogintime}}
                     </td>
                </tr>
                <tr>
                    <td class="cx_title">登陆ip：</td>
                    <td class="cx_content">
                         {{ lastloginip}}
                     </td>
                </tr>
                <tr>
                    <td class="cx_title">登陆次数：</td>
                    <td class="cx_content">
                         {{ logincount}}
                     </td>
                </tr>
                <tr>
                    <td class="cx_title">创建时间：</td>
                    <td class="cx_content">
                         {{ createtime}}
                     </td>
                </tr>
                <tr>
                    <td class="cx_title">修改角色：</td>
                    <td class="cx_content">
                            <select  name="role_id">
                                {% for key,val in role %}
                                <option value="{{val.id}}" {% if role_id===val.id %} selected=selected {% endif %} >{{val.rolename}}</option>
                                {% endfor %}
                            </select>
                     </td>
                </tr>

         </table>
         
    </div> 
    <div align="center" style="margin-top:20px;">
        <td>{{ hidden_field("id") }}</td>
          {{ submit_button("修改",'class':'sub') }} 
         </div>
  </div>
</form>
  <!-- main_right 结束  -->  
</div>


