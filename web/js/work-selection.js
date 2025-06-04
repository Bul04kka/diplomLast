document.addEventListener('DOMContentLoaded', function () {
    const selector = document.getElementById('work-selector');
    const container = document.getElementById('selected-works');

    const workData = JSON.parse(selector.getAttribute('data-works') || '{}');

    selector.addEventListener('change', function () {
        const workId = this.value;
        if (!workId || document.getElementById('work-row-' + workId)) return;

        const workName = workData[workId] || 'Неизвестно';
        const html = `
            <div class="form-group" id="work-row-${workId}">
                <label>${workName}</label>
                <input type="hidden" name="Service[work_selection][${workId}]" value="${workId}">
                <button type="button" class="btn btn-danger btn-sm remove-work" data-id="${workId}">Удалить</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    });

    container.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-work')) {
            const id = e.target.dataset.id;
            const row = document.getElementById('work-row-' + id);
            if (row) row.remove();
        }
    });
});
