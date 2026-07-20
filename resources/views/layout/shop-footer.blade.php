<footer class="mt-5" style="background:linear-gradient(135deg,#1a1a2e 0%,#16213e 60%,#0f3460 100%);color:#fff;padding:52px 0 0;">

    <div class="container-fluid px-4 px-lg-5">

        {{-- ── Main footer grid ── --}}
        <div class="row g-5 mb-5">

            {{-- Brand / About --}}
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-bag-heart-fill" style="font-size:1.6rem;color:#2874f0;"></i>
                    <span style="font-size:1.4rem;font-weight:800;color:#f9c13a;letter-spacing:-.4px;">Flipkart<span style="color:#2874f0;">.</span></span>
                </div>
                <p style="color:rgba(255,255,255,.55);font-size:.88rem;line-height:1.7;max-width:300px;">
                    Your trusted online shopping destination. Discover millions of products at the best prices from verified sellers across the country.
                </p>
                {{-- Trust badges --}}
                <div class="d-flex gap-3 mt-4 flex-wrap">
                    <div style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:10px 14px;display:flex;align-items:center;gap:8px;">
                        <i class="bi bi-shield-check" style="color:#0d9e5d;font-size:1.1rem;"></i>
                        <div>
                            <div style="font-size:.7rem;font-weight:700;color:#fff;">100% Secure</div>
                            <div style="font-size:.62rem;color:rgba(255,255,255,.5);">SSL Encrypted</div>
                        </div>
                    </div>
                    <div style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:10px 14px;display:flex;align-items:center;gap:8px;">
                        <i class="bi bi-truck" style="color:#f9c13a;font-size:1.1rem;"></i>
                        <div>
                            <div style="font-size:.7rem;font-weight:700;color:#fff;">Fast Delivery</div>
                            <div style="font-size:.62rem;color:rgba(255,255,255,.5);">Pan India</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="col-lg-2 col-md-3 col-6">
                <h6 style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.9px;color:rgba(255,255,255,.45);margin-bottom:18px;">Quick Links</h6>
                <ul class="list-unstyled" style="display:flex;flex-direction:column;gap:10px;">
                    <li>
                        <a href="/" style="color:rgba(255,255,255,.7);text-decoration:none;font-size:.86rem;transition:color .18s;display:flex;align-items:center;gap:6px;"
                           onmouseover="this.style.color='#2874f0'" onmouseout="this.style.color='rgba(255,255,255,.7)'">
                            <i class="bi bi-house" style="font-size:.8rem;"></i>Home
                        </a>
                    </li>
                    <li>
                        <a href="/shop" style="color:rgba(255,255,255,.7);text-decoration:none;font-size:.86rem;transition:color .18s;display:flex;align-items:center;gap:6px;"
                           onmouseover="this.style.color='#2874f0'" onmouseout="this.style.color='rgba(255,255,255,.7)'">
                            <i class="bi bi-shop" style="font-size:.8rem;"></i>Shop
                        </a>
                    </li>
                    <li>
                        <a href="#" style="color:rgba(255,255,255,.7);text-decoration:none;font-size:.86rem;transition:color .18s;display:flex;align-items:center;gap:6px;"
                           onmouseover="this.style.color='#2874f0'" onmouseout="this.style.color='rgba(255,255,255,.7)'">
                            <i class="bi bi-info-circle" style="font-size:.8rem;"></i>About Us
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('customer.login') }}" style="color:rgba(255,255,255,.7);text-decoration:none;font-size:.86rem;transition:color .18s;display:flex;align-items:center;gap:6px;"
                           onmouseover="this.style.color='#2874f0'" onmouseout="this.style.color='rgba(255,255,255,.7)'">
                            <i class="bi bi-person" style="font-size:.8rem;"></i>My Account
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Support --}}
            <div class="col-lg-2 col-md-3 col-6">
                <h6 style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.9px;color:rgba(255,255,255,.45);margin-bottom:18px;">Customer Support</h6>
                <ul class="list-unstyled" style="display:flex;flex-direction:column;gap:10px;">
                    <li>
                        <a href="#" style="color:rgba(255,255,255,.7);text-decoration:none;font-size:.86rem;transition:color .18s;display:flex;align-items:center;gap:6px;"
                           onmouseover="this.style.color='#2874f0'" onmouseout="this.style.color='rgba(255,255,255,.7)'">
                            <i class="bi bi-telephone" style="font-size:.8rem;"></i>Contact Us
                        </a>
                    </li>
                    <li>
                        <a href="#" style="color:rgba(255,255,255,.7);text-decoration:none;font-size:.86rem;transition:color .18s;display:flex;align-items:center;gap:6px;"
                           onmouseover="this.style.color='#2874f0'" onmouseout="this.style.color='rgba(255,255,255,.7)'">
                            <i class="bi bi-shield-check" style="font-size:.8rem;"></i>Privacy Policy
                        </a>
                    </li>
                    <li>
                        <a href="#" style="color:rgba(255,255,255,.7);text-decoration:none;font-size:.86rem;transition:color .18s;display:flex;align-items:center;gap:6px;"
                           onmouseover="this.style.color='#2874f0'" onmouseout="this.style.color='rgba(255,255,255,.7)'">
                            <i class="bi bi-file-text" style="font-size:.8rem;"></i>Terms & Conditions
                        </a>
                    </li>
                    <li>
                        <a href="#" style="color:rgba(255,255,255,.7);text-decoration:none;font-size:.86rem;transition:color .18s;display:flex;align-items:center;gap:6px;"
                           onmouseover="this.style.color='#2874f0'" onmouseout="this.style.color='rgba(255,255,255,.7)'">
                            <i class="bi bi-arrow-counterclockwise" style="font-size:.8rem;"></i>Returns & Refunds
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Connect --}}
            <div class="col-lg-4 col-md-6">
                <h6 style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.9px;color:rgba(255,255,255,.45);margin-bottom:18px;">Connect With Us</h6>
                <div class="d-flex gap-3 mb-4">
                    @foreach([
                        ['bi-facebook',  '#1877f2', 'Facebook'],
                        ['bi-twitter-x', '#000',    'Twitter'],
                        ['bi-instagram', '#e1306c', 'Instagram'],
                        ['bi-youtube',   '#ff0000', 'YouTube'],
                    ] as [$icon, $color, $label])
                        <a href="#"
                           title="{{ $label }}"
                           style="width:42px;height:42px;border-radius:10px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:rgba(255,255,255,.65);text-decoration:none;transition:background .2s,color .2s,transform .2s;"
                           onmouseover="this.style.background='{{ $color }}';this.style.color='#fff';this.style.transform='translateY(-3px)';this.style.borderColor='{{ $color }}'"
                           onmouseout="this.style.background='rgba(255,255,255,.07)';this.style.color='rgba(255,255,255,.65)';this.style.transform='none';this.style.borderColor='rgba(255,255,255,.1)'">
                            <i class="bi {{ $icon }}"></i>
                        </a>
                    @endforeach
                </div>
                {{-- Seller CTA --}}
                <div style="background:rgba(40,116,240,.15);border:1px solid rgba(40,116,240,.25);border-radius:12px;padding:16px;">
                    <div style="font-size:.82rem;font-weight:700;color:#fff;margin-bottom:4px;">
                        <i class="bi bi-shop me-2" style="color:#f9c13a;"></i>Sell on Flipkart
                    </div>
                    <div style="font-size:.76rem;color:rgba(255,255,255,.55);margin-bottom:10px;">
                        Reach millions of customers. Start your seller journey today.
                    </div>
                    <a href="{{ route('seller.register') }}"
                       style="display:inline-flex;align-items:center;gap:6px;background:#2874f0;color:#fff;border-radius:8px;padding:7px 16px;font-size:.78rem;font-weight:700;text-decoration:none;transition:background .18s;"
                       onmouseover="this.style.background='#1558c0'" onmouseout="this.style.background='#2874f0'">
                        <i class="bi bi-arrow-right-circle"></i>Become a Seller
                    </a>
                </div>
            </div>

        </div>

        {{-- ── Bottom bar ── --}}
        <div style="border-top:1px solid rgba(255,255,255,.08);padding:20px 0;">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <p style="margin:0;font-size:.8rem;color:rgba(255,255,255,.4);">
                    © {{ date('Y') }} Flipkart Marketplace. All Rights Reserved.
                </p>
                <div class="d-flex align-items-center gap-3">
                    {{-- Payment icons --}}
                    @foreach(['bi-credit-card','bi-phone','bi-bank','bi-wallet2'] as $payIcon)
                        <i class="bi {{ $payIcon }}" style="font-size:1.1rem;color:rgba(255,255,255,.3);"></i>
                    @endforeach
                </div>
                <p style="margin:0;font-size:.8rem;color:rgba(255,255,255,.4);">
                    Crafted for a premium shopping experience.
                </p>
            </div>
        </div>

    </div>

</footer>