<style>
ul li {line-height: 37px;}
</style>
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.css" />
<div class="dialog" style="width:425px; padding-bottom:3px;">
    <form action="/member/dialog/changestimepro" method="post" id="editprice">
             
                  <div class="formBox clearfix" >
                    <font>预约采摘时间：</font>
                    <div class="selectBox">
                        <em>2015年</em>
                        <select name="except_shipping_time">
                            <option value="2015-08-16" {% if endtime == "2015-08-16" %} selected='selected' {% endif %}>8月16日</option>
                            <option value="2015-08-23" {% if endtime == "2015-08-23" %} selected='selected' {% endif %}>8月23日</option>
                            <option value="2015-08-30" {% if endtime == "2015-08-30" %} selected='selected' {% endif %}>8月30日</option>
                            <option value="2015-09-06" {% if endtime == "2015-09-06" %} selected='selected' {% endif %}>9月6日</option>
                            <option value="2015-09-13" {% if endtime == "2015-09-13" %} selected='selected' {% endif %}>9月13日</option>
                            <option value="2015-09-20" {% if endtime == "2015-09-20" %} selected='selected' {% endif %}>9月20日</option>
                            <option value="2015-09-27" {% if endtime == "2015-09-27" %} selected='selected' {% endif %}>9月27日</option>
                            <option value="2015-10-04" {% if endtime == "2015-09-04" %} selected='selected' {% endif %}>10月4日</option>
                            <option value="2015-10-11" {% if endtime == "2015-09-11" %} selected='selected' {% endif %}>10月11日</option>
                            <option value="2015-10-18" {% if endtime == "2015-09-18" %} selected='selected' {% endif %}>10月18日</option>
                            <option value="2015-10-25" {% if endtime == "2015-09-25" %} selected='selected' {% endif %}>10月25日</option>
                        </select>
                        <em>星期日</em>
                    </div>
                </div>
                <li><span class="label">&nbsp;</span><em>
                <input type="hidden" name="order_id" value="{{ orders.id }}" />
                <input class="fu_btn" type="submit" value="确认修改" /></em>
            </li>
  
    </form>
</div>

<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery.form.js"></script>

<script>
    
var api = frameElement.api, W = api.opener;

$(function(){
   window.parent.dialog.size(425,193);
})

</script>
