// Делегируем все клики на документе
document.addEventListener('click', function (e) {
    // Добавить товар/работу/услугу
    let btn = e.target.closest('.add-to-cart');
    if (btn) {
        const { type, id } = btn.dataset;
        fetch(`/cart/add?type=${type}&id=${id}&quantity=1`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(r => r.json())
        .then(data => {
            alert(data.success ? 'Добавлено в корзину' : (data.message || 'Ошибка при добавлении'));
            updateCartCount();
        });
        return;
    }

    // Удалить один элемент
    btn = e.target.closest('.remove-cart-item');
    if (btn) {
        const itemId = btn.dataset.id;
        fetch(`/cart/remove?id=${itemId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(() => {
            reloadCart();
            updateCartCount();
        });
        return;
    }

    // Очистить корзину
    btn = e.target.closest('#clear-cart-button');
    if (btn) {
        fetch('/cart/clear', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(() => {
            reloadCart();
            updateCartCount();
        });
        return;
    }
});

// Делегируем изменение количества
document.addEventListener('change', function (e) {
    if (e.target.matches('.cart-quantity-input')) {
        const itemId = e.target.dataset.id;
        const quantity = e.target.value;
        fetch(`/cart/update-quantity?id=${itemId}&quantity=${quantity}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(() => {
            recalculateCart();
            updateCartCount();
        });
    }
});

// Пересчитать сумму в открытой корзине
function recalculateCart() {
    let total = 0;
    document.querySelectorAll('.item-price').forEach(span => {
        const unit = parseFloat(span.dataset.unitPrice);
        const staticQty = parseInt(span.dataset.quantity, 10) || 1;
        const id = span.dataset.id;
        // продукт: есть input — берём из него, иначе — staticQty
        const inp = document.querySelector(`.cart-quantity-input[data-id="${id}"]`);
        const qty = inp ? (parseInt(inp.value, 10) || 1) : staticQty;
        const line = unit * qty;
        span.textContent = line;
        total += line;
    });
    const totalEl = document.getElementById('cart-total');
    if (totalEl) totalEl.textContent = total;
}


// Обновить цифру возле корзины
function updateCartCount() {
    fetch('/cart/count', {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(json => {
        const cnt = document.getElementById('cart-count');
        if (cnt) cnt.textContent = json.count;
    });
}

// Перезагрузить содержимое модалки корзины
function reloadCart() {
    const url = '/cart/view';
    document.getElementById('cart-modal-content') &&
        $('#cart-modal-content').load(url, function() {
            recalculateCart();
        });
}

// При первой загрузке страницы обновим счётчик
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
});
