<ul class="sidebar-nav sidebar-list">
    <li v-for="(item, i) in items" :class="classes(item)">
        <a class="d-flex" :target="item.target"
           :href="item.children.length > 0 ? '#'+item.id+'-collapse-element' : item.href"
           role="button"
           :style="'color:'+color(item)"
           :data-bs-toggle="item.children.length > 0 ? 'collapse' : ''"
        >
            <span :class="'icon '+item.icon_class"></span>
            <span class="title">@{{ item.title }}</span>
        </a>
        <div v-if="item.children.length > 0" :id="item.id+'-collapse-element'" :class="'panel-collapse collapse' + (item.active ? ' show' : ' ')">
            <div class="panel-body">
                <admin-menu :items="item.children"></admin-menu>
            </div>
        </div>
    </li>
</ul>
