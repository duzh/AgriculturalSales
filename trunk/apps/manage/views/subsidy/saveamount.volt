<div id="MyDiv1" class="white_content2">
    <div class="gb">
      确定审核未通过
      <a href="#" onclick="CloseDiv1('MyDiv1','fade1')"></a>
    </div>
    <div class="shenh">
      <ul>
        <li>
          <lable>请输入拒绝理由：</lable>
          <div>
            <input name="reject" type="text"  data-rule="required;"  value='' />
          </div>
        </li>
        <li>
          <lable>&nbsp;</lable>
          <div>
            <input type="submit" value="确定" class="btn3"/>
            <input type="hidden" name='HTTP_REFERER' value='{{ HTTP_REFERER}}'>
            <input type="hidden" name='subsidy_id' value='{{ subsidy.subsidy_id}}'> <!-- # 隐藏ID -->
            <input type="hidden" name="status" value="0">
            <input name="" type="button" value="取消" class="btn3" onclick="CloseDiv1('MyDiv1','fade1')"/>
          </div>
        </li>
      </ul>
    </div>