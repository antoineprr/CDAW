document.addEventListener('DOMContentLoaded', function() {
    const icon = document.querySelector('.like-b i');
    const span = document.querySelector('.like-b span');

    icon.addEventListener('click', function() {
        icon.classList.toggle('press');
        span.classList.toggle('press');
    });
});