<div class="sidebar">
    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="#" class="simple-text logo-mini">{{ _('PMW') }}</a>
            <a href="#" class="simple-text logo-normal">{{ _('Notícias') }}</a>
        </div>
        <ul class="nav">
            <li @if ($pageSlug == 'dashboard') class="active " @endif>
                <a href="{{ route('noticias.index') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ _('Notícias') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'profile') class="active " @endif>
                <a href="{{ route('profile.edit')  }}">
                    <i class="tim-icons icon-single-02"></i>
                    <p>{{ _('Perfil do Usuário') }}</p>
                </a>
            </li>
        </ul>
    </div>
</div>
