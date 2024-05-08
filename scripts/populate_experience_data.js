window.addEventListener('DOMContentLoaded', () => {
    const experienceContainer = document.getElementById('experience-container');

    // Clear existing content before fetching new data
    experienceContainer.innerHTML = '';

    // AJAX request to fetch data from the database
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            const experiences = JSON.parse(this.responseText);
            experiences.forEach(experience => {
                const article = document.createElement('article');
                article.innerHTML = `
                    <img src="./assets/${experience.icon_path}" alt="Experience icon" class="icon" />
                    <div>
                        <h3>${experience.technology_name}</h3>
                        <p>${experience.proficiency_level}</p>
                    </div>
                `;
                experienceContainer.appendChild(article);
            });
        }
    };
    xhr.open('GET', './php/fetch_experience_data.php', true);
    xhr.send();
});