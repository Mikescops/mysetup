
<div class="setup-item">
    <a class="setup-pic" href="{{ url }}">
        <div class="tile-gradient"></div>
        <img alt="{{ title }}" src="{{ img_src }}">
    </a>
    <div class="red_like"><i class="fa fa-heart"></i> {{ likes }}</div>

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