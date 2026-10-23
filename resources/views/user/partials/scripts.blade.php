<script>
    // ============================================
    // DROPDOWN MENU (garis 3) — support multiple dropdowns
    // ============================================
    document.querySelectorAll('.dropdown').forEach(function(dropdown) {
        var dropbtn = dropdown.querySelector('.dropbtn');
        if (!dropbtn) return;

        dropbtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Tutup dropdown lain dulu (biar cuma 1 kebuka)
            document.querySelectorAll('.dropdown.show').forEach(function(d) {
                if (d !== dropdown) d.classList.remove('show');
            });

            dropdown.classList.toggle('show');
        });
    });

    // Klik di luar → tutup semua
    window.addEventListener('click', function(event) {
        if (!event.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown').forEach(function(d) {
                d.classList.remove('show');
            });
        }
    });

    // ============================================
    // CART — localStorage
    // ============================================
    window.cart = JSON.parse(localStorage.getItem('spaCart') || '[]');

    function saveCart() {
        localStorage.setItem('spaCart', JSON.stringify(window.cart));
    }

    function updateCartUI() {
        const badge = document.getElementById('cartBadge');
        const cartItemsList = document.getElementById('cartItemsList');
        const cartTotal = document.getElementById('cartTotal');

        if (!badge || !cartItemsList || !cartTotal) return;

        // Total qty — dari qty (jumlah produk)
        const totalQty = window.cart.reduce((sum, item) => sum + (item.qty || 0), 0);

        badge.textContent = totalQty;
        badge.setAttribute('data-count', totalQty);

        if (window.cart.length === 0) {
            cartItemsList.innerHTML = '<p class="cart-empty-msg">Your cart is empty.</p>';
            cartTotal.innerHTML = 'Total: <span>Rp 0</span>';
            return;
        }

        let total = 0;
        cartItemsList.innerHTML = '';
        window.cart.forEach((item, index) => {
            const itemTotal = item.price * item.qty;
            total += itemTotal;
            cartItemsList.innerHTML += `
                <div class="cart-item">
                    <img src="${item.image}" alt="${item.name}">
                    <div class="cart-item-info">
                        <h5>${item.name}</h5>
                        <p>${item.duration} • ${item.qty} ${item.unit}</p>
                        <span class="price">Rp ${itemTotal.toLocaleString('id-ID')}</span>
                    </div>
                    <div class="cart-qty-control">
                        <button onclick="decreaseQty(${index})">-</button>
                        <span>${item.qty}</span>
                        <button onclick="increaseQty(${index})">+</button>
                    </div>
                    <button class="remove-item" onclick="removeItem(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
        });
        cartTotal.innerHTML = `Total: <span>Rp ${total.toLocaleString('id-ID')}</span>`;
    }

    // ============================================
    // TOGGLE & CLOSE CART
    // ============================================
    function toggleCartDropdown(event) {
        if (event) event.stopPropagation();
        const dd = document.getElementById('cartDropdown');
        if (dd) dd.classList.toggle('show');
    }

    function closeCartDropdown() {
        const dd = document.getElementById('cartDropdown');
        if (dd) dd.classList.remove('show');
    }

    function openCartDropdown() {
        const dd = document.getElementById('cartDropdown');
        if (dd) dd.classList.add('show');
    }

    // ============================================
    // QTY CONTROL
    // ============================================
    function increaseQty(index) {
        window.cart[index].qty++;
        saveCart();
        updateCartUI();
    }

    function decreaseQty(index) {
        if (window.cart[index].qty > 1) {
            window.cart[index].qty--;
        } else {
            window.cart.splice(index, 1);
        }
        saveCart();
        updateCartUI();
    }

    function removeItem(index) {
        window.cart.splice(index, 1);
        saveCart();
        updateCartUI();
    }

    // ============================================
    // ADD TO CART
    // ============================================
    function addToCart(item) {
        const existing = window.cart.find(i =>
            i.name === item.name && i.duration === item.duration
        );

        if (existing) {
            existing.qty += item.qty;
        } else {
            window.cart.push(item);
        }

        saveCart();
        updateCartUI();

        // Auto-open cart dropdown — STAY OPEN
        openCartDropdown();
    }

    // ============================================
    // CLOSE CART — klik di luar (TAPI JANGAN nutup pas klik di dalam)
    // ============================================
    document.addEventListener('click', function(event) {
        const clickedInsideCart = event.target.closest('.cart-dropdown');
        const clickedOnButton = event.target.closest('.header-cart');
        if (!clickedInsideCart && !clickedOnButton) {
            closeCartDropdown();
        }
    });

    // Init
    document.addEventListener('DOMContentLoaded', updateCartUI);
    updateCartUI();
    // ============================================
    // FOOTER SHARE DROPDOWN
    // ============================================
    (function() {
        var btn = document.getElementById('footerShareBtn');
        var dd  = document.getElementById('footerShareDropdown');
        if (!btn || !dd) return;

        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var isOpen = dd.classList.contains('show');
            dd.classList.toggle('show');
            btn.setAttribute('aria-expanded', !isOpen ? 'true' : 'false');
        });

        // Klik di luar → tutup
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.share-wrap')) {
                dd.classList.remove('show');
                btn.setAttribute('aria-expanded', 'false');
            }
        });

        // Escape → tutup
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                dd.classList.remove('show');
                btn.setAttribute('aria-expanded', 'false');
            }
        });
    })();
</script>