const fetchFormFunction = (form, method='POST', success_function = null) => {
    let form_url, form_token, form_headers, form_data;

    form_url = form.getAttribute('action');
    form_token = form.querySelector('input[name="_token"]').value;
    form_headers = {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "X-Requested-With": "XMLHttpRequest",
        'X-CSRF-TOKEN': form_token
    };
    form_data = JSON.stringify(serializeForm(form));

    fetch(form_url, {
        method: method,
        body: form_data,
        headers: form_headers,
    }).then(function (response) {
        return response.json();
    }).then(function (data) {
        console.log(data);
        let formAlert = form.querySelector('.alert');
        formAlert.classList.remove('d-none');
        if(data.success){
            formAlert.classList.remove('alert-danger');
            formAlert.classList.add('alert-success');
            formAlert.innerHTML=data.success;

            setTimeout(() => {
                formAlert.classList.add('d-none');
                formAlert.classList.remove('alert-success');
                formAlert.innerHTML='';

                let inputs = form.querySelectorAll('input');
                inputs.forEach(input => input.value = '');
            }, 5000);
        }
        else {
            console.log(data.message);
            formAlert.classList.remove('alert-success');
            formAlert.classList.add('alert-danger');
            if(data.exception)
                formAlert.innerHTML='Грешка.';
            if(!data.exception && data.message) {
                formAlert.innerHTML=data.message;
            }
        }

        if(success_function != null) {
            success_function(data);
        }
    }).catch(function (error) {
        console.warn(error);
    });
};

const createSelectOption = function (data) {
    let new_option = document.createElement('option');
    new_option.value = data.id;
    new_option.innerHTML = data.title;
    return new_option;
};

const serializeForm = function (form) {
    let obj = {};
    let formData = new FormData(form);
    for (let key of formData.keys()) {
        obj[key] = formData.get(key);
    }
    return obj;
};