{{ partial('layouts/page_header') }}
<div class="wuliu_body_v2">
        <div class="wuliu_v2 w1190 mtauto">
            <div class="mianbao_v2">
                <a href="#">首页</a>
                <span>&gt;</span>
                <a href="/wuliu/index/">物流信息</a>
                <span>&gt;</span>
                <span>车源信息</span>
            </div>

            <div class="wuliu_left_v2 f-fl">
                <div class="cheyuanTable_v2">
                    <table>
                        <tr>
                            <td colspan="4"><strong class="cheyuan_title_v2"> {{ data['start_pname']}}{{ data['start_cname']}}{{ data['start_dname']}}到{{ data['end_pname']}}{{ data['end_cname']}}{{ data['end_dname']}}的车辆信息</strong></td>
                        </tr>
                        <tr class="tr_bg_v2">
                            <td colspan="4"><span class="table_title_v2">车主信息</span></td>
                        </tr>
                        <tr>
                            <td width="20%"><span class="table_span_right">车源编号：</span></td>
                            <td width="30%"><span class="table_span_left">{{ data['car_id'] }}</span></td>
                            <td width="20%"><span class="table_span_right">发布日期：</span></td>
                            <td width="30%"><span class="table_span_left">{{ data['sendtime']}}</span></td>
                        </tr>
                        <tr>
                            <td><span class="table_span_right">联系人：</span></td>
                            <td><span class="table_span_left">{{ data['contact_man'] }}</span></td>
                            <td><span class="table_span_right">手机号：</span></td>
                            <td>
                                {% if data['phone_number'] %}
                                    {% if data['phone_img'] %}
                                    <img src="{{ constant('ITEM_IMG')}}/{{data['phone_img']}}" id='phone_img_{{data['car_id']}}'style='display:none' alt="联系人号码">
                                        <span class="table_span_left">
                                        {{ data['phone_number'] }}
                                        <a href='javascript:;' onclick="showImg(this,{{data['car_id']}})">(查看)</a>
                                        </span>
                                 
                                    {% endif %}
                                {% endif %}
                            </td>
                        </tr>
                        <tr class="tr_bg_v2">
                            <td colspan="4"><span class="table_title_v2">车辆信息</span></td>
                        </tr>
                        <tr>
                            <td><span class="table_span_right">车牌号：</span></td>
                            <td><span class="table_span_left">{{ data['car_licence'] }}</span></td>
                            <td><span class="table_span_right">车辆信息：</span></td>
                            <td><span class="table_span_left">{{ _BOX_TYPE[data['ext']['box_type']] }}　　{{  _BODY_TYPE[data['ext']['body_type']]}}</span></td>
                        </tr>
                        <tr>
                            <td><span class="table_span_right">车板长度：</span></td>
                            <td><span class="table_span_left">{{ data['ext']['length']}}米</span></td>
                            <td><span class="table_span_right">重载：</span></td>
                            <td><span class="table_span_left">{{ data['ext']['carry_weight'] }}吨</span></td>
                        </tr>
                        <tr>
                            <td><span class="table_span_right">车龄：</span></td>
                            <td><span class="table_span_left">{{ data['ext']['use_time'] }}年</span></td>
                            <td><span class="table_span_right">预计发车时间：</span></td>
                            <td><span class="table_span_left">{{data['ext']['departtime']}}</span></td>
                        </tr>
                        <tr class="tr_bg_v2">
                            <td colspan="4"><span class="table_title_v2">期望流向</span></td>
                        </tr>
                        <tr>
                            <td><span class="table_span_right">出发地：</span></td>
                            <td><span class="table_span_left">{{ data['start_pname']}}{{ data['start_cname']}}{{ data['start_dname']}}</span></td>
                            <td><span class="table_span_right">期望流向：</span></td>
                            <td><span class="table_span_left">{{ data['end_pname']}}{{ data['end_cname']}}{{ data['end_dname']}}</span></td>
                        </tr>
                        <tr>
                            <td><span class="table_span_right">是否长期：</span></td>
                            <td><span class="table_span_left"><?php  echo $data['ext']['is_longtime'] == 1 ? '是': '否'; ?></span></td>
                            <td><span class="table_span_right">运行方式：</span></td>
                            <td><span class="table_span_left"><?php echo isset($_TRANSPORT_TYPE[$data['ext']['transport_type']]) ? $_TRANSPORT_TYPE[$data['ext']['transport_type']] : ''; ?></span></td>
                        </tr>
                        <tr>
                            <td><span class="table_span_right">轻货价：</span></td>
                            <td><span class="table_span_left"><?php echo $data['light_goods']>0 ? $data['light_goods']. '元/方' : '面议'; ?></span></td>
                            <td><span class="table_span_right">重货价：</span></td>
                            <td><span class="table_span_left"><?php echo $data['heavy_goods']>0 ? $data['heavy_goods']. '元/吨' : '面议'; ?></span></td>
                        </tr>
                        <tr>
                            <td colspan=""><span class="table_span_right">备注：</span></td>
                            <td colspan="3"><span class="table_span_left"><?php echo  $data['ext']['demo']; ?></span></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="wuliu_right_v2 f-fr">
                <div class="kuaisu_v2">
                    <div class="kuaisu_title_v2">
                        <strong>车源快速导航</strong>
                        <a href="/wuliu/car/index">更多&gt;</a>
                    </div>
                    <div class="kuaisu_con_v2">
                            {% for key ,item in carNavs %}
							{% if item['start_pid']!=0 %}
                                    <a href="/wuliu/car/index?start_pid={{ item['start_pid']}}">{{ item.start_pname }}</a>
							{% endif %}
                            {% endfor %}
                    </div>
                </div>
            </div>
        </div>
</div>

<script type="text/javascript">
function getPhoneImg(carid) {
    // $.get("/wuliu/car/getimgpath", {carid:carid} , function (e) {
    //     alert(e);
    // })
}
function showImg(o, carid) {
    //alert($(o).parent().length);
    $('#phone_img_'+carid).show();
    $(o).parent().hide();
}
</script>
{{ partial('layouts/footer') }}