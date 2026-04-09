document.addEventListener('DOMContentLoaded', () => {

    const filtersWrapper = document.querySelector('.sidebar-area-wrapper._filters');
    const productsWrapper = document.querySelector('.products');

    if (!filtersWrapper || !productsWrapper) return;

    function getFiltersData() {
        const data = {
            action: 'cwc_filter_products',
            current_cat_id: filtersWrapper.dataset.currentCat || 0
        };

        // собираем все активные фильтры
        filtersWrapper.querySelectorAll('.filter-item.active').forEach(el => {
            const taxonomy = el.dataset.taxonomy;
            const slug = decodeURIComponent(el.dataset.slug);

            if (taxonomy && slug) {
                // поддержка нескольких значений (массив)
                if (!data['filter_' + taxonomy]) data['filter_' + taxonomy] = [];
                data['filter_' + taxonomy].push(slug);
            }
        });

        // "В наличии"
        const instock = filtersWrapper.querySelector('.instock-filter.active');
        if (instock) {
            data.instock = 1;
        }

        return data;
    }

    function applyFilters() {
        const data = getFiltersData();

        productsWrapper.classList.add('loading');

        fetch(cwc_ajax_object.ajax_url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams(data)
        })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    productsWrapper.innerHTML = res.data.html;
                } else {
                    console.warn('Ошибка фильтрации', res);
                }
            })
            .finally(() => {
                productsWrapper.classList.remove('loading');
            });
    }

    // клик по фильтру
    filtersWrapper.addEventListener('click', (e) => {
        const filterItem = e.target.closest('.filter-item');
        if (!filterItem) return;

        e.preventDefault();

        const taxonomy = filterItem.dataset.taxonomy;

        // переключение активного состояния
        if (filterItem.classList.contains('active')) {
            filterItem.classList.remove('active');
        } else {
            // для одиночного выбора атрибута снимаем другие
            filtersWrapper.querySelectorAll(`.filter-item.active[data-taxonomy="${taxonomy}"]`)
                .forEach(el => el.classList.remove('active'));

            filterItem.classList.add('active');
        }

        applyFilters();
    });

    // кнопки Apply / Reset
    filtersWrapper.addEventListener('click', (e) => {
        if (e.target.matches('#cwc-apply-filters')) {
            applyFilters();
        }

        if (e.target.matches('#cwc-reset-filters')) {
            filtersWrapper.querySelectorAll('.filter-item.active')
                .forEach(el => el.classList.remove('active'));

            applyFilters();
        }
    });

});