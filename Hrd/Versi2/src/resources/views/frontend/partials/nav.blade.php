<header id="header" class="header sticky-top">
    <div class="topbar d-flex align-items-center">
        <div class="d-flex justify-content-center justify-content-md-between container">
            <div class="contact-info d-flex align-items-center">
                <i class="bi bi-envelope d-flex align-items-center">
                    <a href="mailto:{{ $setting->email }}">{{ $setting->email }}</a>
                </i>
                <i class="bi bi-phone ..."><span>{{ $setting->phone }}</span></i>
            </div>
            <div class="social-links d-none d-md-flex align-items-center">
                <a href="#" class="twitter"><i class="bi bi-twitter-x"></i></a>
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
            </div>
        </div>
    </div>
    <!-- End Top Bar -->

    <div class="branding d-flex align-items-center">
        <div class="position-relative d-flex align-items-center justify-content-between container">
            <a href="index.html" class="logo d-flex align-items-center me-auto">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <!-- <img src="assets/img/logo.png" alt=""> -->
                <img src="{{ asset('storage/' . $setting->logo) }}" alt="{{ $setting->company_name }}" height="40" />
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li>
                        <a href="#hero" class="active">
                            Home
                            <br />
                        </a>
                    </li>
                    <li><a href="#about">Tentang Kami</a></li>
                    <li><a href="#services">Produk</a></li>
                    <li><a href="#departments">Departments</a></li>
                    <li><a href="#doctors">Doctors</a></li>
                    <li class="dropdown">
                        <a href="#">
                            <span>Dropdown</span>
                            <i class="bi bi-chevron-down toggle-dropdown"></i>
                        </a>
                        <ul>
                            <li><a href="#">Dropdown 1</a></li>
                            <li class="dropdown">
                                <a href="#">
                                    <span>Deep Dropdown</span>
                                    <i class="bi bi-chevron-down toggle-dropdown"></i>
                                </a>
                                <ul>
                                    <li><a href="#">Deep Dropdown 1</a></li>
                                    <li><a href="#">Deep Dropdown 2</a></li>
                                    <li><a href="#">Deep Dropdown 3</a></li>
                                    <li><a href="#">Deep Dropdown 4</a></li>
                                    <li><a href="#">Deep Dropdown 5</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Dropdown 2</a></li>
                            <li><a href="#">Dropdown 3</a></li>
                            <li><a href="#">Dropdown 4</a></li>
                        </ul>
                    </li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <a class="cta-btn d-none d-sm-block" href="#appointment">Login</a>
        </div>
    </div>
</header>
