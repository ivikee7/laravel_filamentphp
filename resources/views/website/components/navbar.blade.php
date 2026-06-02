@php
    // JSON source for nested navbar menus.
    // You can move this JSON to DB/config later without changing the renderer.
    $menuJson = <<<'JSON'
[
  {
    "label": "Home",
    "url": "/",
    "target": "_self",
    "children": []
  },
  {
    "label": "Main Website",
    "url": "//srcspatna.com",
    "target": "_blank",
    "children": []
  },
  {
    "label": "Admin Login",
    "url": "/admin/login",
    "target": "_self",
    "children": []
  },
  {
    "label": "Student Login",
    "url": "/student/login",
    "target": "_self",
    "children": []
  }
]
JSON;

    $navigation = json_decode($menuJson, true);
    if (! is_array($navigation)) {
        $navigation = [];
    }

    $renderMenu = function (array $items, bool $isRoot = false) use (&$renderMenu): string {
        $class = $isRoot ? 'menu-root' : '';
        $html = '<ul class="'.$class.'">';

        foreach ($items as $item) {
            $children = (array) ($item['children'] ?? []);
            $hasChildren = count($children) > 0;
            $label = e((string) ($item['label'] ?? 'Untitled'));
            $url = e((string) ($item['url'] ?? '#'));
            $target = e((string) ($item['target'] ?? '_self'));
            $rel = $target === '_blank' ? 'noopener noreferrer' : '';

            $isHome = ($item['url'] ?? '') === '/';
            $isActive = $isHome ? request()->is('/') : request()->is(ltrim((string) ($item['url'] ?? ''), '/').'*');

            $html .= '<li class="menu-item'.($hasChildren ? ' has-children' : '').'">';
            $html .= '<a href="'.$url.'" target="'.$target.'" rel="'.$rel.'"'.($isActive ? ' class="active"' : '').'>'.$label;
            if ($hasChildren) {
                $html .= ' <span class="dropdown-icon">v</span>';
            }
            $html .= '</a>';

            if ($hasChildren) {
                $html .= $renderMenu($children);
            }

            $html .= '</li>';
        }

        $html .= '</ul>';

        return $html;
    };
@endphp
<header class="site-header">
    <div class="container site-header-inner">
        <a href="{{ route('website.home') }}" class="brand" aria-label="Shri Ram Centennial School">
            <img src="{{ asset('storage/media/logo_50.png') }}" alt="School logo" class="brand-logo" loading="lazy">
            <img src="{{ asset('storage/media/logo_name_150.png') }}" alt="Shri Ram Centennial School"
                 class="brand-wordmark" loading="lazy">
        </a>

        <button id="mobile-nav-toggle" class="mobile-toggle" type="button" aria-expanded="false"
                aria-label="Toggle menu">Menu
        </button>

        <nav id="site-nav" class="site-nav" aria-label="Primary navigation">
            {!! $renderMenu($navigation, true) !!}
        </nav>
    </div>
</header>

<style>
    .top-bar {
        background: #020b24;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding: 8px 0;
    }

    .top-bar-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
    }

    .top-bar-right {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .top-bar-item {
        color: #e2e8f0;
        font-size: 12px;
        font-weight: 500;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: color 0.2s ease;
    }

    .top-bar-item::before {
        content: '';
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: #f59e0b;
        flex-shrink: 0;
    }

    .top-bar-item:hover {
        color: #ffffff;
    }

    .social-links {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-left: 2px;
        padding-left: 10px;
        border-left: 1px solid rgba(255, 255, 255, 0.2);
    }

    .social-icon {
        width: 28px;
        height: 28px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #e2e8f0;
        font-size: 11px;
        font-weight: 700;
        text-transform: lowercase;
    }

    .social-icon:hover {
        border-color: #f59e0b;
        color: #f59e0b;
    }

    .site-header {
        background: #f8fafc;
        border-bottom: 1px solid #d8e0eb;
        position: sticky;
        top: 0;
        z-index: 60;
        width: 100%;
    }

    .site-header-inner {
        min-height: 78px;
        display: flex;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
        justify-content: space-between;
        padding: 0;
    }

    .brand {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }

    .brand-logo {
        width: 52px;
        height: 52px;
        object-fit: contain;
        border-radius: 10px;
        background: #fff;
        border: 1px solid #e2e8f0;
        padding: 2px;
    }

    .brand-wordmark {
        width: 154px;
        height: auto;
        object-fit: contain;
    }

    .site-nav {
        margin-left: auto;
        flex: 0 1 auto;
        display: flex;
        justify-content: flex-end;
    }

    .menu-root {
        list-style: none;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: nowrap;
        justify-content: flex-end;
    }

    .menu-item {
        position: relative;
        flex-shrink: 0;
        white-space: nowrap;
    }

    /* Bridge the small gap between parent link and submenu to keep hover active. */
    .menu-item.has-children::after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        top: 100%;
        height: 12px;
    }

    .menu-item > a {
        color: #475569;
        font-size: 14px;
        font-weight: 600;
        padding: 10px 14px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    /* Override legacy global nav caret styles from layout.css */
    .site-header .menu-item.has-children > a::after {
        content: none !important;
    }

    .menu-item > a:hover {
        background: #e9edf3;
        color: #0f172a;
    }

    .menu-item > a.active {
        background: #020617;
        color: #ffffff;
    }

    .dropdown-icon {
        font-size: 11px;
        line-height: 1;
        opacity: 0.75;
        transform: translateY(-1px);
    }

    .menu-item ul {
        list-style: none;
        position: absolute;
        top: calc(100% + 8px);
        right: auto;
        min-width: 200px;
        background: #ffffff;
        border: 1px solid #dbe3ee;
        border-radius: 12px;
        box-shadow: 0 14px 26px rgba(15, 23, 42, 0.08);
        padding: 6px;
        display: none;
        z-index: 1000;
    }

    .menu-item:hover > ul {
        display: block;
    }

    .menu-item li > a {
        display: block;
        color: #334155;
        font-size: 13px;
        font-weight: 500;
        padding: 10px 11px;
        border-radius: 9px;
    }

    .menu-item li > a:hover {
        background: #f1f5f9;
        color: #0f172a;
    }


    .mobile-toggle {
        display: none;
        border: 1px solid #c9d3e0;
        background: #ffffff;
        color: #334155;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        padding: 8px 12px;
        cursor: pointer;
    }

    @media (max-width: 1200px) {
        .top-bar-content {
            flex-wrap: wrap;
        }

        .top-bar-right {
            flex-wrap: wrap;
        }

        .menu-item > a {
            padding: 9px 10px;
            font-size: 13px;
        }

        .site-nav {
            max-width: 50%;
            overflow: hidden;
        }

        .menu-root {
            flex-wrap: wrap;
        }
    }

    @media (max-width: 920px) {
        .top-bar-item {
            font-size: 11px;
        }

        .brand-wordmark {
            width: 120px;
        }

        .mobile-toggle {
            display: inline-flex;
        }

        .site-nav {
            position: fixed;
            left: 0;
            right: 0;
            top: 118px;
            background: #ffffff;
            border-top: 1px solid #dbe3ee;
            border-bottom: 1px solid #dbe3ee;
            display: none;
            padding: 12px 16px;
            max-height: calc(100vh - 118px);
            overflow-y: auto;
            z-index: 999;
        }

        .site-nav.open {
            display: block;
        }

        .menu-root {
            flex-direction: column;
            align-items: stretch;
            gap: 4px;
        }

        .menu-item > a {
            width: 100%;
            justify-content: space-between;
            border-radius: 10px;
            padding: 12px;
        }

        .menu-item ul {
            position: static;
            display: none;
            margin-top: 4px;
            border-radius: 10px;
            box-shadow: none;
        }

        .menu-item.open > ul {
            display: block;
        }

        .btn-login {
            display: none;
        }
    }

    @media (max-width: 640px) {
        .top-bar {
            padding: 6px 0;
        }

        .top-bar-item {
            font-size: 10px;
        }

        .top-bar-item::before {
            width: 6px;
            height: 6px;
        }

        .social-icon {
            width: 24px;
            height: 24px;
            font-size: 10px;
        }

        .brand-logo {
            width: 42px;
            height: 42px;
        }

        .brand-wordmark {
            width: 102px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const navToggle = document.getElementById('mobile-nav-toggle');
        const siteNav = document.getElementById('site-nav');

        if (!navToggle || !siteNav) {
            return;
        }

        navToggle.addEventListener('click', function () {
            const isOpen = siteNav.classList.toggle('open');
            navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        siteNav.querySelectorAll('.menu-item.has-children > a').forEach(function (link) {
            link.addEventListener('click', function (event) {
                if (window.innerWidth > 920) {
                    return;
                }

                event.preventDefault();
                link.parentElement.classList.toggle('open');
            });
        });
    });
</script>


