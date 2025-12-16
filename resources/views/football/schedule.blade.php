@extends('layouts.app')

@section('content')

<div class="full-width-page">
    <div class="premier-league-hero py-4">
        <div class="full-width-content">
            <div class="row align-items-center">
                <div class="col-12">
                    <h1 class="display-6 fw-bold text-white mb-2">
                        <i class="fas fa-futbol me-2"></i>Premier League Schedule
                    </h1>
                    <p class="text-white mb-0">
                         Fixtures, and match information
                    </p>
                </div>
            </div>
        </div>
    </div>

    @section('head')
    @parent
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    @endsection

    <div class="full-width-content py-4">
        <div class="row">
            <div class="col-12">
                <div class="card competition-card premier-league h-100 border-0 shadow-sm">
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
                            <span class="badge bg-primary-light text-primary" id="premier-count">230 matches</span>
                            @if(auth()->check() && auth()->user()->isAdmin())
                            <a href="/admin/tickets" class="btn btn-warning btn-sm">
                                <i class="fas fa-cog me-1"></i>Manage All Tickets
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="full-width-content py-4">
        <div class="card border-0 shadow">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-0">Premier League Matches</h4>
                        <p class="text-muted mb-0 small" id="active-competition-subtitle">230 matches total</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-danger btn-sm" onclick="clearFilters()">
                            <i class="fas fa-times me-1"></i>Clear Filter
                        </button>
                        @if(auth()->check() && auth()->user()->isAdmin())
                        <a href="/admin/tickets" class="btn btn-warning btn-sm">
                            <i class="fas fa-cog me-1"></i>Manage Tickets
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="card-body border-bottom p-3 bg-light">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Filter by Team Name</label>
                        <input type="text" class="form-control" id="team-filter" 
                               placeholder="Search team name..." onkeyup="applyFilters()">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Filter by Date</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="date-filter" 
                                   placeholder="Select date...">
                            <button class="btn btn-outline-secondary" type="button" onclick="clearDateFilter()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="w-100">
                            <div class="small text-muted" id="filter-results-count">Showing all 230 matches</div>
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
                        <p class="text-muted mb-3 small">Error loading match data</p>
                        <button onclick="refreshData()" class="btn btn-primary btn-sm">
                            <i class="fas fa-redo me-1"></i>Try Again
                        </button>
                    </div>
                </div>

                <div id="empty-state" class="text-center py-5 d-none">
                    <div class="py-5">
                        <i class="fas fa-calendar-times fa-2x text-muted mb-3"></i>
                        <h5 class="mb-2">No Matches</h5>
                        <p class="text-muted small">No matches available</p>
                    </div>
                </div>

                <div id="no-filter-results" class="text-center py-5 d-none">
                    <div class="py-5">
                        <i class="fas fa-search fa-2x text-muted mb-3"></i>
                        <h5 class="mb-2">No Matches Found</h5>
                        <p class="text-muted small">No matches match your filter criteria</p>
                    </div>
                </div>

                <div id="matches-container" class="d-none">
                    <div id="matches-list" class="p-3">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="full-width-content py-4">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-3">
                        <i class="fas fa-calendar-alt fa-lg text-primary mb-2"></i>
                        <h4 class="fw-bold" id="total-matches">230</h4>
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

    <div class="full-width-content py-4">
        <div class="text-center">
            <a href="{{ url('/') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Home
            </a>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetchPremierLeagueData();
    setInterval(refreshData, 30000);
    initializeDatepicker();
});

let allMatches = [];
let currentFilteredMatches = [];
let selectedDate = null;

async function fetchPremierLeagueData() {
    try {
        showLoading();
        const response = await fetch('/api/football/premier-league');
        const data = await response.json();
        if (data.success) {
            allMatches = data.matches || [];
            currentFilteredMatches = [...allMatches];
            updateStats(currentFilteredMatches);
            updateFilterResultsCount();
            sortByDate();
            hideLoading();
        } else {
            showError();
        }
    } catch (error) {
        showError();
    }
}

function initializeDatepicker() {
    $('#date-filter').datepicker({
        dateFormat: 'yy-mm-dd',
        showButtonPanel: true,
        changeMonth: true,
        changeYear: true,
        yearRange: '2023:2025',
        onSelect: function(dateText) {
            selectedDate = new Date(dateText);
            updateDateDisplay(selectedDate);
            applyFilters();
        },
        beforeShowDay: function(date) {
            const hasMatches = allMatches.some(match => {
                const matchDate = new Date(match.utcDate);
                return date.getDate() === matchDate.getDate() &&
                       date.getMonth() === matchDate.getMonth() &&
                       date.getFullYear() === matchDate.getFullYear();
            });
            
            if (hasMatches) {
                return [true, 'has-matches', 'Matches available'];
            }
            return [true, '', ''];
        }
    });
    
    $('#date-filter').click(function() {
        $(this).datepicker('show');
    });
}

function updateDateDisplay(date) {
    const displayElement = document.getElementById('date-filter-display');
    if (date) {
        const formattedDate = date.toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        displayElement.textContent = `Filtering: ${formattedDate}`;
        displayElement.classList.remove('d-none');
    } else {
        displayElement.classList.add('d-none');
    }
}

function applyFilters() {
    const teamFilter = document.getElementById('team-filter').value.toLowerCase();
    
    let filtered = [...allMatches];
    
    if (teamFilter) {
        filtered = filtered.filter(match => {
            const homeTeam = match.homeTeam.name.toLowerCase();
            const awayTeam = match.awayTeam.name.toLowerCase();
            const homeShort = match.homeTeam.shortName ? match.homeTeam.shortName.toLowerCase() : '';
            const awayShort = match.awayTeam.shortName ? match.awayTeam.shortName.toLowerCase() : '';
            
            return homeTeam.includes(teamFilter) || 
                   awayTeam.includes(teamFilter) ||
                   homeShort.includes(teamFilter) ||
                   awayShort.includes(teamFilter);
        });
    }
    
    if (selectedDate) {
        filtered = filtered.filter(match => {
            const matchDate = new Date(match.utcDate);
            return matchDate.toDateString() === selectedDate.toDateString();
        });
    }
    
    currentFilteredMatches = filtered;
    
    if (filtered.length === 0) {
        document.getElementById('matches-container').classList.add('d-none');
        document.getElementById('no-filter-results').classList.remove('d-none');
        document.getElementById('empty-state').classList.add('d-none');
    } else {
        document.getElementById('no-filter-results').classList.add('d-none');
        document.getElementById('matches-container').classList.remove('d-none');
        document.getElementById('empty-state').classList.add('d-none');
        sortByDate();
        updateStats(filtered);
    }
    
    updateFilterResultsCount();
}

function clearFilters() {
    document.getElementById('team-filter').value = '';
    clearDateFilter();
    currentFilteredMatches = [...allMatches];
    sortByDate();
    updateStats(currentFilteredMatches);
    updateFilterResultsCount();
    document.getElementById('no-filter-results').classList.add('d-none');
    document.getElementById('empty-state').classList.add('d-none');
    document.getElementById('matches-container').classList.remove('d-none');
}

function clearDateFilter() {
    selectedDate = null;
    document.getElementById('date-filter').value = '';
    document.getElementById('date-filter-display').classList.add('d-none');
    applyFilters();
}

function updateFilterResultsCount() {
    const total = allMatches.length;
    const filtered = currentFilteredMatches.length;
    const resultsCount = document.getElementById('filter-results-count');
    
    if (filtered === total) {
        resultsCount.textContent = `Showing all ${total} matches`;
        resultsCount.className = 'small text-muted';
    } else {
        resultsCount.textContent = `Showing ${filtered} of ${total} matches`;
        resultsCount.className = 'small text-primary fw-bold';
    }
}

function sortByDate() {
    if (currentFilteredMatches.length === 0) return;
    const matches = [...currentFilteredMatches];
    matches.sort((a, b) => new Date(a.utcDate) - new Date(b.utcDate));
    renderMatches(matches);
}

function renderMatches(matches) {
    const container = document.getElementById('matches-list');
    container.innerHTML = '';
    
    const isAdmin = {{ auth()->check() && auth()->user()->isAdmin() ? 'true' : 'false' }};
    
    matches.forEach(match => {
        const matchDate = new Date(match.utcDate);
        const formattedDate = matchDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        const formattedTime = matchDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
        const statusClass = getStatusClass(match.status);
        const statusText = getStatusText(match.status);
        const homeTeamCrest = match.homeTeam.crest || getTeamIcon(match.homeTeam.name);
        const awayTeamCrest = match.awayTeam.crest || getTeamIcon(match.awayTeam.name);
        
        let buttonHtml = '';
        if (isAdmin) {
            buttonHtml = `
            <button class="btn btn-warning btn-sm" onclick="manageTickets('${match.id}')">
                <i class="fas fa-cog me-1"></i>Manage Tickets
            </button>
            `;
        } else {
            buttonHtml = `
            <button class="btn btn-primary btn-sm" onclick="viewTickets('${match.id}')">
                <i class="fas fa-ticket-alt me-1"></i>Get Tickets
            </button>
            `;
        }
        
        const matchCard = document.createElement('div');
        matchCard.className = 'card mb-3 border-0 shadow-sm';
        matchCard.innerHTML = `
        <div class="card-body p-3">
            <div class="row align-items-center">
                <div class="col-md-2">
                    <div class="text-center">
                        <div class="fw-bold text-primary small">${formattedDate}</div>
                        <div class="text-muted smaller">${formattedTime}</div>
                        <span class="badge ${statusClass} mt-1">${statusText}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <img src="${homeTeamCrest}" alt="${match.homeTeam.name}" class="team-crest me-3" onerror="this.src='${getTeamIcon(match.homeTeam.name)}'">
                        <div>
                            <div class="fw-bold">${match.homeTeam.shortName || match.homeTeam.name}</div>
                            <div class="text-muted small">Home</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 text-center">
                    <div class="vs-text">
                        <span class="badge bg-light text-dark px-3 py-2 fs-6">VS</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="text-end me-3">
                            <div class="fw-bold">${match.awayTeam.shortName || match.awayTeam.name}</div>
                            <div class="text-muted small">Away</div>
                        </div>
                        <img src="${awayTeamCrest}" alt="${match.awayTeam.name}" class="team-crest" onerror="this.src='${getTeamIcon(match.awayTeam.name)}'">
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 text-end">
                    ${buttonHtml}
                </div>
            </div>
        </div>
        `;
        container.appendChild(matchCard);
    });
}

function getStatusClass(status) {
    if (status === 'POSTPONED') return 'bg-warning';
    return 'bg-success';
}

function getStatusText(status) {
    if (status === 'POSTPONED') return 'Postponed';
    return 'Available';
}

function getTeamIcon(teamName) {
    return `data:image/svg+xml;base64,${btoa(`
        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="32" height="32" rx="16" fill="#f8f9fa"/>
            <text x="50%" y="50%" font-family="Arial" font-size="12" fill="#6c757d" text-anchor="middle" dy=".3em">${teamName.charAt(0)}</text>
        </svg>
    `)}`;
}

function updateStats(matches) {
    const total = matches.length;
    const live = matches.filter(m => m.status === 'LIVE' || m.status === 'IN_PLAY').length;
    const upcoming = matches.filter(m => m.status === 'SCHEDULED').length;
    document.getElementById('total-matches').textContent = total;
    document.getElementById('live-matches').textContent = live;
    document.getElementById('upcoming-matches').textContent = upcoming;
}

function hideLoading() {
    if (allMatches.length === 0) {
        showEmptyState();
    } else {
        document.getElementById('loading-state').classList.add('d-none');
        document.getElementById('matches-container').classList.remove('d-none');
    }
}

function showLoading() {
    document.getElementById('loading-state').classList.remove('d-none');
    document.getElementById('error-state').classList.add('d-none');
    document.getElementById('empty-state').classList.add('d-none');
    document.getElementById('no-filter-results').classList.add('d-none');
    document.getElementById('matches-container').classList.add('d-none');
}

function showError() {
    document.getElementById('loading-state').classList.add('d-none');
    document.getElementById('error-state').classList.remove('d-none');
    document.getElementById('empty-state').classList.add('d-none');
    document.getElementById('no-filter-results').classList.add('d-none');
    document.getElementById('matches-container').classList.add('d-none');
}

function showEmptyState() {
    document.getElementById('loading-state').classList.add('d-none');
    document.getElementById('error-state').classList.add('d-none');
    document.getElementById('empty-state').classList.remove('d-none');
    document.getElementById('no-filter-results').classList.add('d-none');
    document.getElementById('matches-container').classList.add('d-none');
}

function refreshData() {
    showLoading();
    fetchPremierLeagueData();
}

function viewTickets(matchId) {
    window.location.href = `/stadium/${matchId}`;
}

function manageTickets(matchId) {
    window.location.href = `/admin/tickets/${matchId}`;
}
</script>

<style>
.full-width-page {
    width: 100%;
    margin: 0;
    padding: 0;
}

.full-width-content {
    width: 100%;
    padding-left: 15px;
    padding-right: 15px;
    margin-left: auto;
    margin-right: auto;
}

@media (min-width: 1200px) {
    .full-width-content {
        max-width: 100%;
        padding-left: 30px;
        padding-right: 30px;
    }
}

@media (min-width: 1400px) {
    .full-width-content {
        padding-left: 50px;
        padding-right: 50px;
    }
}

@media (min-width: 1600px) {
    .full-width-content {
        padding-left: 80px;
        padding-right: 80px;
    }
}

.premier-league-hero {
    background: linear-gradient(135deg, #38003c 0%, #00ff85 100%);
    width: 100%;
}

.competition-card {
    cursor: pointer;
    transition: all 0.2s ease;
}

.competition-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
}

.competition-icon {
    width: 45px;
    height: 45px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.bg-primary {
    background-color: #38003c;
}

.bg-primary-light {
    background-color: #e8d6e9;
}

.team-crest {
    width: 40px;
    height: 40px;
    object-fit: contain;
}

.btn-outline-primary {
    color: #38003c;
    border-color: #38003c;
}

.btn-outline-primary:hover {
    background-color: #38003c;
    border-color: #38003c;
    color: white;
}

.card {
    border-radius: 12px;
}

.btn-primary {
    background-color: #38003c;
    border-color: #38003c;
}

.btn-primary:hover {
    background-color: #2a002d;
    border-color: #2a002d;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-danger:hover {
    background-color: #bb2d3b;
    border-color: #bb2d3b;
}

.btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
}

.btn-warning:hover {
    background-color: #e0a800;
    border-color: #d39e00;
    color: #000;
}

.smaller {
    font-size: 0.75rem;
}

#matches-container {
    width: 100%;
}

#matches-list .card {
    width: 100%;
    margin-bottom: 15px;
}

#matches-list .card-body {
    padding: 20px;
}

#matches-list .row {
    width: 100%;
    margin: 0;
}

#matches-list .col-md-2,
#matches-list .col-md-4 {
    padding-left: 15px;
    padding-right: 15px;
}

.ui-datepicker {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    font-size: 14px;
}

.ui-datepicker-header {
    background: white;
    color: #38003c;
    border: none;
    border-radius: 6px 6px 0 0;
    padding: 20px 40px;
    position: relative;
    text-align: center;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ui-datepicker-title {
    font-weight: 600;
    color: #38003c;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.ui-datepicker-title .ui-datepicker-month {
    order: 1;
    margin-right: 5px;
}

.ui-datepicker-title .ui-datepicker-year {
    order: 2;
}

.ui-datepicker-prev, .ui-datepicker-next {
    cursor: pointer;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    background: white;
    border: 1px solid #dee2e6;
    font-size: 16px;
}

.ui-datepicker-prev {
    left: 10px;
}

.ui-datepicker-next {
    right: 10px;
}

.ui-datepicker-prev:hover, .ui-datepicker-next:hover {
    background: #f8f9fa;
    border-color: #38003c;
}

.ui-datepicker-prev .ui-icon, .ui-datepicker-next .ui-icon {
    display: none;
}

.ui-datepicker-prev:after {
    content: '←';
    font-weight: bold;
    color: #38003c;
}

.ui-datepicker-next:after {
    content: '→';
    font-weight: bold;
    color: #38003c;
}

.ui-datepicker-calendar {
    margin-top: 10px;
}

.ui-datepicker-calendar th {
    color: #38003c;
    font-weight: 600;
    padding: 8px;
    font-size: 13px;
}

.ui-datepicker-calendar td {
    padding: 3px;
}

.ui-datepicker-calendar td a {
    text-align: center;
    padding: 8px 6px;
    border-radius: 4px;
    color: #495057;
    text-decoration: none;
    display: block;
    font-size: 13px;
}

.ui-datepicker-calendar td a:hover {
    background: #f8f9fa;
    color: #38003c;
}

.ui-datepicker-calendar td a.ui-state-active {
    background: #38003c;
    color: white;
}

.ui-datepicker-calendar td.has-matches a {
    position: relative;
    color: #38003c;
    font-weight: 500;
}

.ui-datepicker-calendar td.has-matches a::after {
    content: '';
    position: absolute;
    bottom: 2px;
    left: 50%;
    transform: translateX(-50%);
    width: 4px;
    height: 4px;
    background-color: #00ff85;
    border-radius: 50%;
}

.ui-datepicker-today a {
    background: #e7f1ff;
    border: 1px solid #0d6efd;
    color: #0d6efd;
}

.ui-datepicker-buttonpane {
    background: white;
    border-top: 1px solid #dee2e6;
    padding: 10px;
    margin-top: 10px;
}

.ui-datepicker-buttonpane button {
    background: #38003c;
    color: white;
    border: none;
    padding: 6px 15px;
    border-radius: 4px;
    cursor: pointer;
    margin: 0 5px;
    font-size: 13px;
}

.ui-datepicker-buttonpane button:hover {
    background: #2a002d;
}

.ui-datepicker-close {
    background: #dc3545 !important;
}

.ui-datepicker-close:hover {
    background: #bb2d3b !important;
}
</style>
@endsection