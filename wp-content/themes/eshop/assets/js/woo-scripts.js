document.addEventListener('DOMContentLoaded', () => {

    // Обработчик клика на кнопки + / -
    document.addEventListener('click', e => {
        const btn = e.target.closest('.qty-btn');
        if (!btn) return;

        e.preventDefault();

        const wrap = btn.closest('.pro-qty');
        if (!wrap) return;

        const input = wrap.querySelector('input');
        if (!input) return;

        // Берём текущее значение как число
        let value = parseFloat(input.value) || 0;
        const step = parseFloat(input.dataset.step) || 1;
        const min = parseFloat(input.dataset.min) || 1;
        const max = input.dataset.max ? parseFloat(input.dataset.max) : Infinity;

        // Увеличиваем или уменьшаем
        if (btn.classList.contains('inc')) value += step;
        if (btn.classList.contains('dec')) value -= step;

        // Ограничиваем диапазон
        value = Math.max(min, value);
        value = Math.min(max, value);

        // Устанавливаем именно property, чтобы визуально обновилось
        input.value = value;

        // Триггерим события для WooCommerce и других слушателей
        input.dispatchEvent(new Event('input', { bubbles: true }));
        input.dispatchEvent(new Event('change', { bubbles: true }));

        // Обновление корзины на странице cart
        if (document.body.classList.contains('woocommerce-cart')) {
            clearTimeout(cartUpdateTimer);
            cartUpdateTimer = setTimeout(() => {
                const updateBtn = document.querySelector('button[name="update_cart"]');
                if (updateBtn) {
                    updateBtn.disabled = false;
                    updateBtn.click();
                }
            }, 400);
        }
    });

    /* ===============================
      WOOCOMMERCE MESSAGE AUTO-HIDE
   =============================== */

    document.addEventListener('click', e => {
        document.querySelectorAll('.woocommerce-message').forEach(msg => {
            if (!msg.contains(e.target)) {
                msg.classList.add('fade-out');
                setTimeout(() => msg.remove(), 700);
            }
        });
    });
});

document.body.addEventListener('added_to_cart', function (e) {
    const button = e.detail?.button;

    if (!button) return;

    button.textContent = 'В корзине';
    button.classList.add('in-cart');
    button.disabled = true;
});