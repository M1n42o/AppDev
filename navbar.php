<!-- NAVBAR CREATION -->
<!-- ADMIN NAVBAR -->
<header class="header" style="background-color:rgb(59, 124, 121); padding: 15px 30px; display: flex; align-items: center; justify-content: space-between;">
    <nav class="navbar" style="display: flex; align-items: center;">
    
    </nav>
    
    <div class="search-container">
        <form class="search-bar" onsubmit="return false;">
            <input type="text" id="search-input" placeholder="Search..." autocomplete="off">
        </form>
        <div id="search-results" class="search-results-dropdown"></div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    const searchButton = document.getElementById('search-button');
    
    // Function to perform search
    function performSearch() {
        const query = searchInput.value.trim();
        
        if (query.length < 2) {
            searchResults.innerHTML = '';
            searchResults.style.display = 'none';
            return;
        }
        
    // Fetch search results via AJAX
    fetch(`search_results.php?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                let html = '<table class="search-results-table">';
            html += '<tr><th>ID</th><th>Product</th><th>Category</th></tr>';

            data.forEach(item => {
                html += `<tr class="search-result-item" data-id="${item.id}" data-type="${item.type}">`;
                html += `<td>${item.id}</td>`;
                html += `<td>${item.title}</td>`;
                html += `<td>${item.type.charAt(0).toUpperCase() + item.type.slice(1)}</td>`;
                html += '</tr>';
            });

            html += '</table>';

                searchResults.innerHTML = html;
                searchResults.style.display = 'block';

                // Add click event to result items
                document.querySelectorAll('.search-result-item').forEach(item => {
                    item.addEventListener('click', function () {
                        const id = this.getAttribute('data-id');
                        const type = this.getAttribute('data-type');
                        let url = '';

                        switch (type) {
                            case 'product':
                                url = `adminpages/manage_product.php?highlight_id=${id}`;
                                break;
                            case 'category':
                                url = `adminpages/category.php?highlight_id=${id}`;
                                break;
                        }

                        if (url) {
                            window.location.href = url;
                        }
                    });
                });

            } else {
                searchResults.innerHTML = '<div class="no-results">No results found</div>';
                searchResults.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            searchResults.innerHTML = '<div class="no-results">Error fetching results</div>';
            searchResults.style.display = 'block';
        });

    }
    
    // Event listener for input changes (with debounce)
    let debounceTimer;
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(performSearch, 300);
    });
    
    // Event listener for search button click
    searchButton.addEventListener('click', performSearch);
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
            searchResults.style.display = 'none';
        }
    });
});
</script>

<style>
.navbar a{
    position: relative;
    font-size: 16px;
    color: #ffffff;
    margin-right: 30px;
    text-decoration: none;
}
.navbar a::after{
    content: "";
    position: absolute;
    left: 0;
    width: 100%;
    height: 2px;
    background: #fff;
    bottom: -5px;
    border-radius: 5px;
    transform: translateY(10px);
    opacity: 0;
    transition: .5s ease;
}
.navbar a:hover:after{
    transform: translateY(0);
    opacity: 1;
}




/* Search container and search bar styles */

.search-container {
    position: relative;
    width: 400px; /* Increased width from default */
}

.search-bar {
    display: flex;
    width: 100%;
}

#search-input {
    flex: 1;
    padding: 10px 16px;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px 20px 20px 20px;
    background: white; /* change this */
    border: none; /* optional: remove border */
    color: black;
    font-size: 15px;
    transition: all 0.3s ease;
    outline: none;
}


/* Search results dropdown styles */
.search-results-dropdown {
    display: none;
    position: absolute;
    top: calc(100% + 5px);
    left: 0;
    right: 0;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    overflow-y: auto;
    z-index: 1000;

}

.search-results-table {
    width: 100%;
    border-collapse: collapse;
}

.search-results-table th,
.search-results-table td {
    padding: 12px 18px; /* Increased padding */
    text-align: left;
    border-bottom: 1px solid #eee;
}

.search-results-table th {
    background-color: #f5f5f5;
    color: #333;
    font-weight: bold;
    position: sticky;
    top: 0;
}

.search-results-table th:first-child {
    border-top-left-radius: 10px;
}

.search-results-table th:last-child {
    border-top-right-radius: 10px;
}

.search-result-item {
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.search-result-item:hover {
    background-color: #f0f7ff; /* Light blue highlight on hover */
}

.search-result-item td:first-child {
    font-weight: 500; /* Make titles slightly bolder */
}

.search-result-item td:last-child {
    color: #666;
    font-style: italic;
    width: 100px; /* Fixed width for type column */
}

.no-results {
    padding: 20px;
    text-align: center;
    color: #666;
    font-style: italic;
}

/* Scrollbar styling for the dropdown */
.search-results-dropdown::-webkit-scrollbar {
    width: 8px;
}

.search-results-dropdown::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 0 10px 10px 0;
}

.search-results-dropdown::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}

.search-results-dropdown::-webkit-scrollbar-thumb:hover {
    background: #aaa;
}
</style>
</header>