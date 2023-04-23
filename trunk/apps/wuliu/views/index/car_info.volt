<div class="search_tab_v2">
	<div class="searchBtn_v2 f-fl" id="searchBtn_v2">
		<ul>
			<li class="active">车源信息</li>
			<li>货源信息</li>
			<li>专线信息</li>
		</ul>
	</div>
	<div class="searchCon_v2 f-fl" id="searchCon_v2">
		<div class="search_con_v2" style="display:block;">
			<div class="search_title_v2"><strong>搜索全国车源信息</strong>共({{newCarList['total']}})条</div>
			<form action="/wuliu/car/index" >
			<div class="search_selectBox_v2">
				<span>出发地：</span>
				<select name="start_pid" class='car_start wl-select-s' id="">
					<option value="0" >省份</option>
				</select>
				<select name="start_cid" class='car_start wl-select-s' id="">
					<option value="0">城市</option>
				</select>

				<select name="start_did" class='car_start wl-select-s' id="">
					<option value="0">县/区</option>
				</select>
			</div>
			<div class="search_selectBox_v2">
				<span>目的地：</span>
				<select name="end_pid" class='car_end wl-select-s' id="">
					<option value="">省份</option>
				</select>
				<select name="end_cid" class='car_end wl-select-s' id="">
					<option value="">城市</option>

				</select>
				<select name="end_did" class='car_end wl-select-s' id="">
					<option value="">县/区</option>
				</select>
			</div>
			<div class="search_hot_v2">
				<span>热门车源:</span>
				{% for key ,item in CarNavs %}
					{% if key< 8 %}
				    <a href="/wuliu/car/index?start_pid={{item.start_pid}}">{{ item.start_pname}}</a>
				    {% endif %}
				{% endfor %}
			</div>
			<div class="search_sub_v2">
				<input type="submit" value="搜索">
			</div>
		    </form>
		</div>
		<div class="search_con_v2">
			<div class="search_title_v2"><strong>搜索全国货源信息</strong>共({{CarGoCount}})条</div>
			{{ form("cargo/index", "method":"get", "autocomplete" : "off") }}
				<div class="search_selectBox_v2">
					<span>出发地：</span>
					<select name="start_pid" class='cargo_start wl-select-s' id="">
						<option value="">省份</option>
					</select>
					<select name="start_cid" class='cargo_start wl-select-s' id="">
						<option value="">城市</option>
					</select>

					<select name="start_did" class='cargo_start wl-select-s' id="">
						<option value="">县/区</option>
					</select>
				</div>
				<div class="search_selectBox_v2">
					<span>目的地：</span>
					<select name="end_pid" class='cargo_end wl-select-s' id="">
						<option value="">省份</option>
					</select>
					<select name="end_cid" class='cargo_end wl-select-s' id="">
						<option value="">城市</option>
					</select>
					<select name="end_did" class='cargo_end wl-select-s' id="">
						<option value="">县/区</option>
					</select>
				</div>
				<div class="search_hot_v2">
					<span>热门货源:</span>
				    {% for key ,item in CarGoInfolimit %}
					<a href="/wuliu/cargo/index/{{ item.start_pid}}">{{ item.start_pname}}</a>
					{% endfor %}
				</div>
				<div class="search_sub_v2">
					<input type="submit" value="搜索">
				</div>
			</form>
		</div>
		<div class="search_con_v2">
			<div class="search_title_v2"><strong>搜索全国专线信息</strong>共({{ScinfoCount}})条</div>
			{{ form("special/index", "method":"get", "autocomplete" : "off") }}
			<div class="search_selectBox_v2">
				<span>出发地：</span>
				<select name="province"  class="wl-select-s startAreas">
					<option value="">省份</option>
				</select>
				<select name="city"  class="wl-select-s startAreas">
					<option value="">城市</option>
				</select>
				<select name="district"  class="wl-select-s startAreas">
					<option value="">县/区</option>
				</select>
			</div>
			<div class="search_selectBox_v2">
				<span>目的地：</span>
				<select name="endprovince"  class="wl-select-s endAreas ">
					<option value="">省份</option>
				</select>
				<select name="endcity"  class="wl-select-s endAreas ">
					<option value="">城市</option>
				</select>
				<select name="enddistrict"  class="wl-select-s endAreas ">
					<option value="">县/区</option>
				</select>
			</div>
			<div class="search_hot_v2">
				<span>热门专线:</span>
				{% for key ,item in ScInfolimit %}
				<a href="/wuliu/special/index?province={{ item.start_pid}}">{{ item.start_pname}}</a>
				{% endfor %}
			</div>
			<div class="search_sub_v2">
				<input type="submit" value="搜索">
			</div>
			</form>
		</div>
	</div>
	<div class="fabuBtn_con_v2 f-fl">
		<a href="/wuliu/car/new" target="_blank"><span>发布车源</span></a>
		<a href="/wuliu/cargo/new" target="_blank"><span>发布货源</span></a>
	</div>
</div>
