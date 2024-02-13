document.addEventListener('DOMContentLoaded', ()=>{
    const reportsForm = document.querySelector('#reportsForm');
    reportsForm.addEventListener('submit', (e) => e.preventDefault());

    const reportsSubmitBtns = reportsForm.querySelectorAll('.btn');
    reportsSubmitBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const formAction = routes[e.target.id.split('_')[1]];
            reportsForm.setAttribute('action', formAction);
            reportsForm.submit();
        });
    });
});