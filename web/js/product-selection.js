document.addEventListener('DOMContentLoaded', function () {
    const selector = document.getElementById('product-selector');
    const container = document.getElementById('selected-products');

    const productData = JSON.parse(selector.getAttribute('data-products') || '{}');

    selector.addEventListener('change', function () {
        const productId = this.value;
        if (!productId || document.getElementById('product-row-' + productId)) return;
        // ПРОВЕРКА КОНСОЛИ НА ОШИБКИ
        console.log('productData:', productData);
        console.log('productId:', productId);
        console.log('productName:', productData[productId]);

        const productName = productData[productId] || 'Неизвестно';//ОШИБКА ПЕРЕДАЧИ OBJECT
        const html = `
            <div class="form-group" id="product-row-${productId}">
                <label>${productName}</label>
                <input type="hidden" name="Service[product_selection][${productId}][id]" value="${productId}">
                <input type="number" name="Service[product_selection][${productId}][quantity]" value="1" min="1" style="width:100px; display:inline-block;">
                <button type="button" class="btn btn-danger btn-sm remove-product" data-id="${productId}">Удалить</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    });

    container.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-product')) {
            const id = e.target.dataset.id;
            const row = document.getElementById('product-row-' + id);
            if (row) row.remove();
        }
    });
});
