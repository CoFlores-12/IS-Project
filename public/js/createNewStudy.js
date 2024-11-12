const fileInput = document.getElementById('csvFile');
const sendButton = document.getElementById('sendCSV');

sendButton.addEventListener("click", () => {
    const file = fileInput.files[0];

    if (!file) {
        alert("Please select a file.");
        return;
    }

    const reader = new FileReader();

    reader.onload = function(e) {
        const csvContent = e.target.result;

        fetch('/api/post/register/addStudent.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                csvData: csvContent
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
            } else {
                console.error(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    };

    reader.readAsText(file); 
});
