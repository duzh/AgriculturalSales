<div class="box clearfix">
                        <div class="message clearfix">
                            <font>卖家手机号：</font>
                            <div class="inputVal">
                                <input type="text" name='add_partner_phone' value='' data-rule="required;mobile;"  />
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font>卖家名称：</font>
                            <div class="inputVal">
                                <input type="text"  name='add_partner_name' value='' data-rule="required;" />
                            </div>
                        </div>
                    </div>
                    <div class="tip">温馨提示：由于卖家手机号还未在丰收汇注册，在提交订单后，我们会自动帮其注册一个账户，并将密码发送的该注册手机.</div>
                    <div class="radioBox clearfix">
                       
                        <label class="f-db clearfix">
                            <input type="radio" checked />
                            <font>结算到银行</font>
                        </label>
                    </div>
                    <div class="box clearfix">
                        <div class="message clearfix">
                            <font>结算银行：</font>
                            <div class="selectVal">
                                <select name='add_bank_name' data-rule="required;" >
                                    {% for key, val in bankList %}
                                        <option value='{{ val['gate_id'] }}'>{{ val['bank_name'] }}</option>
                                    {% endfor %}
                                </select>
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font>开户名：</font>
                            <div class="inputVal">
                                <input type="text" name='add_bank_account'   data-rule="required;" />
                            </div>
                        </div>
                    </div>
                    <div class="box clearfix">
                        <div class="message clearfix">
                            <font>银行卡号：</font>
                            <div class="inputVal">
                                <input type="text"  name='add_bank_card' value='' data-rule="required;" />
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font>银行所在地：</font>
                            <div class="inputVal">
                                <input type="text"  name='add_bank_address' value='' data-rule="required;" />
                            </div>
                        </div>
                    </div>
                    <div class="box clearfix">
                        <div class="message clearfix">
                            <font>采购数量：</font>
                            <div class="shortVal">
                                <input type="text" name='add_goods_number' id='add_goods_number' value='' class='goods_number' data-rule="required;"  onblur="add_goods_numbers(this)"/>
                                <i class="unit">公斤</i>
                            </div>
                        </div>
                    </div>
                    <div class="addPrice f-tar">
                        <span>
                            采购金额：
                            <i class='goodsAmount'>0</i>
                            元
                        </span>
                    </div>
                    <div class="btn-box f-fr clearfix">
                        <label class="f-db clearfix">
                            <input type="checkbox" checked name='add_user_partner' />
                            <font>同时添加为我的商友</font>
                        </label>
                        <input onclick="removeSeller()" class="btn1" type="button" value="取消" />
                        <input onclick="addSellerCon()" class="btn2" type="submit" value="添加" />
                    </div>

                </div>