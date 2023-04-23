<div class="box clearfix">
        <div class="message clearfix f-fl">
            <font>手机号：</font>
            <div class="inputVal">
                <input type="text" />
            </div>
        </div>
        <div class="message clearfix f-fl">
            <font>付款方式：</font>
            <div class="inputVal">
                <select name='pay_type'>
                    {% for key,val in _pay_type %}
                    <option value='{{ key }}'>{{ val }}</option>
                    {% endfor %}
                </select>
            </div>
        </div>
    </div>
    <table cellpadding="0" cellspacing="0" width="100%">
        <tr height="38">
            <th width="20%">名称</th>
            <th width="20%">手机号</th>
            <th width="20%">收款方式</th>
            <th width="20%">采购量</th>
            <th width="20%">采购金额</th>
        </tr>
        {% for key , item in UserPartner['items'] %}
        <tr height="38">
            <td align="center">
                <label class="f-db clearfix">
                    <input class="check-btn f-fl" type="checkbox">
                    <font class="f-fl">{{ item.partner_name }}</font>
                </label>
            </td>
            <td align="center">{{ item.partner_phone }}</td>
            <td align="center">{{ item.paytype }}</td>
            <td align="center">
                <input class="txt" type="text"> <em class="unit">公斤</em>
            </td>
            <td align="center">
                <i>0</i>
                元
            </td>
        </tr>
        {% endfor %}
    </table>
    <!-- 分页 -->
    <div class="esc-page mt30 mb30 f-tac">
        {{ UserPartner['pages']}}
        <span>
            <label>去</label>
            <input type="text" />
            <label>页</label>
        </span>
        <input class="btn" type="submit" value="确定" />
    </div>
    <div class="btns f-tac">
        <input onclick="removeFriend()" class="btn1" type="button" value="取消" />
        <input onclick="addFriendCon()" class="btn2" type="button" value="确定" />
    </div>