    <style> :root {
        --primary: #f76c6c;
        --primary-dark: #ea5252;
        --maroon: #a3141f;
        --dark: #1a1a1a;
        --gray: #777;
        --bg-cream: #f4f1ef;
        --footer-bg: #e9e4e0;
        --line: #e7e2df;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Montserrat', sans-serif;
        color: #333;
        background: var(--bg-cream);
    }

    a {
        text-decoration: none;
        color: inherit;
        transition: 0.3s;
    }

    ul {
        list-style: none;
    }

    img {
        max-width: 100%;
        display: block;
    }

    .container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 15px;
    }

    html {
        scroll-behavior: smooth;
    }

    /* ========== HEADER & NAVBAR ========== */
    header {
        background: var(--bg-cream);
        border-bottom: 1px solid var(--line);
        padding: 18px 0;
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .header-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        font-size: 22px;
        color: var(--maroon);
    }

    .logo i {
        font-size: 20px;
        color: var(--maroon);
    }

    .nav-center {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 1;
    }

    nav ul {
        display: flex;
        gap: 25px;
        align-items: center;
    }

    nav ul li a {
        font-size: 14px;
        font-weight: 600;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        color: #332f2e;
    }

    nav ul li a:hover,
    nav ul li a.active {
        color: var(--primary-dark);
    }

    nav ul li a.active {
        position: relative;
    }

    nav ul li a.active::after {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        bottom: -8px;
        height: 2px;
        background: var(--primary-dark);
    }

    /* Dropdown Treatments */
    .nav-dropdown {
        position: relative;
        display: inline-block;
    }

    .nav-dropdown-content {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background: #fff;
        min-width: 200px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        padding: 10px 0;
        z-index: 1000;
    }

    .nav-dropdown:hover .nav-dropdown-content {
        display: block;
    }

    .nav-dropdown-content a {
        display: block;
        padding: 10px 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        color: #332f2e;
    }

    .nav-dropdown-content a:hover {
        background: var(--bg-cream);
        color: var(--primary-dark);
    }

    /* Dropdown Garis 3 */
    .header-icons {
        display: flex;
        align-items: center;
        gap: 15px;
        font-size: 16px;
        position: relative;
    }

    .header-icons a {
        color: #332f2e;
    }

    .header-icons a:hover {
        color: var(--primary-dark);
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        top: 35px;
        right: 0;
        background: #fff;
        min-width: 170px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        border-radius: 10px;
        z-index: 1000;
        overflow: hidden;
        padding: 5px 0;
    }

    .dropdown-content a {
        color: #333;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        font-size: 14px;
        font-weight: 500;
        text-align: left;
        transition: 0.2s;
    }

    .dropdown-content a:hover {
        background: var(--bg-cream);
        color: var(--primary-dark);
    }

    .dropdown.show .dropdown-content {
        display: block;
    }

    /* ========== HEADER DETAIL (dengan back button) ========== */
    .header-detail {
        background: var(--bg-cream);
        border-bottom: 1px solid var(--line);
        padding: 18px 0;
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .header-detail .header-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-detail .logo-wrapper {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .header-detail .back-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(163, 20, 31, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: 0.3s;
        text-decoration: none;
    }

    .header-detail .back-btn i {
        font-size: 16px;
        color: var(--maroon);
        transition: 0.3s;
    }

    .header-detail .back-btn:hover {
        background: var(--primary);
    }

    .header-detail .back-btn:hover i {
        color: #fff;
    }

    .header-detail .logo {
        color: var(--maroon);
    }

    .header-detail .logo i {
        color: var(--maroon);
    }

    @media (max-width: 600px) {
        .header-detail .back-btn {
            width: 36px;
            height: 36px;
        }
    }

    /* ========== BOOK NOW BUTTON (header) ========== */
    .btn-book-now-header {
        display: inline-flex;
        align-items: center;
        padding: 10px 20px;
        border-radius: 30px;
        background: var(--primary);
        color: #fff !important;
        font-size: 12.5px;
        font-weight: 700;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        text-decoration: none;
        box-shadow: 0 6px 16px rgba(247, 108, 108, 0.35);
        transition: all 0.3s ease;
        white-space: nowrap;
        font-family: 'Montserrat', sans-serif;
    }

    /* ========== HEADER CART (Treatments & Packages) ========== */
    .header-cart-wrap {
        position: relative;
        display: inline-flex;
        align-items: center;
    }

    /* Icon polos — sama kayak nav & hamburger */
    .header-cart {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #332f2e;
        font-size: 16px;
        cursor: pointer;
        transition: color 0.3s ease;
        line-height: 1;
        padding: 4px;
    }
    .header-cart:hover {
        color: var(--primary-dark);
    }

    /* Badge */
    .header-cart .badge {
        position: absolute;
        top: -6px;
        right: -8px;
        min-width: 18px;
        height: 18px;
        padding: 0 5px;
        border-radius: 9px;
        background: linear-gradient(135deg, #f76c6c 0%, #a3141f 100%);
        color: #fff;
        font-size: 10px;
        font-weight: 800;
        line-height: 18px;
        text-align: center;
        border: 2px solid var(--bg-cream);
        box-sizing: border-box;
        display: flex;
        align-items: center;
        justify-content: center;
        letter-spacing: 0;
    }
    .header-cart .badge:empty,
    .header-cart .badge[data-count="0"] {
        display: none;
    }

    /* ============================================
    CART DROPDOWN — full styling (dari floating-cart lama)
    ============================================ */
    .header-cart-wrap .cart-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 14px);
        right: 0;
        bottom: auto;
        width: 380px;
        max-width: calc(100vw - 40px);
        max-height: 70vh;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        z-index: 1100;
        flex-direction: column;
        overflow: hidden;
    }
    .header-cart-wrap .cart-dropdown.show {
        display: flex;
    }

    /* HEADER */
    .header-cart-wrap .cart-dropdown .cart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 22px 14px;
        border-bottom: 1px solid var(--line, #e7e2df);
        flex: none;
    }
    .header-cart-wrap .cart-dropdown .cart-header h4 {
        font-size: 15px;
        font-weight: 800;
        margin: 0;
        color: var(--ink, #2a2420);
    }
    .header-cart-wrap .cart-dropdown .cart-close {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: none;
        background: transparent;
        color: var(--gray, #777);
        font-size: 14px;
        cursor: pointer;
        transition: .2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .header-cart-wrap .cart-dropdown .cart-close:hover {
        background: rgba(239, 68, 68, .1);
        color: #ef4444;
    }

    /* BODY (scrollable) */
    .header-cart-wrap .cart-dropdown .cart-body {
        flex: 1;
        overflow-y: auto;
        padding: 16px 22px;
        max-height: 45vh;
    }

    /* ITEM */
    .header-cart-wrap .cart-dropdown .cart-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 0;
        border-bottom: 1px dashed var(--line, #e7e2df);
    }
    .header-cart-wrap .cart-dropdown .cart-item:last-child {
        border-bottom: none;
    }
    .header-cart-wrap .cart-dropdown .cart-item img {
        width: 52px;
        height: 52px;
        border-radius: 10px;
        object-fit: cover;
        flex: none;
        background: #f8fafc;
    }
    .header-cart-wrap .cart-dropdown .cart-item-info {
        flex: 1;
        min-width: 0;
    }
    .header-cart-wrap .cart-dropdown .cart-item-info h5 {
        font-size: 13px;
        font-weight: 700;
        margin: 0 0 3px;
        line-height: 1.3;
        color: var(--ink, #2a2420);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .header-cart-wrap .cart-dropdown .cart-item-info p {
        font-size: 11.5px;
        color: var(--gray, #777);
        margin: 0 0 5px;
        line-height: 1.4;
    }
    .header-cart-wrap .cart-dropdown .cart-item-info .price {
        font-size: 12.5px;
        font-weight: 700;
        color: var(--primary-dark, #ea5252);
    }

    /* QTY CONTROL */
    .header-cart-wrap .cart-dropdown .cart-qty-control {
        display: flex;
        align-items: center;
        gap: 0;
        background: var(--bg-cream, #f4f1ef);
        border-radius: 30px;
        padding: 3px;
        flex: none;
    }
    .header-cart-wrap .cart-dropdown .cart-qty-control button {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: none;
        background: transparent;
        color: var(--gray, #777);
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: .2s;
        line-height: 1;
        padding: 0;
    }
    .header-cart-wrap .cart-dropdown .cart-qty-control button:hover {
        background: #fff;
        color: var(--primary-dark, #ea5252);
    }
    .header-cart-wrap .cart-dropdown .cart-qty-control span {
        font-size: 12px;
        font-weight: 700;
        color: var(--ink, #2a2420);
        min-width: 20px;
        text-align: center;
    }

    /* REMOVE */
    .header-cart-wrap .cart-dropdown .remove-item {
        flex: none;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: none;
        background: transparent;
        color: #e74c3c;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: .2s;
    }
    .header-cart-wrap .cart-dropdown .remove-item:hover {
        background: rgba(231, 76, 60, 0.1);
    }

    /* TOTAL */
    .header-cart-wrap .cart-dropdown .cart-total {
        font-size: 15px;
        font-weight: 800;
        padding: 14px 22px;
        border-top: 1px solid var(--line, #e7e2df);
        text-align: right;
        flex: none;
        background: #fbfcfe;
        color: var(--ink, #2a2420);
    }
    .header-cart-wrap .cart-dropdown .cart-total span {
        color: var(--primary-dark, #ea5252);
    }

    /* BUTTON */
    .header-cart-wrap .cart-dropdown .btn-booking-cart {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: var(--primary, #f76c6c);
        color: #fff;
        padding: 14px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        text-decoration: none;
        transition: all 0.3s ease;
        flex: none;
        letter-spacing: .5px;
    }
    .header-cart-wrap .cart-dropdown .btn-booking-cart:hover {
        background: var(--primary-dark, #ea5252);
        color: #fff;
    }

    /* EMPTY STATE */
    .header-cart-wrap .cart-dropdown .cart-empty-msg {
        font-size: 13px;
        color: #888;
        text-align: center;
        padding: 30px 0;
    }

    /* Mobile — cart dropdown full width */
    @media (max-width: 600px) {
        .header-cart-wrap .cart-dropdown {
            right: -10px;
            left: auto;
            width: calc(100vw - 30px);
            max-width: 380px;
        }
    }
    .btn-book-now-header:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(247, 108, 108, 0.5);
        color: #fff !important;
    }

    @media (max-width: 900px) {
        .btn-book-now-header {
            padding: 9px 16px;
            font-size: 11.5px;
        }
    }

    @media (max-width: 600px) {
        .btn-book-now-header {
            padding: 8px 14px;
            font-size: 11px;
            gap: 6px;
        }

        .btn-book-now-header i {
            font-size: 12px;
        }
    }

    /* ========== HERO ========== */
    .hero {
        position: relative;
        height: 600px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: flex-start;
    }

    .hero img.hero-bg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 0;
        transition: opacity 0.5s ease;
    }

    .hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 1;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 550px;
        margin-left: 10%;
    }

    .hero-content .eyebrow {
        font-size: 12px;
        letter-spacing: 2px;
        color: #ccc;
        margin-bottom: 20px;
    }

    .hero-content .eyebrow span {
        color: var(--primary);
    }

    .hero-content h1 {
        font-size: 54px;
        font-weight: 800;
        line-height: 1.15;
        color: #fff;
    }

    .hero-content h1 span {
        color: var(--primary);
    }

    .hero-content h1 br {
        display: block;
    }

    .hero-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        color: #fff;
        font-size: 24px;
        cursor: pointer;
        z-index: 3;
        transition: color 0.3s, transform 0.3s;
    }

    .hero-arrow:hover {
        color: var(--primary);
        transform: translateY(-50%) scale(1.1);
    }

    .hero-arrow.prev {
        left: 30px;
    }

    .hero-arrow.next {
        right: 30px;
    }

    .hero-dots {
        position: absolute;
        bottom: 25px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 8px;
        z-index: 3;
    }

    .hero-dots span {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        border: 1px solid #fff;
        cursor: pointer;
        transition: 0.3s;
    }

    .hero-dots span.active {
        background: #fff;
    }

    /* ========== WELCOME / ABOUT ========== */
    .welcome {
        padding: 90px 0;
        background: #fbf9f8;
    }

    .welcome-inner {
        display: flex;
        align-items: center;
        gap: 60px;
    }

    .welcome-text {
        flex: 1;
    }

    .welcome-text .eyebrow {
        color: #c98f8c;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 15px;
        display: block;
    }

    .welcome-text h2 {
        font-size: 30px;
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: 25px;
    }

    .welcome-text p {
        color: var(--gray);
        font-size: 14px;
        line-height: 1.8;
        margin-bottom: 20px;
        max-width: 450px;
    }

    .btn {
        display: inline-block;
        background: var(--primary);
        color: #fff;
        padding: 12px 28px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        border: none;
        cursor: pointer;
        transition: all 0.4s ease;
    }

    .btn:hover {
        background: var(--primary-dark);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(247, 108, 108, 0.3);
    }

    .welcome-image {
        flex: 1;
    }

    .welcome-image img {
        width: 100%;
        max-width: 480px;
        margin-left: auto;
        border-radius: 0;
        box-shadow: none;
    }

    /* ========== TREATMENTS ========== */
    .treatments {
        padding: 90px 0;
        text-align: center;
    }

    .treatments h2 {
        font-size: 28px;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 25px;
    }

    .treatments>.container>p {
        color: var(--gray);
        font-size: 13px;
        max-width: 600px;
        margin: 0 auto 50px;
        line-height: 1.8;
    }

    .tabs {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 60px;
        flex-wrap: wrap;
    }

    .tabs button {
        padding: 10px 25px;
        border: 1px solid #ddd;
        background: #fff;
        border-radius: 3px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        color: #888;
        transition: all 0.3s ease;
    }

    .tabs button.active,
    .tabs button:hover {
        border-color: var(--primary);
        color: var(--primary);
        box-shadow: 0 5px 10px rgba(247, 108, 108, 0.1);
    }

    .treatment-detail {
        display: flex;
        align-items: flex-start;
        justify-content: center;
        gap: 40px;
        text-align: left;
        margin-bottom: 80px;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }

    .treatment-images {
        display: flex;
        gap: 15px;
        flex: 0 0 auto;
    }

    .treatment-images img {
        width: 250px;
        height: 400px;
        object-fit: cover;
        border-radius: 0;
        cursor: pointer;
        transition: 0.3s;
    }

    .treatment-images img:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .treatment-text {
        flex: 1;
        margin-left: 20px;
        padding-top: 10px;
    }

    .treatment-text h3 {
        font-size: 20px;
        font-weight: 700;
        line-height: 1.4;
        margin-bottom: 20px;
    }

    .treatment-text p {
        color: var(--gray);
        font-size: 13px;
        line-height: 1.8;
        margin-bottom: 20px;
    }

    /* ========== GALLERY ========== */
    .gallery-section {
        padding: 0 0 90px;
        text-align: center;
    }

    .gallery-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 30px;
        max-width: 1180px;
        margin-left: auto;
        margin-right: auto;
        padding: 0 15px;
    }

    .gallery-header h2 {
        font-size: 28px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .see-more-link {
        font-size: 13px;
        font-weight: 700;
        color: var(--primary-dark);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: 0.3s;
    }

    .see-more-link:hover {
        color: var(--maroon);
        text-decoration: underline;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 10px;
        margin-bottom: 30px;
    }

    .gallery-grid img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        cursor: pointer;
        transition: 0.4s;
    }

    .gallery-grid img:hover {
        transform: scale(1.02);
        filter: brightness(0.85);
    }

    /* 3 Foto Bawah */
    .bottom-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        max-width: 1080px;
        margin: 0 auto;
        text-align: center;
    }

    .bottom-card {
        cursor: pointer;
    }

    .bottom-card .img-wrap {
        position: relative;
        overflow: hidden;
        border-radius: 5px;
    }

    .bottom-card img {
        width: 100%;
        height: 260px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .bottom-card:hover img {
        transform: scale(1.05);
    }

    .bottom-card .overlay {
        position: absolute;
        inset: 0;
        background: rgba(247, 108, 108, 0);
        transition: 0.4s;
    }

    .bottom-card:hover .overlay {
        background: rgba(247, 108, 108, 0.2);
    }

    .bottom-card h4 {
        margin-top: 20px;
        font-size: 15px;
        font-weight: 700;
        color: #333;
    }

    .bottom-card .cat-tag {
        display: inline-block;
        background: var(--dark);
        color: #fff;
        font-size: 11px;
        padding: 5px 12px;
        margin-top: 10px;
        margin-bottom: 10px;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .bottom-card p {
        font-size: 12px;
        color: var(--gray);
        line-height: 1.6;
    }

    /* ========== TESTIMONIALS ========== */
    .testimonials {
        background: var(--bg-cream);
        padding: 90px 0;
        text-align: center;
    }

    .testimonials h2 {
        font-size: 28px;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 50px;
    }

    .testimonial-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        max-width: 1180px;
        margin: 0 auto 40px;
        padding: 0 15px;
    }

    .testimonial-card {
        background: #fff;
        padding: 40px 30px;
        border-radius: 14px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        text-align: left;
    }

    .testimonial-card .stars {
        color: #f1c40f;
        font-size: 14px;
        margin-bottom: 15px;
    }

    .testimonial-card p {
        font-size: 14px;
        color: var(--gray);
        line-height: 1.7;
        margin-bottom: 20px;
    }

    .testimonial-card .user {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .testimonial-card .user img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    .testimonial-card .user h4 {
        font-size: 14px;
        font-weight: 700;
    }

    .testimonial-card .user span {
        font-size: 12px;
        color: var(--gray);
    }

    .see-all-testimonials {
        display: inline-block;
        font-size: 14px;
        font-weight: 700;
        color: var(--primary-dark);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid transparent;
        transition: 0.3s;
    }

    .see-all-testimonials:hover {
        border-bottom-color: var(--primary-dark);
        color: var(--maroon);
    }

    /* ========== LIGHTBOX ========== */
    .lightbox {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.92);
        backdrop-filter: blur(8px);
        z-index: 10000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 40px;
    }

    .lightbox.active {
        display: flex;
    }

    .lightbox-content {
        position: relative;
        max-width: 85%;
        max-height: 85%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .lightbox-content img {
        max-width: 100%;
        max-height: 70vh;
        object-fit: contain;
        border-radius: 8px;
    }

    .lightbox-close {
        position: absolute;
        top: -80px;
        right: -80px;
        width: 30px;
        height: 30px;
        background: transparent;
        border: none;
        cursor: pointer;
        color: #fff;
        font-size: 28px;
        font-weight: 300;
        transition: all 0.3s ease;
        line-height: 1;
    }

    .lightbox-close:hover {
        color: var(--primary);
        transform: rotate(90deg);
    }

    .lightbox-prev,
    .lightbox-next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: #fff;
        font-size: 36px;
        font-weight: 300;
        cursor: pointer;
        transition: all 0.3s ease;
        opacity: 0.6;
    }

    .lightbox-prev:hover,
    .lightbox-next:hover {
        opacity: 1;
        color: var(--primary);
    }

    .lightbox-prev {
        left: -120px;
    }

    .lightbox-next {
        right: -120px;
    }

    /* ========== QUESTIONS ========== */
    .questions {
        background: var(--bg-cream);
        padding: 90px 0;
        text-align: center;
    }

    .questions h2 {
        font-size: 28px;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 25px;
    }

    .questions>.container>p {
        color: var(--gray);
        font-size: 13px;
        max-width: 600px;
        margin: 0 auto 50px;
        line-height: 1.8;
    }

    .info-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .info-card {
        background: #fff;
        padding: 35px 20px;
        border-radius: 6px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
        cursor: pointer;
        transition: all 0.4s ease;
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .info-card i {
        font-size: 32px;
        color: var(--primary);
        display: block;
        margin: 0 auto 20px;
        transition: transform 0.3s;
    }

    .info-card:hover i {
        transform: scale(1.1);
    }

    .info-card h4 {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 10px;
        text-transform: uppercase;
    }

    .info-card p {
        font-size: 13px;
        color: var(--gray);
        line-height: 1.8;
    }

    /* ========== NEWSLETTER ========== */
    .newsletter {
        background: var(--primary);
        color: #fff;
        padding: 35px 0;
    }

    .newsletter-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    .newsletter h3 {
        font-size: 15px;
        font-weight: 600;
        text-transform: none;
        letter-spacing: 1px;
    }

    .newsletter-form {
        display: flex;
        background: #fff;
        width: 420px;
        border-radius: 0;
        overflow: hidden;
    }

    .newsletter-form input {
        flex: 1;
        border: none;
        outline: none;
        padding: 15px;
        font-size: 14px;
        width: 100%;
    }

    .newsletter-form button {
        background: var(--primary);
        color: #fff;
        border: none;
        padding: 0 25px;
        cursor: pointer;
        transition: 0.3s;
    }

    .newsletter-form button:hover {
        background: var(--dark);
    }

    .social-icons {
        display: flex;
        gap: 12px;
        font-size: 16px;
    }

    .social-icons a {
        width: 32px;
        height: 32px;
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        transition: 0.3s;
    }

    .social-icons a:hover {
        background: #fff;
        color: var(--primary);
        border-color: #fff;
    }

    /* ========== FOOTER ========== */
    footer {
        background: var(--footer-bg);
        padding: 30px 0;
        border-top: 1px solid var(--line);
    }

    .footer-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
        color: #726c69;
    }

    .footer-logo {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--maroon);
        font-weight: 700;
        font-size: 18px;
        cursor: pointer;
    }

    .footer-logo i {
        color: var(--maroon);
    }

    .footer-logo i:hover {
        color: var(--primary-dark);
    }

    .footer-copyright {
        font-size: 13px;
        color: #726c69;
    }

    /* ========== FOOTER GRID (halaman selain home) ========== */
    footer .footer-grid {
        display: grid;
        grid-template-columns: 1.6fr 1fr 1fr 1fr;
        gap: 32px;
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 32px 44px;
    }

    footer .footer-brand .logo {
        color: var(--maroon);
        margin-bottom: 14px;
        display: flex;
        font-size: 22px;
        font-weight: 700;
        align-items: center;
        gap: 8px;
    }

    footer .footer-brand .logo i {
        color: var(--maroon);
        font-size: 20px;
    }

    footer .footer-brand p {
        color: #726c69;
        font-size: 0.88rem;
        line-height: 1.65;
        margin: 0;
        max-width: 280px;
    }

    footer h4 {
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        margin: 0 0 18px;
        color: var(--dark);
    }

    footer ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    footer li {
        margin-bottom: 13px;
    }

    footer li a {
        color: #726c69;
        font-size: 0.88rem;
        transition: 0.3s;
    }

    footer li a:hover {
        color: var(--primary-dark);
    }

    footer li a.active {
        color: var(--maroon);
        font-weight: 700;
    }

    /* ===== FOOTER CONNECT ICONS — MODERN ===== */
    footer .connect-icons {
        display: flex;
        gap: 10px;
        margin-top: 16px;
        align-items: center;
    }

    /* Base — semua icon (mail & share) */
    footer .connect-icons>a,
    footer .connect-icons .ci-share {
        position: relative;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        color: #475569;
        font-size: 16px;
        cursor: pointer;
        border: 1px solid rgba(148, 163, 184, .25);
        transition: all .28s cubic-bezier(.4, 0, .2, 1);
        text-decoration: none;
        overflow: hidden;
    }

    /* Subtle gradient sheen saat hover */
    footer .connect-icons>a::before,
    footer .connect-icons .ci-share::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        opacity: 0;
        transition: opacity .28s ease;
        z-index: 0;
    }

    footer .connect-icons>a i,
    footer .connect-icons .ci-share i {
        position: relative;
        z-index: 1;
        transition: color .28s ease, transform .3s cubic-bezier(.4, 0, .2, 1);
    }

    footer .connect-icons>a:hover,
    footer .connect-icons .ci-share:hover {
        border-color: transparent;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(247, 108, 108, .22);
    }

    footer .connect-icons>a:hover::before,
    footer .connect-icons .ci-share:hover::before {
        opacity: 1;
    }

    footer .connect-icons>a:hover i,
    footer .connect-icons .ci-share:hover i {
        color: #fff;
        transform: scale(1.08);
    }

    /* Share aktif (dropdown kebuka) — biar tetep colored */
    footer .connect-icons .ci-share[aria-expanded="true"] {
        border-color: transparent;
        background: var(--primary);
        color: #fff;
        box-shadow: 0 8px 20px rgba(247, 108, 108, .22);
    }

    footer .connect-icons .ci-share[aria-expanded="true"]::before {
        opacity: 1;
    }

    /* ===== SHARE DROPDOWN ===== */
    footer .share-wrap {
        position: relative;
    }

    footer .share-dropdown {
        position: absolute;
        bottom: calc(100% + 12px);
        right: 0;
        background: #fff;
        border: 1px solid rgba(148, 163, 184, .2);
        border-radius: 14px;
        box-shadow: 0 12px 36px rgba(15, 23, 42, .12), 0 2px 8px rgba(15, 23, 42, .04);
        padding: 8px;
        min-width: 210px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(8px) scale(.96);
        transform-origin: bottom right;
        transition: opacity .22s ease, transform .22s ease, visibility .22s;
        z-index: 100;
    }

    footer .share-dropdown.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }

    footer .share-dropdown::after {
        content: "";
        position: absolute;
        bottom: -6px;
        right: 18px;
        width: 12px;
        height: 12px;
        background: #fff;
        border-right: 1px solid rgba(148, 163, 184, .2);
        border-bottom: 1px solid rgba(148, 163, 184, .2);
        transform: rotate(45deg);
    }

    footer .share-title {
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .7px;
        color: #94a3b8;
        padding: 6px 10px 8px;
        border-bottom: 1px solid rgba(148, 163, 184, .12);
        margin-bottom: 4px;
    }

    footer .share-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 10px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        color: #475569;
        text-decoration: none;
        transition: background .18s ease, color .18s ease, transform .18s ease;
    }

    footer .share-item:hover {
        background: #f8fafc;
        color: #1e293b;
        transform: translateX(2px);
    }

    footer .share-item .share-icon {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: #fff;
        flex: none;
        transition: transform .22s ease;
    }

    footer .share-item:hover .share-icon {
        transform: scale(1.08);
    }

    footer .share-instagram .share-icon {
        background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
    }

    footer .share-whatsapp .share-icon {
        background: #25D366;
    }

    footer .share-facebook .share-icon {
        background: #1877F2;
    }

    footer .share-twitter .share-icon {
        background: #0f172a;
    }

    footer .share-linkedin .share-icon {
        background: #0A66C2;
    }

    footer .share-item .share-label {
        flex: 1;
    }

    footer .footer-divider {
        border: none;
        border-top: 1px solid rgba(0, 0, 0, 0.08);
        max-width: 1180px;
        margin: 0 auto;
    }

    footer .footer-bottom {
        max-width: 1180px;
        margin: 0 auto;
        padding: 24px 32px 0;
        text-align: center;
        color: #8a8482;
        font-size: 0.85rem;
    }

    /* ========== FOOTER CONNECT ICONS (email + share) ========== */
    footer .connect-icons>a,
    footer .connect-icons .ci-share {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.04);
        color: #4a4544;
        font-size: 15px;
        cursor: pointer;
        border: none;
        transition: all .25s ease;
        text-decoration: none;
    }

    footer .connect-icons>a:hover,
    footer .connect-icons .ci-share:hover {
        background: var(--primary);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(247, 108, 108, 0.3);
    }

    /* ===== SHARE DROPDOWN ===== */
    footer .share-wrap {
        position: relative;
    }

    footer .share-dropdown {
        position: absolute;
        bottom: calc(100% + 10px);
        left: 50%;
        transform: translateX(-50%) translateY(6px);
        background: #fff;
        border: 1px solid rgba(148, 163, 184, .2);
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .12);
        padding: 8px;
        min-width: 200px;
        opacity: 0;
        visibility: hidden;
        transition: opacity .2s ease, transform .2s ease, visibility .2s;
        z-index: 100;
    }

    footer .share-dropdown.show {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
    }

    footer .share-dropdown::after {
        content: "";
        position: absolute;
        bottom: -6px;
        left: 50%;
        transform: translateX(-50%) rotate(45deg);
        width: 12px;
        height: 12px;
        background: #fff;
        border-right: 1px solid rgba(148, 163, 184, .2);
        border-bottom: 1px solid rgba(148, 163, 184, .2);
    }

    footer .share-title {
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .6px;
        color: #94a3b8;
        padding: 6px 10px 8px;
        border-bottom: 1px solid rgba(148, 163, 184, .12);
        margin-bottom: 4px;
    }

    footer .share-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 10px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        color: #475569;
        text-decoration: none;
        transition: background .15s;
    }

    footer .share-item:hover {
        background: #f8fafc;
        color: var(--primary);
    }

    footer .share-item .share-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        color: #fff;
        flex: none;
    }

    footer .share-instagram .share-icon {
        background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
    }

    footer .share-whatsapp .share-icon {
        background: #25D366;
    }

    footer .share-facebook .share-icon {
        background: #1877F2;
    }

    footer .share-twitter .share-icon {
        background: #000;
    }

    footer .share-linkedin .share-icon {
        background: #0A66C2;
    }

    footer .share-item .share-label {
        flex: 1;
    }

    @media (max-width: 900px) {
        footer .footer-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 600px) {
        footer .footer-grid {
            grid-template-columns: 1fr;
            padding: 0 24px 32px;
        }
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 900px) {
        nav {
            display: none;
        }

        .header-inner {
            padding-right: 20px;
        }

        .header-icons {
            margin-left: auto;
            gap: 12px;
        }

        .welcome-inner,
        .treatment-detail {
            flex-direction: column;
        }

        .hero-content h1 {
            font-size: 38px;
        }

        .hero-arrow {
            display: none;
        }

        .gallery-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .bottom-cards,
        .info-cards,
        .testimonial-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .newsletter-inner {
            flex-direction: column;
            text-align: center;
        }

        .lightbox-prev {
            left: 10px;
        }

        .lightbox-next {
            right: 10px;
        }

        .lightbox-close {
            top: -50px;
            right: 0;
        }
    }

    @media (max-width: 600px) {

        .gallery-grid,
        .bottom-cards,
        .info-cards,
        .testimonial-grid {
            grid-template-columns: 1fr;
        }

        .hero-content h1 {
            font-size: 30px;
        }

        .treatment-images {
            flex-direction: column;
        }

        .treatment-images img {
            width: 100%;
        }

        .lightbox-prev {
            font-size: 24px;
        }

        .lightbox-next {
            font-size: 24px;
        }

        .footer-inner {
            flex-direction: column;
            gap: 10px;
            text-align: center;
        }
    }

    </style>