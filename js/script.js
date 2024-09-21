/**
 * Cryptocurrency Tracker JavaScript
 * 
 * JavaScript functionalities for the Cryptocurrency Tracker application, including AJAX calls 
 * to fetch real-time data from the CryptoCompare API, and handling pagination and search features.
 * 
 * Created by Amin Amiri
 * Email: amin.shahmirani@gmail.com
 */

$(document).ready(function () {
    async function fetchSymbols(page = 1, search = '') {
        const response = await fetch(`api/fetch_data.php?page=${page}&limit=10&search=${search}`);
        const data = await response.json();
        return data;
    }

    function formatUnixTimestamp(timestamp) {
        const date = new Date(timestamp * 1000); 
        return date.toLocaleDateString("en-US") + ' ' + date.toLocaleTimeString("en-US"); 
    }

    function renderSymbols(symbols) {
        const tableBody = $('#cryptoTable tbody');
        tableBody.empty(); 

        symbols.forEach(symbol => {
            const row = `
                <tr>
                    <td><img src="${symbol.ImageUrl}" alt="${symbol.Name}" width="30"></td>
                    <td>
                        <a href="#" class="crypto-name" 
                           data-symbol="${symbol.Symbol}" 
                           data-description="${symbol.Description}" 
                           data-website="${symbol.AssetWebsiteUrl}" 
                           data-launch="${symbol.AssetLaunchDate}" 
                           data-content-created="${symbol.ContentCreatedOn}" 
                           data-platform="${symbol.PlatformType}">
                           ${symbol.Name}
                        </a>
                    </td>
                    <td>${symbol.Symbol}</td>
                    <td>${symbol.Price.toFixed(8)}</td>
                    <td>${symbol['24hChange'].toFixed(2)}%</td>
                    <td>${symbol.Volume.toLocaleString()}</td>
                    <td>${symbol.MarketCap.toLocaleString()}</td>
                </tr>
            `;
            tableBody.append(row);
        });

        $('.crypto-name').on('click', function (event) {
            event.preventDefault(); 
            const description = $(this).data('description');
            const website = $(this).data('website');
            const launchDate = $(this).data('launch');
            const contentCreated = formatUnixTimestamp($(this).data('content-created'));
            const platformType = $(this).data('platform');
            
            $('#cryptoDescription').text(description);
            $('#cryptoWebsite').attr('href', website).text(website);
            $('#cryptoLaunchDate').text(launchDate);
            $('#cryptoContentCreated').text(contentCreated);
            $('#cryptoPlatformType').text(platformType);
            $('#cryptoModal').modal('show');
        });
    }

    function renderPagination(total, currentPage) {
        const pagination = document.getElementById('pagination');
        const totalPages = Math.ceil(total / 10); 

        pagination.innerHTML = '';

        if (currentPage > 1) {
            const prevButton = document.createElement('button');
            prevButton.textContent = 'Previous';
            prevButton.className = 'btn btn-secondary mx-1';
            prevButton.onclick = () => loadPage(currentPage - 1);
            pagination.appendChild(prevButton);
        }

        const maxVisiblePages = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

        for (let i = startPage; i <= endPage; i++) {
            const button = document.createElement('button');
            button.textContent = i;
            button.className = 'btn btn-primary mx-1';
            button.disabled = (i === currentPage);
            button.onclick = () => loadPage(i);
            pagination.appendChild(button);
        }

        if (currentPage < totalPages) {
            const nextButton = document.createElement('button');
            nextButton.textContent = 'Next';
            nextButton.className = 'btn btn-secondary mx-1';
            nextButton.onclick = () => loadPage(currentPage + 1);
            pagination.appendChild(nextButton);
        }
    }

    async function loadPage(page) {
        $('#loading').show(); 
        const search = $('#search').val();
        const data = await fetchSymbols(page, search);
        $('#loading').hide(); 

        if (data.data) {
            renderSymbols(data.data);
            renderPagination(data.total, page);
        } else {
            console.error(data.error);
        }
    }

    $('#search').on('input', function () {
        loadPage(1); 
    });

    loadPage(1); 
});
