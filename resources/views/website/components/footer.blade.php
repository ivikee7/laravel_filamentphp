@php
    $mainMenu = \App\Models\WebsiteMenu::mainNavigationMenu();

    $mapMenuItem = function (\App\Models\WebsiteMenuItem $item) use (&$mapMenuItem): array {
        $url = '#';
        if ($item->page?->slug) {
            $url = route('website.page', $item->page->slug);
        } elseif (filled($item->url)) {
            $url = (string) $item->url;
        }

        return [
            'label' => (string) $item->label,
            'url' => $url,
            'target' => $item->target ?: '_self',
            'children' => $item->childrenRecursive->map(fn (\App\Models\WebsiteMenuItem $child): array => $mapMenuItem($child))->all(),
        ];
    };

    $navigation = $mainMenu?->rootItems()?->get()?->map(fn (\App\Models\WebsiteMenuItem $item): array => $mapMenuItem($item))->all() ?? [];
@endphp

<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            <!-- Left Column: Brand & Description -->
            <div class="footer-column footer-brand-col">
                <h3 class="footer-brand-title">Shri Ram Centenary School, Patna</h3>
                <p class="footer-description">
                    Legacy of 95 years of excellence in the domain of education reflected in the world class premier institutions of higher learning by Shri Ram family - SRCC & LSR. Ranked as one of the the Best CBSE Schools in Bihar by the EW Magazine, SRCS focuses on the holistic growth of the child. We cater to learning upto senior secondary level and provide career counselling as well.
                </p>
                <div class="footer-social">
                    <a href="https://www.facebook.com/patna.srcs" class="social-link" aria-label="Facebook" target="_blank" >f</a>
                    <a href="https://www.linkedin.com/company/srcspatna" class="social-link" aria-label="LinkedIn" target="_blank" >in</a>
                    <a href="https://instagram.com/srcs_patna" class="social-link" aria-label="Instagram" target="_blank">ig</a>
                    <a href="https://www.youtube.com/@srcs_patna" class="social-link" aria-label="YouTube" target="_self">yt</a>
                </div>
            </div>

            <!-- Middle Column: Reach Out -->
            <div class="footer-column">
                <h3 class="footer-column-title">Reach Out</h3>
                <div class="footer-contact-list">
                    <a href="tel:+918873002603" class="footer-contact-item">
                        <span class="contact-icon">ph</span>
                        <span>+918873002603</span>
                    </a>
                    <a href="tel:+918873002602" class="footer-contact-item">
                        <span class="contact-icon">ph</span>
                        <span>+918873002602</span>
                    </a>
                    <a href="mailto:info@srcspatna.com" class="footer-contact-item">
                        <span class="contact-icon">@</span>
                        <span>info@srcspatna.com</span>
                    </a>
                    <a href="//wa.me/918873002603" class="footer-contact-item">
                        <span class="contact-icon">wa</span>
                        <span>+918873002603</span>
                    </a>
                    <a href="//share.google/wH34zPADRelLaYBgy" class="footer-contact-item">
                        <span class="contact-icon">ad</span>
                        <span>Bhogipur, Near Shahpur, Jaganpura, Patna, Bihar, 804453</span>
                    </a>
                </div>
            </div>

            <!-- Right Column: Navigate -->
            <div class="footer-column">
                <h3 class="footer-column-title">Navigate</h3>
                <nav class="footer-nav">
                    <a href="{{ route('filament.admin.auth.login') }}" class="footer-link">Admin Login</a>
                    <a href="{{ route('filament.student.auth.login') }}" class="footer-link">Student Login</a>
                </nav>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="footer-copyright">
                <span>&copy; SHRI RAM CENTENNIAL SCHOOL {{ date('Y') }}. All Rights Reserved.</span>
            </div>
            <button class="scroll-to-top" id="scroll-to-top" aria-label="Scroll to top">↑</button>
        </div>
    </div>
</footer>

<style>
    .site-footer {
        background: linear-gradient(135deg, #0f172a 0%, #1a2d4d 100%);
        color: #cbd5e1;
        padding-top: 3rem;
        margin-top: 0;
    }

    .footer-content {
        display: grid;
        grid-template-columns: 1.3fr 1fr 1fr;
        gap: 3rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid rgba(203, 213, 225, 0.15);
        margin-bottom: 2rem;
    }

    .footer-column {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .footer-brand-col {
        gap: 1.5rem;
    }

    .footer-brand-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
        font-family: 'Merriweather', serif;
    }

    .footer-column-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
        text-transform: capitalize;
    }

    .footer-description {
        font-size: 0.9rem;
        line-height: 1.6;
        color: #cbd5e1;
        margin: 0;
    }

    .footer-social {
        display: flex;
        gap: 0.8rem;
    }

    .social-link {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(203, 213, 225, 0.1);
        border-radius: 50%;
        color: #cbd5e1;
        text-decoration: none;
        font-weight: bold;
        font-size: 0.9rem;
        transition: 0.3s ease;
        border: 1px solid rgba(203, 213, 225, 0.2);
    }

    .social-link:hover {
        background: #f97316;
        color: #0f172a;
        border-color: #f97316;
    }

    .footer-contact-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .footer-contact-item {
        display: flex;
        align-items: flex-start;
        gap: 0.8rem;
        color: #cbd5e1;
        text-decoration: none;
        transition: 0.2s ease;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .footer-contact-item:hover {
        color: #f97316;
    }

    .contact-icon {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        width: 22px;
        height: 22px;
        border-radius: 999px;
        border: 1px solid rgba(203, 213, 225, 0.4);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 0.1rem;
    }

    .footer-nav {
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
    }

    .footer-link {
        color: #cbd5e1;
        text-decoration: none;
        font-size: 0.95rem;
        transition: 0.2s ease;
        display: inline-block;
    }

    .footer-link:hover {
        color: #f97316;
        padding-left: 0.3rem;
    }

    .footer-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 0;
        gap: 1rem;
    }

    .footer-copyright {
        font-size: 0.85rem;
        color: #94a3b8;
    }

    .scroll-to-top {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #f97316;
        color: #0f172a;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s ease;
        flex-shrink: 0;
        font-weight: bold;
    }

    .scroll-to-top:hover {
        background: #fb923c;
        transform: translateY(-2px);
    }

    .scroll-to-top:active {
        transform: translateY(0);
    }

    @media (max-width: 1000px) {
        .footer-content {
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .footer-brand-col {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 768px) {
        .footer-content {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .footer-brand-col {
            grid-column: auto;
        }

        .footer-bottom {
            flex-direction: column-reverse;
            gap: 1.5rem;
        }

        .footer-copyright {
            text-align: center;
            width: 100%;
        }

        .scroll-to-top {
            align-self: center;
        }

        .footer-brand-title {
            font-size: 1.2rem;
        }

        .footer-column-title {
            font-size: 1rem;
        }
    }

    @media (max-width: 480px) {
        .footer-content {
            gap: 1.2rem;
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .footer-description {
            font-size: 0.85rem;
        }

        .footer-contact-item {
            font-size: 0.85rem;
        }

        .footer-link {
            font-size: 0.85rem;
        }

        .scroll-to-top {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scrollToTopBtn = document.getElementById('scroll-to-top');

        if (scrollToTopBtn) {
            // Show/hide button based on scroll position
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    scrollToTopBtn.style.opacity = '1';
                    scrollToTopBtn.style.pointerEvents = 'auto';
                } else {
                    scrollToTopBtn.style.opacity = '0';
                    scrollToTopBtn.style.pointerEvents = 'none';
                }
            });

            scrollToTopBtn.style.opacity = '0';
            scrollToTopBtn.style.pointerEvents = 'none';
            scrollToTopBtn.style.transition = 'opacity 0.3s ease';

            // Scroll to top when clicked
            scrollToTopBtn.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
    });
</script>


