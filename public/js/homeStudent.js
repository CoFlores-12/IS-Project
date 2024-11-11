let btnModalRequests = document.getElementById('btnModalRequests');
let modalRequests = document.getElementById('modalRequests');
let requestType = document.getElementById('requestType');
let dataForRequest = document.getElementById('dataForRequest');
let modalRequestsBS = new bootstrap.Modal(modalRequests);
let optionsBody = async (value) => {
    let body = `<textarea name="comments" placeholder="Justify" id="comments" class="form-control bg-aux my-4 text"></textarea>`;
    switch (value) {
        case "2": 
            body+= '<input type="file" name="evidemce"  accept="application/pdf"  class="form-control my-4" id="evidence">';
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
            let HTML2 = `<select name="careerChange" class="form-control my-4" id="campusChange">
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
    body += `<button id="sendRequestBtn" class="btn bg-custom-primary text mt-2 form-control">Send</button>`;
    
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
    const newHTML = await optionsBody(value);
    dataForRequest.innerHTML = newHTML;
    addEvents(value);
})
function addEvents(key) {
    const sendRequestBtn = document.getElementById('sendRequestBtn');
    sendRequestBtn.disabled =false;
    sendRequestBtn.addEventListener('click', (e)=>{
        e.target.innerHTML = `<div class="spinner-border text-light" role="status"></div>`
        e.target.disabled = true;
        const formData = new FormData();
        formData.append('request_type_id', document.getElementById('requestType').value);
        formData.append('comments', document.getElementById('comments').value);
        try {
            formData.append("evidence", document.getElementById('evidence').files[0])
        } catch (error) {}
        try {
            formData.append("career_change_id", document.getElementById('careerChange').value)
        } catch (error) {}
        try {
            formData.append("campus_change_id", document.getElementById('campusChange').value)
        } catch (error) {}
        fetch('/api/post/students/createRequest.php',{
            method: 'POST',
            body: formData
        })
        .then((response)=>{return response.json()})
        .then((response)=>{
            alert(response.message);
            e.target.innerHTML = `Send`;
            e.target.disabled = false;
            dataForRequest.innerHTML = '';
            modalRequestsBS.hide();
        })
        .catch(()=>{
            alert('error');
            e.target.innerHTML = `Send`;
            e.target.disabled = false;
        })
    });
    switch (key) {
        case "3":
            sendRequestBtn.disabled =true;
            document.getElementById('careerChange').addEventListener('change', async (e)=>{
                sendRequestBtn.disabled = false;
                if (e.target.value === '') {
                    sendRequestBtn.disabled = true;
                }
            })
            break;
            case "4":
            sendRequestBtn.disabled =true;
            document.getElementById('campusChange').addEventListener('change', async (e)=>{
                sendRequestBtn.disabled = false;
                if (e.target.value === '') {
                    sendRequestBtn.disabled = true;
                }
            })
        break;
        default:
            break;
    }
    
    
}