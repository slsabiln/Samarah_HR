document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('[data-sidebar]');
    const toggle = document.querySelector('[data-sidebar-toggle]');
    if (toggle && sidebar) {
        toggle.addEventListener('click', () => sidebar.classList.toggle('open'));
        document.addEventListener('click', (event) => {
            if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                sidebar.classList.remove('open');
            }
        });
    }

    document.querySelectorAll('input[type="number"]').forEach((input) => {
        input.addEventListener('wheel', (event) => event.preventDefault(), { passive: false });
    });
});
