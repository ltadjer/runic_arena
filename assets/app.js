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

    if (btnGenerateName && nameField) {
        btnGenerateName.addEventListener('click', (event) => {
            event.preventDefault();

            fetch('/generate-card-name')
                .then(response => response.json())
                .then(data => {
                    nameField.value = data.name;
                })
                .catch(error => console.error('Error:', error));
        });
    }

    const cardsByTypeDataElement = document.getElementById('cardsByTypeData');
    const cardsByClassDataElement = document.getElementById('cardsByClassData');

    if (cardsByTypeDataElement && cardsByClassDataElement) {
        const cardsByTypeData = JSON.parse(cardsByTypeDataElement.textContent);
        const cardsByClassData = JSON.parse(cardsByClassDataElement.textContent);

        const ctxType = document.getElementById('cardsByTypeChart').getContext('2d');
        const ctxClass = document.getElementById('cardsByClassChart').getContext('2d');

        new Chart(ctxType, {
            type: 'doughnut',
            data: {
                labels: cardsByTypeData.map(item => item.type),
                datasets: [{
                    data: cardsByTypeData.map(item => item.count),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                }]
            }
        });

        new Chart(ctxClass, {
            type: 'doughnut',
            data: {
                labels: cardsByClassData.map(item => item.class),
                datasets: [{
                    data: cardsByClassData.map(item => item.count),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                }]
            }
        });
    }
});