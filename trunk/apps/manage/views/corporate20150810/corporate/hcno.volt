 <div id="test2_2" class="tablist">
        <!-- 列表 -->
        <div class="neirong">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr align="center" class="alt">
                    <td width="10%" class="bj">用户ID</td>
                    <td width="10%" class="bj">手机号</td>
                    <td width="10%" class="bj">名称</td>
                    <td width="10%" class="bj">类型</td>
                    <td width="15%" class="bj">所在地区</td>
                    <td width="15%" class="bj">服务区域</td>
                    <td width="10%" class="bj">申请时间</td>
                    <td width="10%" class="bj">状态</td>
                    <td width="10%" class="bj">操作</td>
                </tr>
                {% for key, item in users['items'] %}
                <tr align="center">
                  <td >{{ item['id']}}</td>
                  <td >{{ item['username'] ? item['username'] : '-'}}</td>
                  <td >{{ item['ext'] ? item['ext']['name'] : '-'}}</td>
                  <td >{% if item['info'] and item['info']['type']==1 %}企业{% elseif item['info'] and item['info']['type']==0 %}个人{% endif %}</td>
                  <td >{% if item['info'] %}{{item['info']['credit_id']}}{% else %}-{% endif %}</td>
                  <td >{% if item['info'] %}{{item['info']['province_name']}}{{item['info']['city_name']}}{{item['info']['district_name']}}{{item['info']['address']}}{% else %}-{% endif %}</td>
                  <td >{% if item['area'] %}{{ item['area']['province_name'] }}{{ item['area']['city_name'] }}{{ item['area']['district_name'] }}{{ item['area']['village_name'] }}{{ item['area']['town_name'] }}{% else %}-{% endif %}</td>
                  <td >{{ date('Y-m-d H:i:s', item['regtime'] ) }}</td>
                  <td >{% if item['info'] %}<?=Mdg\Models\UserInfo::$_status[$item['info']['status']]?>{% else %}-{% endif %}</td>
                  <td >
                    <a href="/manage/quotation/get/{{ item['id']}}">查看</a>
                    <a href="/manage/quotation/get/{{ item['id']}}">编辑</a>
                    {% if item['info'] and item['info']['status']==2 %}
                      <a href="/manage/quotation/get/{{ item['id']}}">取消认证</a>
                    {% endif %}

                  </td>
                </tr>
                  {% endfor %}
            </table>
        </div>

  {{ form("quotation/index", "method":"get") }}
  <div class="fenye">
    <div class="fenye1">
      <span>{{ data['pages'] }}</span> <em>跳转到第
        <input type="t_GET" class='input' name='p' <?php if(isset($_GET['p'])&&$_GET['p']!=''){ echo "value='".$_GET['p']."'" ;}else{ echo "value='1'"; } ?>/>页</em>
      <?php unset($_GET['p']);
              foreach ($_GET as $key =>
      $val) {
          echo "<input type='hidden' name='{$key}' value='{$val}'>";
        }?>
      <input class="sure_btn"  type='submit' value='确定'></div>
  </div>
</form>
    </div>