document.addEventListener('DOMContentLoaded', function () {

    let page = 1;
    const perPage = 10;

    function fetchBreweries(page = 1) {

    	// Controlla se il token è presente
		const token = localStorage.getItem('spa_token');

		if (!token) {
		    // Se il token non è presente, reindirizza alla pagina di login
		    window.location.href = './login.html';
		}
		else {

    		// Configura Axios per includere il token
    		axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

	        axios.get('http://localhost/api/breweries', {
	            params: {
	                page: page,
	                per_page: perPage
	            }
	        })
	        .then(response => {

	            const data = response.data.data;
	            const meta = response.data.meta;

	            page = meta.page;

	            // Update breweries list
	            updateBreweriesList(data);

	            // Update pager
	            updatePager(meta.page, meta.total_pages);
	        })
	        .catch(error => {

		        if (error.response && error.response.status === 401) {
		            console.error('Unauthorized: Token is invalid or expired.');
		            localStorage.removeItem('spa_token');
		            window.location.href = './login.html';
		        } else {
		            console.error('Error fetching breweries:', error);
		            alert('An error occurred while fetching breweries. Please try again later.');
		        }
	        });

    	}
    }

    function updateBreweriesList(breweries) {

        const breweriesList = document.querySelector('#breweries-list tbody');

        breweriesList.innerHTML = '';

        breweries.forEach(brewery => {

	        const row = document.createElement('tr');

	        const nameCell = document.createElement('td');
	        nameCell.textContent = brewery.name;
	        row.appendChild(nameCell);

	        const cityCell = document.createElement('td');
	        cityCell.textContent = brewery.city;
	        row.appendChild(cityCell);

	        const stateCell = document.createElement('td');
	        stateCell.textContent = brewery.state;
	        row.appendChild(stateCell);

	        breweriesList.appendChild(row);

        });
    }

    function updatePager(page, totalPages) {

        const prevPageButton = document.getElementById('prev-page');
        const nextPageButton = document.getElementById('next-page');
        const pageInfo = document.getElementById('page-info');

        pageInfo.textContent = `Page ${page} of ${totalPages}`;

        prevPageButton.disabled = page <= 1;
        nextPageButton.disabled = page >= totalPages;
    }

    document.getElementById('prev-page').addEventListener('click', () => {
        if (page > 1) {
            fetchBreweries(page - 1);
            page--;
        }
    });

    document.getElementById('next-page').addEventListener('click', () => {
        fetchBreweries(page + 1);
        page++;
    });

    // Initial fetch
    fetchBreweries(page);

});