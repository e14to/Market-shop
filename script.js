// ─── Navigation ───────────────────────────────────────────────
const navButtons = document.querySelectorAll('.nav-but');
const pages = document.querySelectorAll('.content');

function navigateTo(target) {
    pages.forEach(p => p.classList.remove('active'));
    navButtons.forEach(b => b.classList.remove('active'));

    const page = document.getElementById(target);
    if (page) page.classList.add('active');

    const btn = document.querySelector(`.nav-but[data-target="${target}"]`);
    if (btn) btn.classList.add('active');
}

navButtons.forEach(button => {
    button.addEventListener('click', () => {
        navigateTo(button.getAttribute('data-target'));
    });
});

// ─── Home button ──────────────────────────────────────────────
document.getElementById('homeShopBtn').addEventListener('click', () => navigateTo('shop'));

// ─── Dark Mode ────────────────────────────────────────────────
const darkToggle = document.getElementById('darkToggle');
let darkMode = localStorage.getItem('darkMode') === 'true';

function applyDark() {
    document.body.classList.toggle('dark', darkMode);
    darkToggle.textContent = darkMode ? '☀️' : '🌙';
}

function toggleDark() {
    darkMode = !darkMode;
    localStorage.setItem('darkMode', darkMode);
    applyDark();
}

applyDark();

// ─── Toast Notification ───────────────────────────────────────
function showToast(message) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2500);
}

// ─── Basket State ─────────────────────────────────────────────
let basket = [];

function addToBasket(name, price, btn) {
    const existing = basket.find(i => i.name === name);
    if (existing) {
        existing.qty++;
    } else {
        basket.push({ name, price, qty: 1 });
    }

    // Button bounce animation
    if (btn) {
        btn.classList.add('added');
        btn.textContent = '✓ Added!';
        setTimeout(() => {
            btn.classList.remove('added');
            btn.textContent = '+ Add to Basket';
        }, 1000);
    }

    showToast(`🛒 ${name} added to basket!`);
    updateBasketUI();
    updateWidget();
}

function removeFromBasket(name) {
    basket = basket.filter(i => i.name !== name);
    updateBasketUI();
    updateWidget();
}

function changeQty(name, delta) {
    const item = basket.find(i => i.name === name);
    if (!item) return;
    item.qty += delta;
    if (item.qty <= 0) removeFromBasket(name);
    else {
        updateBasketUI();
        updateWidget();
    }
}

function clearBasket() {
    basket = [];
    updateBasketUI();
    updateWidget();
    showToast('🗑 Basket cleared');
}

function updateWidget() {
    const count = basket.reduce((s, i) => s + i.qty, 0);
    const widget = document.getElementById('widgetCount');
    if (widget) {
        widget.textContent = count;
        widget.classList.toggle('hidden', count === 0);
    }
}

function updateBasketUI() {
    const list = document.getElementById('basketList');
    const empty = document.getElementById('emptyBasket');
    const summary = document.getElementById('basketSummary');

    list.innerHTML = '';

    if (basket.length === 0) {
        empty.style.display = 'block';
        summary.style.display = 'none';
        return;
    }

    empty.style.display = 'none';
    summary.style.display = 'block';

    let total = 0;

    basket.forEach(item => {
        const itemTotal = item.price * item.qty;
        total += itemTotal;

        const div = document.createElement('div');
        div.className = 'basket-item';
        div.innerHTML = `
            <div class="basket-item-info">
                <span class="basket-item-name">${item.name}</span>
                <span class="basket-item-unit">₾${item.price.toFixed(2)} each</span>
            </div>
            <div class="basket-item-controls">
                <button class="qty-btn" onclick="changeQty('${item.name}', -1)">−</button>
                <span class="qty-num">${item.qty}</span>
                <button class="qty-btn" onclick="changeQty('${item.name}', 1)">+</button>
            </div>
            <div class="basket-item-right">
                <strong>₾${itemTotal.toFixed(2)}</strong>
                <button class="remove-btn" onclick="removeFromBasket('${item.name}')">✕</button>
            </div>
        `;
        list.appendChild(div);
    });

    document.getElementById('subtotalPrice').textContent = `₾${total.toFixed(2)}`;
    document.getElementById('totalPrice').textContent = `₾${total.toFixed(2)}`;
    document.getElementById('payAmount').textContent = `₾${total.toFixed(2)}`;
}

// ─── Filter ───────────────────────────────────────────────────
function filterProducts(category, clickedBtn) {
    const products = document.querySelectorAll('.product-card');

    // Update active filter button
    document.querySelectorAll('.filter-buttons button').forEach(b => b.classList.remove('active'));
    if (clickedBtn) clickedBtn.classList.add('active');

    let visible = 0;
    products.forEach(card => {
        const match = category === 'all' || card.dataset.category === category;
        card.style.display = match ? 'block' : 'none';
        if (match) visible++;
    });

    document.getElementById('noResults').classList.toggle('hidden', visible > 0);
}

// ─── Search ───────────────────────────────────────────────────
document.getElementById('searchBox').addEventListener('input', function () {
    const term = this.value.toLowerCase();
    const products = document.querySelectorAll('.product-card');
    let visible = 0;

    products.forEach(card => {
        const name = card.dataset.name.toLowerCase();
        const match = name.includes(term);
        card.style.display = match ? 'block' : 'none';
        if (match) visible++;
    });

    document.getElementById('noResults').classList.toggle('hidden', visible > 0);

    // Reset filter buttons
    document.querySelectorAll('.filter-buttons button').forEach(b => b.classList.remove('active'));
    document.querySelector('.filter-buttons button').classList.add('active');
});

// ─── Payment Modal ────────────────────────────────────────────
const paymentModal = document.getElementById('paymentModal');

document.getElementById('openPayment').addEventListener('click', () => {
    if (basket.length === 0) {
        showToast('Your basket is empty!');
        return;
    }
    document.getElementById('paymentForm').classList.remove('hidden');
    document.getElementById('paymentSuccess').classList.add('hidden');
    paymentModal.style.display = 'flex';
});

document.getElementById('closeModal').addEventListener('click', () => {
    paymentModal.style.display = 'none';
});

window.addEventListener('click', (e) => {
    if (e.target === paymentModal) paymentModal.style.display = 'none';
});

// Card number formatting
document.getElementById('cardNumber').addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '').substring(0, 16);
    this.value = v.replace(/(.{4})/g, '$1 ').trim();
});

// Expiry formatting
document.getElementById('cardExpiry').addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '').substring(0, 4);
    if (v.length >= 3) v = v.substring(0, 2) + '/' + v.substring(2);
    this.value = v;
});

// CVV: digits only
document.getElementById('cardCvv').addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '').substring(0, 3);
});

function processPayment() {
    const number = document.getElementById('cardNumber').value.replace(/\s/g, '');
    const name = document.getElementById('cardName').value.trim();
    const expiry = document.getElementById('cardExpiry').value.trim();
    const cvv = document.getElementById('cardCvv').value.trim();

    let valid = true;

    const setErr = (id, msg) => { document.getElementById(id).textContent = msg; };
    setErr('cardErr', ''); setErr('nameErr', ''); setErr('expiryErr', ''); setErr('cvvErr', '');

    if (number.length < 16) { setErr('cardErr', 'Enter a valid 16-digit card number'); valid = false; }
    if (name.length < 2) { setErr('nameErr', 'Enter cardholder name'); valid = false; }
    if (!/^\d{2}\/\d{2}$/.test(expiry)) { setErr('expiryErr', 'Format: MM/YY'); valid = false; }
    if (cvv.length < 3) { setErr('cvvErr', 'Enter 3-digit CVV'); valid = false; }

    if (!valid) return;

    // Save order to history
    const order = {
        id: '#' + Math.random().toString(36).substring(2, 7).toUpperCase(),
        date: new Date().toLocaleDateString(),
        items: [...basket],
        total: basket.reduce((s, i) => s + i.price * i.qty, 0).toFixed(2)
    };
    orderHistory.unshift(order);
    saveOrders();

    document.getElementById('paymentForm').classList.add('hidden');
    document.getElementById('paymentSuccess').classList.remove('hidden');
}

function afterPayment() {
    basket = [];
    updateBasketUI();
    updateWidget();
    paymentModal.style.display = 'none';
    navigateTo('shop');
    renderOrders();
}

// ─── Order History ────────────────────────────────────────────
let orderHistory = JSON.parse(localStorage.getItem('orderHistory') || '[]');

function saveOrders() {
    localStorage.setItem('orderHistory', JSON.stringify(orderHistory));
    renderOrders();
}

function renderOrders() {
    const list = document.getElementById('orderList');
    const empty = document.getElementById('emptyOrders');

    if (orderHistory.length === 0) {
        list.innerHTML = '';
        empty.style.display = 'block';
        return;
    }

    empty.style.display = 'none';
    list.innerHTML = orderHistory.map(order => `
        <div class="order-card">
            <div class="order-header">
                <strong>${order.id}</strong>
                <span>${order.date}</span>
            </div>
            <div class="order-items">
                ${order.items.map(i => `<span>${i.name} × ${i.qty}</span>`).join('')}
            </div>
            <div class="order-total">Total: <strong>₾${order.total}</strong></div>
        </div>
    `).join('');
}

renderOrders();

const products = [
    { id: 1, name: 'Apple', price: 2.50, img: 'apple.jpg', cat: 'fruit' },
    { id: 2, name: 'Banana', price: 1.80, img: 'banana.jpg', cat: 'fruit' }
];

// და ეს ფუნქცია აუცილებლად ბოლოში:
document.addEventListener('DOMContentLoaded', () => {
    renderProducts();
    updateWidget();
});