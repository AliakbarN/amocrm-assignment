
document.getElementById("submit-button").addEventListener("click", (event) => {
    // Prevent the default behavior of the click event
    event.preventDefault();

    const currentOrigin = window.location.origin;
    let url = currentOrigin + '/contact/store';

    let formData = {
        'firstName': document.getElementById('form.first-name').value,
        'lastName': document.getElementById('form.last-name').value,
        'email': document.getElementById('form.email').value,
        'phone': document.getElementById('form.phone').value,
        'age': document.getElementById('form.age').value,
        'gender': document.getElementById('form.gender').value,
    }

    // Convert form data to JSON
    let jsonData = JSON.stringify(formData);

    // Send POST request
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            // Add any additional headers if needed
        },
        body: jsonData
    })
        .then(response => response.json())
        .then(data => {
            // Handle the response data
            console.log('Response:', data);
        })
        .catch(error => {
            // Handle errors
            console.error('Error:', error);
        });
});
