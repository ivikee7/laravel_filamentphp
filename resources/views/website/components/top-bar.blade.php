<div class="top-bar">
    <div class="container top-bar-content">
        <a href="#" class="top-bar-item top-bar-item--gold">Bhogipur, Near Shahpur, Jaganpura, Patna, Bihar, 804453</a>
        <div class="top-bar-right">
            <a href="tel:+918873002603" class="top-bar-item top-bar-item--gold">+918873002603</a>
            <a href="tel:+918873002602" class="top-bar-item top-bar-item--gold">+918873002602</a>
            <a href="mailto:info@srcspatna.com" class="top-bar-item top-bar-item--gold">info@srcspatna.com</a>
            <a href="//wa.me/918873002603" class="top-bar-item top-bar-item--gold">+918873002603</a>
            <div class="social-links">
                <a href="https://www.facebook.com/patna.srcs" class="social-link" aria-label="Facebook" target="_blank" >f</a>
                <a href="https://www.linkedin.com/company/srcspatna" class="social-link" aria-label="LinkedIn" target="_blank" >in</a>
                <a href="https://instagram.com/srcs_patna" class="social-link" aria-label="Instagram" target="_blank">ig</a>
                <a href="https://www.youtube.com/@srcs_patna" class="social-link" aria-label="YouTube" target="_self">yt</a>
            </div>
        </div>
    </div>
</div>

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
        flex-shrink: 0;
    }

    .social-icon:hover {
        border-color: #f59e0b;
        color: #f59e0b;
    }

    @media (max-width: 1200px) {
        .top-bar-content {
            flex-wrap: wrap;
            gap: 10px;
        }

        .top-bar-right {
            flex-wrap: wrap;
            gap: 10px;
        }
    }

    @media (max-width: 920px) {
        .top-bar-item {
            font-size: 11px;
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
    }
</style>
