document.addEventListener('DOMContentLoaded', () => {
    const btnGenerateName = document.querySelector('.generate-name');
    const nameField = document.querySelector('input[name="card[name]"]');

    btnGenerateName.addEventListener('click', (event) => {
        event.preventDefault();

        fetch('/generate-card-name')
            .then(response => response.json())
            .then(data => {
                nameField.value = data.name;
            })
            .catch(error => console.error('Error:', error));
    });
});