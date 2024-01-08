
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

    let inputElement = document.getElementsByName("_token")[0];

    // csrf
    let csrf_token = inputElement.value;

    // Send POST request
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf_token
        },
        body: jsonData
    })
        .then(response => response.json())
        .then(data => {
            // Handle the response data
            let alert_success = document.getElementById('card');
            alert_success.classList.remove('hidden');

            document.getElementById('contBtn').onclick = (e) => {
                e.preventDefault();

                alert_success.classList.add('hidden');
            }
        })
        .catch(error => {
            // Handle errors
            let alert_failure = document.getElementById('alert-failure');
            alert_failure.classList.remove('hidden');

            setTimeout(() => {
                alert_failure.classList.add('hidden')
            }, 3000);
        })
        .finally(() => {
            let form = document.getElementById('form');
            form.reset();
        });
});
