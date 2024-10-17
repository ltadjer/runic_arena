import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

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