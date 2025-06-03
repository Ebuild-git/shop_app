    function getFiltersQueryString() {
        const form = document.getElementById('filter-form');
        return new URLSearchParams(new FormData(form)).toString();
    }

    function fetchAndReplace(urlWithParams) {
        fetch(urlWithParams, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const newTbody = doc.querySelector('#commande-table-body');
            const currentTbody = document.querySelector('#commande-table-body');
            if (newTbody && currentTbody) {
                currentTbody.innerHTML = newTbody.innerHTML;
            }

            const newPagination = doc.querySelector('.pagination');
            const currentPagination = document.querySelector('.pagination');
            if (newPagination && currentPagination) {
                currentPagination.innerHTML = newPagination.innerHTML;
                attachPaginationListeners(); // Rebind pagination links
            }
        });
    }

    document.getElementById('filter-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const baseUrl = "{{ route('orders') }}";
        const queryString = getFiltersQueryString();
        fetchAndReplace(`${baseUrl}?${queryString}`);
    });

    document.getElementById('reset-btn').addEventListener('click', function () {
        document.getElementById('regionFilter').value = '';
        document.getElementById('dateFilter').value = '';
        document.getElementById('filter-form').dispatchEvent(new Event('submit'));
    });

    function attachPaginationListeners() {
        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                const pageUrl = new URL(this.href);
                const page = pageUrl.searchParams.get('page');
                const query = getFiltersQueryString();
                const baseUrl = "{{ route('orders') }}";

                fetchAndReplace(`${baseUrl}?${query}&page=${page}`);
            });
        });
    }

    attachPaginationListeners();


