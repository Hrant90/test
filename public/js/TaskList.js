document.addEventListener('DOMContentLoaded', function () {
    const taskTableBody = document.getElementById('table-body');
    const modal = document.getElementById('task-modal');
    const closeModalBtn = document.querySelector('.close-btn');
    const modalTitle = document.getElementById('modal-title');
    const modalDescription = document.getElementById('modal-description');
    const modalStatus = document.getElementById('modal-status');
    const modalDate = document.getElementById('modal-date');
    const modalAuthor = document.getElementById('modal-author');
    const paginationContainer = document.getElementById('pagination');

    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');

    const tasksPerPage = 10;
    let currentSearch = '';

    function loadTasks(page = 1, search = '') {
        fetch(`../../mvc-api/api/v1/task?page=${page}&limit=${tasksPerPage}&search=${encodeURIComponent(search)}`)
            .then(response => response.json())
            .then(data => {
                let { tasks, meta } = data;
                taskTableBody.innerHTML = '';
                tasks.forEach(task => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${task.id}</td>
                        <td>${task.title}</td>
                        <td>${task.date}</td>
                    `;
                    tr.setAttribute('data-task-id', task.id);

                    tr.addEventListener('click', function () {
                        showTaskDetails(task.id);
                    });
                    taskTableBody.appendChild(tr);
                });

                renderPagination(meta);
            })
            .catch(error => console.error('Error fetching tasks:', error));
    }

    searchBtn.addEventListener('click', function () {
        currentSearch = searchInput.value;
        loadTasks(1, currentSearch);
    });

    function setCookie(name, value, hours) {
        let date = new Date();
        date.setTime(date.getTime() + (hours * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = name + "=" + JSON.stringify(value) + ";" + expires + ";path=/";
    }

    function getCookie(name) {
        let cname = name + "=";
        let decodedCookie = decodeURIComponent(document.cookie);
        let cookieArray = decodedCookie.split(';');
        for (let i = 0; i < cookieArray.length; i++) {
            let cookie = cookieArray[i].trim();
            if (cookie.indexOf(cname) === 0) {
                return JSON.parse(cookie.substring(cname.length, cookie.length));
            }
        }
        return null;
    }

    function showTaskDetails(id) {
        let cachedTask = getCookie(`task_${id}`);
        if (cachedTask) {
            displayTaskInModal(cachedTask);
        } else {
            fetch(`../../mvc-api/api/v1/task/${id}`)
                .then(response => response.json())
                .then(task => {
                    if (task.error) {
                        alert(task.error);
                    } else {
                        setCookie(`task_${id}`, task, 1);
                        displayTaskInModal(task);
                    }
                })
                .catch(error => console.error('Error fetching task details:', error));
        }
    }

    function displayTaskInModal(task) {
        modalTitle.textContent = task.title;
        modalDescription.textContent = task.description;
        modalStatus.textContent = task.status;
        modalDate.textContent = new Date(task.date).toLocaleDateString();
        modalAuthor.textContent = task.author;
        modal.style.display = 'block';
    }

    function renderPagination(meta) {
        paginationContainer.innerHTML = '';

        if (meta.page > 1) {
            let prevBtn = document.createElement('button');
            prevBtn.textContent = 'Previous';
            prevBtn.addEventListener('click', function () {
                loadTasks(meta.page - 1, currentSearch);
            });
            paginationContainer.appendChild(prevBtn);
        }

        for (let i = 1; i <= meta.total_pages; i++) {
            let pageBtn = document.createElement('button');
            pageBtn.textContent = i;
            if (i === meta.page) {
                pageBtn.disabled = true;
            }
            pageBtn.addEventListener('click', function () {
                loadTasks(i, currentSearch);
            });
            paginationContainer.appendChild(pageBtn);
            if (i>=5) {
                break;
            }
        }

        if (meta.page < meta.total_pages) {
            let nextBtn = document.createElement('button');
            nextBtn.textContent = 'Next';
            nextBtn.addEventListener('click', function () {
                loadTasks(meta.page + 1, currentSearch);
            });
            paginationContainer.appendChild(nextBtn);
        }
    }

    closeModalBtn.addEventListener('click', function () {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    loadTasks(1);
});
