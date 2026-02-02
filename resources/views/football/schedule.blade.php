@extends('layouts.app')

@section('content')
<div class="container-lg custom-container">
    <div class="premier-league-hero py-4 rounded-top-4" id="competition-hero">
        <div class="container-lg">
            <div class="row align-items-center">
                <div class="col-12">
                    <h1 class="display-6 fw-bold text-white mb-2" id="competition-title">
                        <i class="fas fa-futbol me-2"></i>Premier League Schedule
                    </h1>
                    <p class="text-white mb-0" id="competition-subtitle">Fixtures, and match information</p>
                </div>
            </div>
        </div>
    </div>

    @section('head')
    @parent
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    @endsection

    <div class="py-4">
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card competition-card premier-league h-100 border-0 shadow-sm" onclick="changeCompetition('premier-league')" style="cursor: pointer;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="competition-icon bg-primary">
                                <i class="fas fa-crown"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="card-title fw-bold mb-0">Premier League</h5>
                                <small class="text-muted">England</small>
                            </div>
                        </div>
                        <p class="card-text text-muted mb-3">Top English football league</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-primary-light text-primary" id="premier-count">0 matches</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card competition-card champions-league h-100 border-0 shadow-sm" onclick="changeCompetition('champions-league')" style="cursor: pointer;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="competition-icon" style="background: linear-gradient(135deg, #0047AB 0%, #D4AF37 100%);">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="card-title fw-bold mb-0">Champions League</h5>
                                <small class="text-muted">Europe</small>
                            </div>
                        </div>
                        <p class="card-text text-muted mb-3">European club competition</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge" style="background: rgba(0, 71, 171, 0.1); color: #0047AB;" id="champions-count">0 matches</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card competition-card world-cup h-100 border-0 shadow-sm" onclick="changeCompetition('world-cup')" style="cursor: pointer;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="competition-icon" style="background: linear-gradient(135deg, #0066B3 0%, #FFD700 100%);">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="card-title fw-bold mb-0">World Cup</h5>
                                <small class="text-muted">International</small>
                            </div>
                        </div>
                        <p class="card-text text-muted mb-3">International tournament</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge" style="background: rgba(0, 102, 179, 0.1); color: #0066B3;" id="worldcup-count">0 matches</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-4">
        <div class="card border-0 shadow">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-0" id="matches-title">Premier League Matches</h4>
                    </div>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm w-auto" id="competition-select" onchange="changeCompetition(this.value)">
                            <option value="premier-league">Premier League</option>
                            <option value="champions-league">Champions League</option>
                            <option value="world-cup">World Cup</option>
                        </select>
                        <button class="btn btn-danger btn-sm" onclick="clearFilters()">
                            <i class="fas fa-times me-1"></i>Clear Filter
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="card-body border-bottom p-3 bg-light">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Filter by Team Name</label>
                        <input type="text" class="form-control" id="team-filter" placeholder="Search team name..." onkeyup="applyFilters()">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Filter by Date</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 datepicker-toggle" style="cursor:pointer;">
                                <i class="fas fa-calendar-alt text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" id="date-filter" placeholder="Select date..." readonly style="background-color: #fff; cursor: pointer;">
                            <button class="btn btn-outline-secondary" type="button" onclick="clearFilters()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="w-100">
                            <div class="small text-muted" id="filter-results-count">Showing all matches</div>
                            <div class="small text-primary fw-bold d-none" id="date-filter-display"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-0">
                <div id="loading-state" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading data...</p>
                </div>

                <div id="error-state" class="text-center py-5 d-none">
                    <div class="py-5">
                        <i class="fas fa-exclamation-triangle fa-2x text-warning mb-3"></i>
                        <h5 class="mb-2">Unable to Load</h5>
                        <button onclick="refreshData()" class="btn btn-primary btn-sm">Try Again</button>
                    </div>
                </div>

                <div id="no-filter-results" class="text-center py-5 d-none">
                    <i class="fas fa-search fa-2x text-muted mb-3"></i>
                    <p class="text-muted">No matches found for the selected criteria.</p>
                </div>

                <div id="matches-container" class="d-none">
                    <div id="matches-list" class="p-3"></div>
                    <div id="pagination-controls" class="p-3 border-top d-flex justify-content-center"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-4">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-3">
                        <i class="fas fa-calendar-alt fa-lg text-primary mb-2"></i>
                        <h4 class="fw-bold" id="total-matches">0</h4>
                        <p class="text-muted mb-0 small">Total Matches</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-3">
                        <i class="fas fa-play-circle fa-lg text-success mb-2"></i>
                        <h4 class="fw-bold" id="live-matches">0</h4>
                        <p class="text-muted mb-0 small">Live Now</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-3">
                        <i class="fas fa-clock fa-lg text-warning mb-2"></i>
                        <h4 class="fw-bold" id="upcoming-matches">0</h4>
                        <p class="text-muted mb-0 small">Upcoming</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
let allMatches = [];
let currentFilteredMatches = [];
let selectedDate = null;
let currentPage = 1;
let currentCompetition = 'premier-league';
const itemsPerPage = 10;

let favoritesSet = new Set();
const subscriptions = {}; 

async function loadFavorites() {
    try {
        const res = await fetch('/favorites', { headers: { 'Accept': 'application/json' } });
        if (!res.ok) return;
        const json = await res.json();
        favoritesSet = new Set((json.favorites || []).map(String));
        for (const apiId of favoritesSet) {
            subscribeToGameChannel(apiId);
        }
    } catch (e) {
        console.warn('Failed to load favorites', e);
    }
}

function subscribeToGameChannel(apiId) {
    if (typeof window.Echo === 'undefined') return;
    if (subscriptions[apiId]) return;

    try {
        subscriptions[apiId] = window.Echo.private(`game.${apiId}`)
            .listen('GameTicketSold', (e) => {
                showToast(e.message || 'Update for this match');
            });
    } catch (err) {
        console.warn('Subscribe failed', err);
    }
}

function unsubscribeFromGameChannel(apiId) {
    if (typeof window.Echo === 'undefined') return;
    if (!subscriptions[apiId]) return;

    try {
        window.Echo.leave(`game.${apiId}`);
        delete subscriptions[apiId];
    } catch (err) {
        console.warn('Unsubscribe failed', err);
    }
}

function showToast(html, { duration = 4000 } = {}) {
    const toast = document.createElement('div');
    toast.className = 'toast-notification bg-dark text-white p-3 rounded shadow-sm';
    toast.style.position = 'fixed';
    toast.style.right = '20px';
    toast.style.bottom = '20px';
    toast.style.zIndex = 9999;
    toast.style.opacity = '0';
    toast.style.maxWidth = '320px';
    toast.innerHTML = html;

    toast.style.transform = 'translateY(8px)';
    setTimeout(() => toast.style.transform = 'translateY(0)', 100);
    document.body.appendChild(toast);
    setTimeout(() => toast.style.opacity = '1', 50);
    setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 400); }, duration);
}

function formatIsoDate(iso) {
    if (!iso) return '';
    const d = new Date(iso);
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) + ' ' + d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
}

function escapeHtml(unsafe) {
    return String(unsafe)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}


document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    currentPage = parseInt(urlParams.get('page')) || 1;
    
    const urlComp = urlParams.get('competition');
    if (urlComp && ['premier-league', 'champions-league', 'world-cup'].includes(urlComp)) {
        currentCompetition = urlComp;
        document.getElementById('competition-select').value = currentCompetition;
    }

    loadFavorites().finally(() => {
        fetchCompetitionData();
    });

    setInterval(refreshData, 30000);
    initializeDatepicker();
    updateCompetitionBackground(); 

    document.getElementById('matches-list').addEventListener('click', async function (e) {
        const btn = e.target.closest('.favorite-btn');
        if (!btn) return;

        const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
        if (!isAuthenticated) {
            window.location.href = '/login';
            return;
        }

        const apiId = btn.dataset.gameId;

        try {
            const home = decodeURIComponent(btn.dataset.home || '');
            const away = decodeURIComponent(btn.dataset.away || '');
            const dateIso = btn.dataset.date || '';
            const dateStr = formatIsoDate(dateIso);

            // send game info so the server can persist title/logos/time
            const res = await fetch(`/favorites/${apiId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    home_team: home,
                    away_team: away,
                    home_logo: decodeURIComponent(btn.dataset.homeLogo || ''),
                    away_logo: decodeURIComponent(btn.dataset.awayLogo || ''),
                    match_date: dateIso,
                    match_time: dateIso ? new Date(dateIso).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }) : ''
                })
            });

            const json = await res.json();

            if (json.status === 'favorited') {
                favoritesSet.add(String(apiId));
                subscribeToGameChannel(String(apiId));
                const icon = btn.querySelector('.favorite-icon');
                if (icon) {
                    icon.innerText = '★';
                    icon.classList.add('favorited');
                }

                showToast(`<div><strong>${escapeHtml(home)} vs ${escapeHtml(away)}</strong> has been <span style="color:#00ff85">added</span> to your favorite games<div class="small text-muted mt-1">${escapeHtml(dateStr)}</div></div>`);

                // notify other pages (favorites list) that a favorite was added
                window.dispatchEvent(new CustomEvent('favorite-updated', { detail: { apiId, status: 'favorited', game: json.game || null } }));
            } else if (json.status === 'unfavorited') {
                favoritesSet.delete(String(apiId));
                unsubscribeFromGameChannel(String(apiId));
                const icon = btn.querySelector('.favorite-icon');
                if (icon) {
                    icon.innerText = '☆';
                    icon.classList.remove('favorited');
                }

                showToast(`<div><strong>${escapeHtml(home)} vs ${escapeHtml(away)}</strong> has been removed from your favorite games<div class="small text-muted mt-1">${escapeHtml(dateStr)}</div></div>`);

                window.dispatchEvent(new CustomEvent('favorite-updated', { detail: { apiId, status: 'unfavorited' } }));
            }
        } catch (err) {
            console.warn('Failed to toggle favorite', err);
        }
    });
});

async function fetchCompetitionData() {
    try {
        showLoading();
        
        const apiEndpoints = {
            'premier-league': '/api/football/premier-league',
            'champions-league': '/api/football/champions-league',
            'world-cup': '/api/football/world-cup'
        };
        
        const response = await fetch(apiEndpoints[currentCompetition]);
        const data = await response.json();
        
        if (data.success) {
            allMatches = data.matches || [];
            currentFilteredMatches = [...allMatches];

            const totalPages = Math.max(1, Math.ceil(currentFilteredMatches.length / itemsPerPage));
            if (currentPage > totalPages) { currentPage = totalPages; updateUrl(currentPage); }
            
            updateCompetitionCards();
            updateCompetitionTitle();
            updateCompetitionBackground();
            applyFilters(false); 
            hideLoading();
        } else {
            showError();
        }
    } catch (error) {
        showError();
    }
}

function changeCompetition(competition) {
    currentCompetition = competition;
    currentPage = 1;
    
    const url = new URL(window.location);
    url.searchParams.set('competition', competition);
    url.searchParams.set('page', 1);
    window.history.pushState({}, '', url);
    
    document.getElementById('competition-select').value = competition;
    fetchCompetitionData();
}

function updateCompetitionTitle() {
    const titleMap = {
        'premier-league': 'Premier League Schedule',
        'champions-league': 'Champions League Schedule',
        'world-cup': 'World Cup Schedule'
    };
    
    const subtitleMap = {
        'premier-league': 'Fixtures, and match information',
        'champions-league': 'European club competition matches',
        'world-cup': 'International tournament matches'
    };
    
    document.getElementById('competition-title').innerHTML = `<i class="fas fa-futbol me-2"></i>${titleMap[currentCompetition]}`;
    document.getElementById('competition-subtitle').textContent = subtitleMap[currentCompetition];
    document.getElementById('matches-title').textContent = `${titleMap[currentCompetition].replace(' Schedule', '').replace(' Matches', '')} Matches`;
}

function updateCompetitionBackground() {
    const hero = document.getElementById('competition-hero');
    const backgroundImages = {
        'premier-league': "url('/images/PremierLeague.jpg')",
        'champions-league': "url('/images/ChampionsLeague.jpg')",
        'world-cup': "url('/images/WorldCup.jpg')"
    };
    hero.style.backgroundImage = backgroundImages[currentCompetition];
    hero.style.backgroundSize = 'cover';
    hero.style.backgroundPosition = 'center';
    hero.style.backgroundRepeat = 'no-repeat';
}

function updateCompetitionCards() {
    const matchCounts = { 'premier-league': allMatches.length, 'champions-league': 0, 'world-cup': 0 };
    document.getElementById('premier-count').textContent = `${matchCounts['premier-league']} matches`;
    if (currentCompetition === 'champions-league') document.getElementById('champions-count').textContent = `${allMatches.length} matches`;
    if (currentCompetition === 'world-cup') document.getElementById('worldcup-count').textContent = `${allMatches.length} matches`;
}

function updateUrl(page) {
    const url = new URL(window.location);
    url.searchParams.set('page', page);
    url.searchParams.set('competition', currentCompetition);
    window.history.pushState({}, '', url);
}

function renderMatches(matches) {
    const container = document.getElementById('matches-list');
    container.innerHTML = '';
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const paginatedItems = matches.slice(startIndex, endIndex);
    const isAdmin = {{ auth()->check() && auth()->user()->isAdmin() ? 'true' : 'false' }};
    
    paginatedItems.forEach(match => {
        const matchDate = new Date(match.utcDate);
        const formattedDate = matchDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        const formattedTime = matchDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
        const statusClass = getStatusClass(match.status);
        const statusText = getStatusText(match.status);
        
        let buttonHtml = isAdmin 
            ? `<button class="btn btn-warning btn-sm" onclick="manageTickets('${match.id}')"><i class="fas fa-cog me-1"></i>Manage</button>`
            : `<button class="btn btn-primary btn-sm" onclick="viewTickets('${match.id}')"><i class="fas fa-ticket-alt me-1"></i>Get Tickets</button>`;
        
        const homeName = match.homeTeam.shortName || match.homeTeam.name;
        const awayName = match.awayTeam.shortName || match.awayTeam.name;
        const venue = match.venue || (match.area ? match.area.name : 'Stadium');
        const encodedHome = encodeURIComponent(homeName);
        const encodedAway = encodeURIComponent(awayName);
        const encodedDate = encodeURIComponent(match.utcDate);
        const encodedVenue = encodeURIComponent(venue);

        const matchCard = document.createElement('div');
        matchCard.className = 'card mb-3 border-0 shadow-sm';

        const idStr = String(match.id);
        const starred = favoritesSet.has(idStr);
        const homeLogo = match.homeTeam.crest || getTeamIcon(match.homeTeam.name);
        const awayLogo = match.awayTeam.crest || getTeamIcon(match.awayTeam.name);
        const starHtml = `<button class="btn btn-link p-0 favorite-btn" data-game-id="${idStr}" data-home="${encodeURIComponent(homeName)}" data-away="${encodeURIComponent(awayName)}" data-home-logo="${encodeURIComponent(homeLogo)}" data-away-logo="${encodeURIComponent(awayLogo)}" data-date="${encodeURIComponent(match.utcDate)}" aria-label="Favorite"><span class="favorite-icon ${starred ? 'favorited' : ''}">${starred ? '★' : '☆'}</span></button>`;

        matchCard.innerHTML = `
            <div class="card-body p-3">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center position-relative date-col">
                        <div class="date-text fw-bold text-primary small" style="position:relative; z-index:2">${formattedDate}</div>
                        <div class="text-muted smaller">${formattedTime}</div>
                        <span class="badge ${statusClass} mt-1">${statusText}</span>
                        <div class="star-overlay" style="position:absolute; top:6px; left:6px; z-index:3;">${starHtml}</div>
                        <style>
                            /* Make room so star doesn't overlap date text */
                            .date-col .date-text{ padding-left: 34px; }
                        </style>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <img src="${match.homeTeam.crest || getTeamIcon(match.homeTeam.name)}" class="team-crest me-3">
                            <div class="fw-bold">${match.homeTeam.shortName || match.homeTeam.name}</div>
                        </div>
                    </div>
                    <div class="col-md-2 text-center"><span class="badge bg-light text-dark px-3 py-2">VS</span></div>
                    <div class="col-md-4 d-flex align-items-center justify-content-end">
                        <div class="text-end me-3">
                            <div class="fw-bold">${awayName}</div>
                        </div>
                        <img src="${match.awayTeam.crest || getTeamIcon(match.awayTeam.name)}" class="team-crest">
                    </div>
                </div>
                <div class="text-end mt-2">${
                    isAdmin
                        ? buttonHtml
                        : `<button class="btn btn-primary btn-sm" onclick="viewTickets('${match.id}','${encodedHome}','${encodedAway}','${encodedDate}','${encodedVenue}')"><i class="fas fa-ticket-alt me-1"></i>Get Tickets</button>`
                  }</div>
            </div>`;
        container.appendChild(matchCard);
    });
    renderPagination(matches.length);
}

function renderPagination(totalItems) {
    const controls = document.getElementById('pagination-controls');
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    controls.innerHTML = '';
    if (totalPages <= 1) return;
    let html = `<nav><ul class="pagination mb-0">`;
    html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}"><a class="page-link" href="javascript:void(0)" onclick="changePage(${currentPage - 1})">Previous</a></li>`;
    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
            html += `<li class="page-item ${currentPage === i ? 'active' : ''}"><a class="page-link" href="javascript:void(0)" onclick="changePage(${i})">${i}</a></li>`;
        } else if (i === currentPage - 2 || i === currentPage + 2) {
            html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
    }
    html += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}"><a class="page-link" href="javascript:void(0)" onclick="changePage(${currentPage + 1})">Next</a></li>`;
    html += `</ul></nav>`;
    controls.innerHTML = html;
}

function changePage(page) {
    const totalPages = Math.ceil(currentFilteredMatches.length / itemsPerPage);
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    updateUrl(page);
    window.scrollTo({ top: document.getElementById('matches-container').offsetTop - 100, behavior: 'smooth' });
    renderMatches(currentFilteredMatches);
}

function applyFilters(shouldResetPage = true) {
    const teamFilter = document.getElementById('team-filter').value.toLowerCase();
    if (shouldResetPage) { currentPage = 1; updateUrl(1); }
    currentFilteredMatches = allMatches.filter(match => {
        const matchesTeam = !teamFilter || match.homeTeam.name.toLowerCase().includes(teamFilter) || match.awayTeam.name.toLowerCase().includes(teamFilter);
        const matchesDate = !selectedDate || new Date(match.utcDate).toDateString() === selectedDate.toDateString();
        return matchesTeam && matchesDate;
    });
    updateStats(currentFilteredMatches);
    updateFilterResultsCount();
    if (currentFilteredMatches.length === 0) {
        document.getElementById('matches-container').classList.add('d-none');
        document.getElementById('no-filter-results').classList.remove('d-none');
    } else {
        document.getElementById('matches-container').classList.remove('d-none');
        document.getElementById('no-filter-results').classList.add('d-none');
        renderMatches(currentFilteredMatches);
    }
}

function clearFilters() {
    document.getElementById('team-filter').value = '';
    selectedDate = null;
    document.getElementById('date-filter').value = '';
    document.getElementById('date-filter-display').classList.add('d-none');
    applyFilters(true);
}

function initializeDatepicker() {
    $('#date-filter').datepicker({
        dateFormat: 'yy-mm-dd',
        showAnim: 'fadeIn',
        showOtherMonths: true,
        selectOtherMonths: true,
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        firstDay: 1,
        beforeShow: function(input, inst) {
            setTimeout(function() {
                inst.dpDiv.addClass('custom-datepicker');
            }, 0);
        },
        onSelect: function(dateText) {
            selectedDate = new Date(dateText);
            document.getElementById('date-filter-display').textContent = `Filtering: ${selectedDate.toDateString()}`;
            document.getElementById('date-filter-display').classList.remove('d-none');
            applyFilters(true);
        }
    });

    // Open datepicker when input or calendar icon is clicked
    $('#date-filter').on('click', function() { $(this).datepicker('show'); });
    $('.datepicker-toggle').on('click', function() { $('#date-filter').datepicker('show'); });

    // Make the "Today" button set today's date and apply filter
    $(document).on('click', '.ui-datepicker-buttonpane .ui-datepicker-current', function() {
        const today = $.datepicker.formatDate('yy-mm-dd', new Date());
        $('#date-filter').datepicker('setDate', today);
        selectedDate = new Date(today);
        document.getElementById('date-filter-display').textContent = `Filtering: ${selectedDate.toDateString()}`;
        document.getElementById('date-filter-display').classList.remove('d-none');
        applyFilters(true);
    });
}

function updateStats(matches) {
    document.getElementById('total-matches').textContent = matches.length;
    document.getElementById('live-matches').textContent = matches.filter(m => m.status === 'LIVE' || m.status === 'IN_PLAY').length;
    document.getElementById('upcoming-matches').textContent = matches.filter(m => m.status === 'SCHEDULED' || m.status === 'TIMED').length;
}

function updateFilterResultsCount() {
    document.getElementById('filter-results-count').textContent = `Showing ${currentFilteredMatches.length} matches`;
}

function getStatusClass(status) { return (status === 'SCHEDULED' || status === 'TIMED') ? 'bg-success' : 'bg-warning'; }
function getStatusText(status) { return (status === 'SCHEDULED' || status === 'TIMED') ? 'Available' : status.charAt(0) + status.slice(1).toLowerCase(); }
function getTeamIcon(name) { return `data:image/svg+xml;base64,${btoa(`<svg width="32" height="32" xmlns="http://www.w3.org/2000/svg"><rect width="32" height="32" rx="16" fill="#f8f9fa"/><text x="50%" y="50%" font-family="Arial" font-size="12" fill="#6c757d" text-anchor="middle" dy=".3em">${name.charAt(0)}</text></svg>`)}`; }
function showLoading() { document.getElementById('loading-state').classList.remove('d-none'); document.getElementById('matches-container').classList.add('d-none'); document.getElementById('error-state').classList.add('d-none'); }
function hideLoading() { document.getElementById('loading-state').classList.add('d-none'); }
function showError() { document.getElementById('loading-state').classList.add('d-none'); document.getElementById('error-state').classList.remove('d-none'); }
function refreshData() { fetchCompetitionData(); }
function viewTickets(id, home, away, date, venue) {
    const url = new URL(`/stadium/${id}`, window.location.origin);
    if (home) url.searchParams.set('home', decodeURIComponent(home));
    if (away) url.searchParams.set('away', decodeURIComponent(away));
    if (date) url.searchParams.set('date', decodeURIComponent(date));
    if (venue) url.searchParams.set('venue', decodeURIComponent(venue));
    window.location.href = url.toString();
}
function manageTickets(id) { window.location.href = `/admin/tickets/${id}`; }
</script>

<style>
.custom-container { max-width: 1400px; margin: 0 auto; }
.premier-league-hero { background: linear-gradient(135deg, #38003c 0%, #00ff85 100%); }
.competition-icon { width: 45px; height: 45px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; }
.team-crest { width: 40px; height: 40px; object-fit: contain; }
.pagination .page-link { color: #38003c; cursor: pointer; }
.pagination .page-item.active .page-link { background-color: #38003c; border-color: #38003c; color: white; }
.smaller { font-size: 0.75rem; }
.competition-card { transition: transform 0.2s, box-shadow 0.2s; }
.competition-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }

#ui-datepicker-div {
    border: none !important;
    box-shadow: 0 14px 40px rgba(0,0,0,0.18) !important;
    border-radius: 12px !important;
    padding: 8px !important;
    background: #fff !important;
    z-index: 1000 !important;
}


.custom-datepicker .ui-datepicker-header {
    background: linear-gradient(90deg, #38003c 0%, #00ff85 100%);
    color: #fff;
    border-radius: 8px;
    padding: 10px 14px;
    margin-bottom: 8px;
    box-shadow: 0 6px 18px rgba(56,0,60,0.12);
}
.custom-datepicker .ui-datepicker-title { font-weight:700; font-size:1rem; }
.custom-datepicker .ui-datepicker-prev, .custom-datepicker .ui-datepicker-next { color: white; opacity: 0.95; }
.custom-datepicker .ui-datepicker-prev span, .custom-datepicker .ui-datepicker-next span { font-size: 1.1rem; }
.custom-datepicker .ui-datepicker-calendar td a {
    border-radius: 8px;
    padding: 8px 10px;
    display: inline-block;
    color: #333;
}
.custom-datepicker .ui-datepicker-calendar td a:hover {
    background: rgba(56,0,60,0.06);
    color: #38003c;
}
.custom-datepicker .ui-datepicker-calendar td a.ui-state-active {
    background: linear-gradient(90deg,#38003c,#00ff85);
    color: #fff;
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
}
.custom-datepicker .ui-datepicker-buttonpane .ui-datepicker-current, .custom-datepicker .ui-datepicker-buttonpane .ui-datepicker-close {
    border-radius:6px;
    padding:6px 10px;
    margin:4px;
    background:#f8f9fa;
    border: none;
    cursor:pointer;
}
.custom-datepicker .ui-datepicker-buttonpane { text-align:right; padding-top:8px; }

.toast-notification{
    transition: opacity 0.3s ease;
}

.date-col { position: relative; }

.star-overlay .favorite-icon{
    font-size: 22px;
    color: #9aa0a6; 
    opacity: 0.9;
    line-height: 1;
    pointer-events: none; 
}

.favorite-icon.favorited{
    color: #f5c518 !important; 
    opacity: 1 !important;
}

.favorite-btn{ cursor: pointer; background: transparent; border: 0; padding: 0; }
.ui-datepicker-header {
    background: #38003c !important;
    border: none !important;
    border-radius: 8px !important;
    color: white !important;
}
.ui-datepicker-title { font-weight: 600 !important; }
.ui-state-default, .ui-widget-content .ui-state-default {
    border: none !important;
    background: transparent !important;
    text-align: center !important;
    border-radius: 6px !important;
}
.ui-state-hover { background: #f0f0f0 !important; }
.ui-state-active, .ui-widget-content .ui-state-active {
    background: #00ff85 !important;
    color: #38003c !important;
    font-weight: bold !important;
}
.ui-icon { filter: brightness(0) invert(1); }
</style>
@endsection