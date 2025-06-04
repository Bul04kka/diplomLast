// document.addEventListener('DOMContentLoaded', function () {
//     document.querySelectorAll('.recommendation-checkbox').forEach(function (checkbox) {
//         toggleInput(checkbox); // При загрузке показать/скрыть

//         checkbox.addEventListener('change', function () {
//             toggleInput(this);
//         });
//     });

//     function toggleInput(checkbox) {
//         const input = checkbox.closest('.mb-3').querySelector('.recommendation-input');
//         if (checkbox.checked) {
//             input.style.display = 'inline-block';
//         } else {
//             input.style.display = 'none';
//         }
//     }
// });

// document.addEventListener('DOMContentLoaded', function(){
//   const form = document.getElementById('recommendation-form');
//   const container = document.getElementById('recommendation-container');

//   form.addEventListener('submit', function(e){
//     e.preventDefault();
//     // отправляем форму AJAX-ом
//     fetch(form.action, {
//       method: 'POST',
//       body: new FormData(form),
//       headers: { 'X-Requested-With': 'XMLHttpRequest' }
//     })
//     .then(response => response.json())       // предполагаем JSON-ответ
//     .then(json => {
//       // заменяем форму на сообщение
//       container.innerHTML = 
//         '<div class="alert alert-success">Рекомендация применена</div>';
//       // обновляем счётчик корзины, если нужно
//       if (typeof updateCartCount === 'function') {
//         updateCartCount();
//       }
//     })
//     .catch(() => {
//       alert('Не удалось применить рекомендации. Попробуйте ещё раз.');
//     });
//   });
// });


document.addEventListener('DOMContentLoaded', function () {
    const form      = document.getElementById('recommendation-form');
    const container = document.getElementById('recommendation-container');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(json => {
            if (json.success) {
                container.innerHTML = '<div class="alert alert-success">Рекомендация применена</div>';
                // если на странице есть функция обновления счётчика корзины
                if (typeof updateCartCount === 'function') {
                    updateCartCount();
                }
            } else {
                throw new Error(json.message || 'Unknown error');
            }
        })
        .catch(err => {
            console.error('Ошибка при применении рекомендаций:', err);
            alert('Не удалось применить рекомендации. Попробуйте ещё раз.');
        });
    });
});
