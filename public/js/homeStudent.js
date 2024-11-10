let btnModalRequests = document.getElementById('btnModalRequests');
let modalRequests = document.getElementById('modalRequests');
let requestType = document.getElementById('requestType');
let dataForRequest = document.getElementById('dataForRequest');
let modalRequestsBS = new bootstrap.Modal(modalRequests);
let optionsBody = async (value) => {
    let body = `<textarea name="comments" placeholder="Justify" id="commets" class="form-control bg-aux my-4 text"></textarea>`;
    switch (value) {
        case "2": 
            body+= '<input type="file" name="" class="form-control my-4" id="">';
            break
        case "3": 
            let HTML = `<select name="careerChange" class="form-control my-4" id="careerChange">
            <option value="">Select Career</option>`
            const response = await fetch('/api/get/public/allCareers.php');
            const data = await response.json();

            data.forEach(career => {
                HTML += `<option value="${career.career_id}">${career.career_name}</option>`;
            });
            HTML+= `</select>`;
            body += HTML;
            break;
        case "4":
            let HTML2 = `<select name="careerChange" class="form-control my-4" id="careerChange">
            <option value="">Select Campus</option>`
            const response2 = await fetch('/api/get/public/allCampus.php');
            const data2 = await response2.json();

            data2.forEach(campus => {
                HTML2 += `<option value="${campus.center_id}">${campus.center_name}</option>`;
            });
            HTML2+= `</select>`;
            body += HTML2;
            break;
        default:
            break;

    }
    body += `<button class="btn bg-custom-primary text mt-2 form-control">Send</button>`;
    return body
    
}

btnModalRequests.addEventListener('click', (e)=>{
    modalRequestsBS.show();
})

requestType.addEventListener('change', async (e)=>{
    const value = e.target.value;
    dataForRequest.innerHTML = `<center><div class="spinner-grow text-secondary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>`
    dataForRequest.innerHTML = await optionsBody(value);
})