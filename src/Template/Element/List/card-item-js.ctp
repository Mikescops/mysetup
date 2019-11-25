<article class="item-grid">

    <header class="header">

        <a class="user-avatar" href="{{ user_url }}">
            <img alt="{{ user_name }}" width="40" height="40" src="{{ user_src }}">
        </a>

        <div class="setup-meta">
            <span class="setup-title">
                <a href="{{ url }}">
                    {{ title }}
                </a>
            </span>
            <span class="setup-author">{{ user_name }}</span>
        </div>

        <div class="setup-like-box">
            <i class="fa fa-thumbs-up"></i> {{ likes }}
        </div>

    </header>

    <a href="{{ url }}">
        <img class="setup-image" alt="{{ title }}" src="{{ img_src }}">
    </a>

    <div class="card-bg" style="background: rgba({{ rgb_1 }}, {{ rgb_2 }}, {{ rgb_3 }}, 0.4)"></div>

</article>