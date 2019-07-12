
<div class="setup-item" style="background: rgba({{ rgb_1 }}, {{ rgb_2 }}, {{ rgb_3 }},1);">
    <a class="setup-pic" href="{{ url }}">
        <div class="tile-gradient" style="
            background: -moz-linear-gradient(left, rgba(255, 255, 255, 1) 0%, rgba({{ rgb_1 }}, {{ rgb_2 }}, {{ rgb_3 }},0.9) 80%, rgba({{ rgb_1 }}, {{ rgb_2 }}, {{ rgb_3 }},1) 100%);
            /* FF3.6+ */
            background: -webkit-gradient(linear, left top, right top, color-stop(0%, rgba(255, 255, 255, 1)), color-stop(80%, rgba({{ rgb_1 }}, {{ rgb_2 }}, {{ rgb_3 }},0.9)), color-stop(100%, rgba({{ rgb_1 }}, {{ rgb_2 }}, {{ rgb_3 }},1)));
            /* Chrome,Safari4+ */
            background: -webkit-linear-gradient(left, rgba(255, 255, 255, 1) 0%, rgba({{ rgb_1 }}, {{ rgb_2 }}, {{ rgb_3 }},0.9) 80%, rgba({{ rgb_1 }}, {{ rgb_2 }}, {{ rgb_3 }},1) 100%);
            /* Chrome10+,Safari5.1+ */
            background: -o-linear-gradient(left, rgba(255, 255, 255, 1) 0%, rgba({{ rgb_1 }}, {{ rgb_2 }}, {{ rgb_3 }},0.9) 80%, rgba({{ rgb_1 }}, {{ rgb_2 }}, {{ rgb_3 }},1) 100%);
            /* Opera 11.10+ */
            background: -ms-linear-gradient(left, rgba(255, 255, 255, 1) 0%, rgba({{ rgb_1 }}, {{ rgb_2 }}, {{ rgb_3 }},0.9) 80%, rgba({{ rgb_1 }}, {{ rgb_2 }}, {{ rgb_3 }},1) 100%);
            /* IE10+ */
              background: linear-gradient(to right, rgba(255, 255, 255, 0) 0%, rgba({{ rgb_1 }}, {{ rgb_2 }}, {{ rgb_3 }},0.9) 80%, rgba({{ rgb_1 }}, {{ rgb_2 }}, {{ rgb_3 }},1) 100%);
            /* W3C */
            filter: progid: DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#ffffff', GradientType=1);
            /* IE6-9 */
        "></div>
        <img alt="{{ title }}" src="{{ img_src }}">
    </a>
    <div class="badge_like"><i class="fa fa-thumbs-up"></i> {{ likes }}</div>

    <div class="item-inner">
        <a class="featured-user" href="{{ user_url }}">
            <img alt="<?= __('Profile picture of') ?> {{ user_name }}" src="{{ user_src }}">
        </a>

        <a href="{{ url }}">
            <h3>                                    
                {{ title }}
            </h3>
        </a>
    </div>
</div>