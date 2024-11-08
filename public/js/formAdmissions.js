
window.addEventListener('load', function() {
    var forms = document.getElementsByClassName('needs-validation');
    let mainCareer = this.document.getElementById('mainCareer');
    let secondaryCareer = this.document.getElementById('secondaryCareer');
    let loadingModal = this.document.getElementById('loadingModal');
    let MyModalLoading = new bootstrap.Modal(loadingModal);

    var validation = Array.prototype.filter.call(forms, function(form) {
    form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    }, false);
    });


    

    document.getElementById('phone').addEventListener('input', function (e) {
        let input = e.target;
        let value = input.value.replace(/\D/g, '');
        if (value.length > 0 && !/[389]/.test(value[0])) {
            value = '';
        }

        let formattedValue = '';
        if (value.length > 0) {
            formattedValue = value.substring(0, 4);
        }
        if (value.length > 4) {
            formattedValue += '-' + value.substring(4, 8);
        }

        input.value = formattedValue;
    });

    document.getElementById('identity').addEventListener('input', function (e) {
        let input = e.target;
        let value = input.value.replace(/\D/g, ''); 
        let formattedValue = '';

        if (value.length > 0) {
            formattedValue = value.substring(0, 4); 
        }
        if (value.length > 4) {
            formattedValue += '-' + value.substring(4, 8); 
        }
        if (value.length > 8) {
            formattedValue += '-' + value.substring(8, 13); 
        }

        input.value = formattedValue;
    });

    document.getElementById('email').addEventListener('input', function (e) {
        const emailInput = e.target;
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        if (emailPattern.test(emailInput.value)) {
            emailInput.classList.remove('is-invalid');
            emailInput.classList.add('is-valid');
        } else {
            emailInput.classList.remove('is-valid');
            emailInput.classList.add('is-invalid');
        }
    });

    document.getElementById('regionalCenter').addEventListener('change', function (e) {
        MyModalLoading.show();
        mainCareer.disabled = true;
        mainCareer.innerHTML = `<option value="">Select regional center</option>`
        secondaryCareer.innerHTML = `<option value="">Select regional center</option>`
        secondaryCareer.disabled = true;
        if (e.target.value == '') {
            MyModalLoading.hide();
            return;
        }
        
        fetch('/api/get/public/careersByCenter.php?center_id='+e.target.value)
        .then((response)=>{return response.json()})
        .then((response)=>{
            mainCareer.disabled = false;
            secondaryCareer.disabled = false;
            response.forEach(career => {
                mainCareer.innerHTML += `<option value="${career.career_id}">${career.career_name}</option>`
                secondaryCareer.innerHTML += `<option value="${career.career_id}">${career.career_name}</option>`
            });
            MyModalLoading.hide();
        })
    })
}, false);
