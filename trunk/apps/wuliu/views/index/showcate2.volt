
    <div class="kinds f-oh" >
        <div class="small f-fl">
            {% for  key,val in  threecategorys %}
             <a href="#">{{val["title"]}}</a>   
            {% endfor %}
        </div>
        <div class="sort-img f-fl">
            <ul class="f-oh">
                {% for key,val in sell %}
                <li>
                    <a href="/sell/info/{{val['id']}}">
                        <div class="imgs f-oh">
                            <img class="f-fl" src="{% if val['thumb'] %}{{ constant('IMG_URL') }}{{val['thumb']}}{% else %}http://static.ync365.com/mdg/images/detial_b_img.jpg{% endif %}" width="136" height="136" />
                        </div>
                        <div class="name">{{val['title']}}</div>
                        <div class="line"></div>
                        <div class="price">
                            价格：<strong>{{val['min_price']}}~{{val['max_price']}}</strong>
                        </div>
                    </a>
                </li>
                {% endfor  %}
            </ul>
        </div>
    </div>
