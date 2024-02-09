document.addEventListener('DOMContentLoaded', ()=>{
    const modal = document.querySelector('#modal');

    const addUniversityForm = document.querySelector('#addUniversityForm');
    const addTechnologyForm = document.querySelector('#addTechnologyForm');

    const addUniversityBtn = document.querySelector('#addUniversityBtn');
    addUniversityBtn.addEventListener('click', (e) => {
        modal.querySelector('.modal-title').innerHTML = 'Въведи нов университет';
        addTechnologyForm.classList.add('d-none');
        addUniversityForm.classList.remove('d-none');
    });

    const addTechnologyBtn = document.querySelector('#addTechnologyBtn');
    addTechnologyBtn.addEventListener('click', (e) => {
        modal.querySelector('.modal-title').innerHTML = 'Въведи нова технология';
        addUniversityForm.classList.add('d-none');
        addTechnologyForm.classList.remove('d-none');
    });

    addUniversityForm.addEventListener('submit', (e) => {
        e.preventDefault();
        fetchFormFunction(e.target, e.target.getAttribute('method'), saveUniversity);
    });

    addTechnologyForm.addEventListener('submit', (e) => {
        e.preventDefault();
        fetchFormFunction(e.target, e.target.getAttribute('method'), saveTechnology);
    });
});

const saveUniversity = function (data) {
    const uniDropdown = document.querySelector('select[name="university"]');
    uniDropdown.appendChild(createSelectOption(data));
};

const saveTechnology = function (data) {
    const techDropdown = document.querySelector('select[name="technologies[]"]');
    techDropdown.appendChild(createSelectOption(data));
};