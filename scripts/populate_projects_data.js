window.addEventListener('DOMContentLoaded', () => {
    const projectsContainer = document.getElementById('projects-container');

    // Clear existing content before fetching new data
    projectsContainer.innerHTML = '';

    // AJAX request to fetch data from the database
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            const projects = JSON.parse(this.responseText);
            projects.forEach(project => {
                const detailsContainer = document.createElement('div');
                detailsContainer.classList.add('details-container', 'color-container');

                const articleContainer = document.createElement('div');
                articleContainer.classList.add('article-container');

                const thumbnailImg = document.createElement('img');
                thumbnailImg.src = `./assets/${project.thumbnail}`;
                thumbnailImg.alt = project.projTitle;
                thumbnailImg.classList.add('project-img');
                articleContainer.appendChild(thumbnailImg);

                detailsContainer.appendChild(articleContainer);

                const title = document.createElement('h2');
                title.classList.add('experience-sub-title', 'project-title');
                title.textContent = project.projTitle;
                detailsContainer.appendChild(title);

                const description = document.createElement('p');
                description.classList.add('project-description');
                description.textContent = project.projDesc;
                detailsContainer.appendChild(description);

                const btnContainer = document.createElement('div');
                btnContainer.classList.add('btn-container');

                const githubBtn = document.createElement('button');
                githubBtn.classList.add('btn', 'btn-color-2', 'project-btn');
                githubBtn.textContent = 'Github';
                githubBtn.onclick = function() {
                    window.open(project.projGh, '_blank');
                };
                btnContainer.appendChild(githubBtn);

                const seeMoreBtn = document.createElement('button');
                seeMoreBtn.classList.add('btn', 'btn-color-2', 'project-btn');
                seeMoreBtn.textContent = 'See More...';
                seeMoreBtn.onclick = function() {
                    window.open(project.projGdrive, '_blank');
                };
                btnContainer.appendChild(seeMoreBtn);

                detailsContainer.appendChild(btnContainer);

                projectsContainer.appendChild(detailsContainer);
            });
        }
    };
    xhr.open('GET', './php/fetch_projects_data.php', true);
    xhr.send();
});
