@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    {{-- Hero Header with Premier League Theme --}}
    <div class="premier-league-hero py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-4 fw-bold text-white mb-3">
                        <i class="fas fa-futbol me-3"></i>Match Schedules
                    </h1>
                    <p class="lead text-white mb-0">
                        Real-time fixtures, scores, and ticket information
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <button onclick="refreshData()" class="btn btn-light btn-lg shadow-sm">
                        <i class="fas fa-sync-alt me-2"></i>Refresh Live
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Competition Selector Cards --}}
    <div class="container py-4">
        <div class="row g-4">
            {{-- Champions League Card --}}
            <div class="col-lg-4">
                <div class="card competition-card champions-league h-100 border-0 shadow-sm" onclick="switchTab('champions_league')">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="competition-icon bg-purple">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="card-title fw-bold mb-0">Champions League</h5>
                                <small class="text-muted">UEFA</small>
                            </div>
                        </div>
                        <p class="card-text text-muted mb-4">Europe's premier club competition featuring top teams.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-purple-light text-purple" id="champions-count">0 matches</span>
                            <button class="btn btn-sm btn-purple">View <i class="fas fa-arrow-right ms-1"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Premier League Card --}}
            <div class="col-lg-4">
                <div class="card competition-card premier-league h-100 border-0 shadow-sm" onclick="switchTab('premier_league')">
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
                        <p class="card-text text-muted mb-4">The most watched football league in the world.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-primary-light text-primary" id="premier-count">0 matches</span>
                            <button class="btn btn-sm btn-primary">View <i class="fas fa-arrow-right ms-1"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- World Cup Card --}}
            <div class="col-lg-4">
                <div class="card competition-card world-cup h-100 border-0 shadow-sm" onclick="switchTab('world_cup')">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="competition-icon bg-success">
                                <i class="fas fa-globe-americas"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="card-title fw-bold mb-0">World Cup</h5>
                                <small class="text-muted">FIFA</small>
                            </div>
                        </div>
                        <p class="card-text text-muted mb-4">The greatest sporting event on earth.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-success-light text-success" id="worldcup-count">0 matches</span>
                            <button class="btn btn-sm btn-success">View <i class="fas fa-arrow-right ms-1"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Active Competition Section --}}
    <div class="container py-4">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-white border-0 py-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-0" id="active-competition-title">Select a Competition</h3>
                        <p class="text-muted mb-0" id="active-competition-subtitle">Click on a competition card above to view matches</p>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-sort me-2"></i>Sort by
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="sortMatches('date')">Date (Soonest)</a></li>
                            <li><a class="dropdown-item" href="#" onclick="sortMatches('league')">League</a></li>
                            <li><a class="dropdown-item" href="#" onclick="sortMatches('status')">Match Status</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-0">
                {{-- Loading State --}}
                <div id="loading-state" class="text-center py-5">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading football data...</p>
                </div>

                {{-- Error State --}}
                <div id="error-state" class="text-center py-5 d-none">
                    <div class="py-5">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-4"></i>
                        <h4 class="mb-3">Unable to Load Data</h4>
                        <p class="text-muted mb-4">There was an issue fetching the latest match information.</p>
                        <button onclick="refreshData()" class="btn btn-primary">
                            <i class="fas fa-redo me-2"></i>Try Again
                        </button>
                    </div>
                </div>

                {{-- Empty State --}}
                <div id="empty-state" class="text-center py-5 d-none">
                    <div class="py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-4"></i>
                        <h4 class="mb-3">No Matches Found</h4>
                        <p class="text-muted">There are no upcoming matches for this competition.</p>
                    </div>
                </div>

                {{-- Matches Grid --}}
                <div id="matches-container" class="d-none">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3 ps-4">Date & Time</th>
                                    <th class="py-3">Home Team</th>
                                    <th class="py-3"></th>
                                    <th class="py-3">Away Team</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3 pe-4 text-end">Tickets</th>
                                </tr>
                            </thead>
                            <tbody id="matches-table-body">
                                <!-- Matches will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="container py-4">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-calendar-alt fa-2x text-primary mb-3"></i>
                        <h3 class="fw-bold" id="total-matches">0</h3>
                        <p class="text-muted mb-0">Total Matches</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-play-circle fa-2x text-success mb-3"></i>
                        <h3 class="fw-bold" id="live-matches">0</h3>
                        <p class="text-muted mb-0">Live Now</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-clock fa-2x text-warning mb-3"></i>
                        <h3 class="fw-bold" id="upcoming-matches">0</h3>
                        <p class="text-muted mb-0">Upcoming</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Back Button --}}
    <div class="container py-5">
        <div class="text-center">
            <a href="{{ url('/') }}" class="btn btn-lg btn-outline-primary px-5">
                <i class="fas fa-arrow-left me-2"></i>Back to Home
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    fetchAllCompetitions();
});

let allMatches = {};
let currentCompetition = null;

async function fetchAllCompetitions() {
    try {
        showLoading();
        const response = await fetch('/api/football/all');
        const data = await response.json();
        
        if (data.success) {
            allMatches = data.competitions;
            updateCompetitionCards();
            showInitialView();
            hideError();
        } else {
            showError();
        }
    } catch (error) {
        console.error('Error:', error);
        showError();
    }
}

function updateCompetitionCards() {
    // Update match counts on cards
    if (allMatches.champions_league) {
        document.getElementById('champions-count').textContent = 
            `${allMatches.champions_league.count} matches`;
    }
    if (allMatches.premier_league) {
        document.getElementById('premier-count').textContent = 
            `${allMatches.premier_league.count} matches`;
    }
    if (allMatches.world_cup) {
        document.getElementById('worldcup-count').textContent = 
            `${allMatches.world_cup.count} matches`;
    }
}

function switchTab(compType) {
    currentCompetition = compType;
    const competition = allMatches[compType];
    
    if (!competition || !competition.matches || competition.matches.length === 0) {
        showEmptyState();
        return;
    }
    
    // Update active title
    document.getElementById('active-competition-title').textContent = competition.name;
    document.getElementById('active-competition-subtitle').textContent = 
        `${competition.count} matches found`;
    
    // Render matches
    renderMatches(competition.matches);
    updateStats(competition.matches);
    
    // Show matches container
    document.getElementById('loading-state').classList.add('d-none');
    document.getElementById('empty-state').classList.add('d-none');
    document.getElementById('error-state').classList.add('d-none');
    document.getElementById('matches-container').classList.remove('d-none');
}

function renderMatches(matches) {
    const tbody = document.getElementById('matches-table-body');
    tbody.innerHTML = '';
    
    // Sort by date
    matches.sort((a, b) => new Date(a.utcDate) - new Date(b.utcDate));
    
    matches.forEach(match => {
        const matchDate = new Date(match.utcDate);
        const formattedDate = matchDate.toLocaleDateString('en-US', {
            weekday: 'short',
            month: 'short',
            day: 'numeric'
        });
        const formattedTime = matchDate.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit'
        });
        
        const statusClass = getStatusClass(match.status);
        const statusText = getStatusText(match.status);
        
        const homeTeamCrest = match.homeTeam.crest || getTeamIcon(match.homeTeam.name);
        const awayTeamCrest = match.awayTeam.crest || getTeamIcon(match.awayTeam.name);
        
        const row = document.createElement('tr');
        row.className = 'match-row';
        row.innerHTML = `
            <td class="ps-4 align-middle">
                <div class="fw-bold">${formattedDate}</div>
                <small class="text-muted">${formattedTime}</small>
            </td>
            <td class="align-middle">
                <div class="d-flex align-items-center">
                    <img src="${homeTeamCrest}" 
                         alt="${match.homeTeam.name}" 
                         class="team-crest me-3"
                         onerror="this.src='${getTeamIcon(match.homeTeam.name)}'">
                    <span class="fw-medium">${match.homeTeam.shortName || match.homeTeam.name}</span>
                </div>
            </td>
            <td class="align-middle text-center">
                ${match.score?.fullTime ? `
                    <div class="score-display">
                        <span class="badge bg-dark text-white fs-6 px-3 py-2">
                            ${match.score.fullTime.home} - ${match.score.fullTime.away}
                        </span>
                    </div>
                ` : `
                    <div class="vs-text text-muted">vs</div>
                `}
            </td>
            <td class="align-middle">
                <div class="d-flex align-items-center">
                    <span class="fw-medium me-3">${match.awayTeam.shortName || match.awayTeam.name}</span>
                    <img src="${awayTeamCrest}" 
                         alt="${match.awayTeam.name}" 
                         class="team-crest"
                         onerror="this.src='${getTeamIcon(match.awayTeam.name)}'">
                </div>
            </td>
            <td class="align-middle">
                <span class="badge ${statusClass}">${statusText}</span>
            </td>
            <td class="pe-4 align-middle text-end">
                <button class="btn btn-sm btn-primary px-3" onclick="viewTickets(${match.id})">
                    <i class="fas fa-ticket-alt me-1"></i>Tickets
                </button>
            </td>
        `;
        
        tbody.appendChild(row);
    });
}

function getStatusClass(status) {
    switch(status) {
        case 'SCHEDULED': return 'bg-info';
        case 'LIVE': return 'bg-success';
        case 'IN_PLAY': return 'bg-success';
        case 'FINISHED': return 'bg-secondary';
        case 'POSTPONED': return 'bg-warning';
        default: return 'bg-secondary';
    }
}

function getStatusText(status) {
    switch(status) {
        case 'SCHEDULED': return 'Upcoming';
        case 'LIVE': return 'Live';
        case 'IN_PLAY': return 'Live';
        case 'FINISHED': return 'Finished';
        case 'POSTPONED': return 'Postponed';
        default: return status;
    }
}

function getTeamIcon(teamName) {
    return `data:image/svg+xml;base64,${btoa(`
        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="40" height="40" rx="20" fill="#f8f9fa"/>
            <text x="50%" y="50%" font-family="Arial" font-size="14" fill="#6c757d" 
                  text-anchor="middle" dy=".3em">${teamName.charAt(0)}</text>
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

function showInitialView() {
    document.getElementById('loading-state').classList.add('d-none');
    document.getElementById('active-competition-title').textContent = 'Select a Competition';
    document.getElementById('active-competition-subtitle').textContent = 'Click on a competition card above to view matches';
}

function showLoading() {
    document.getElementById('loading-state').classList.remove('d-none');
    document.getElementById('error-state').classList.add('d-none');
    document.getElementById('empty-state').classList.add('d-none');
    document.getElementById('matches-container').classList.add('d-none');
}

function showError() {
    document.getElementById('loading-state').classList.add('d-none');
    document.getElementById('error-state').classList.remove('d-none');
    document.getElementById('empty-state').classList.add('d-none');
    document.getElementById('matches-container').classList.add('d-none');
}

function showEmptyState() {
    document.getElementById('loading-state').classList.add('d-none');
    document.getElementById('error-state').classList.add('d-none');
    document.getElementById('empty-state').classList.remove('d-none');
    document.getElementById('matches-container').classList.add('d-none');
}

function hideError() {
    document.getElementById('error-state').classList.add('d-none');
}

function refreshData() {
    showLoading();
    fetchAllCompetitions();
}

function sortMatches(criteria) {
    if (!currentCompetition || !allMatches[currentCompetition]) return;
    
    const matches = [...allMatches[currentCompetition].matches];
    
    switch(criteria) {
        case 'date':
            matches.sort((a, b) => new Date(a.utcDate) - new Date(b.utcDate));
            break;
        case 'league':
            // You could sort by league if you have that data
            break;
        case 'status':
            const statusOrder = { 'LIVE': 1, 'IN_PLAY': 1, 'SCHEDULED': 2, 'FINISHED': 3, 'POSTPONED': 4 };
            matches.sort((a, b) => (statusOrder[a.status] || 5) - (statusOrder[b.status] || 5));
            break;
    }
    
    renderMatches(matches);
}

function viewTickets(matchId) {
    alert(`Viewing tickets for match ID: ${matchId}\nThis would redirect to ticket purchase page.`);
}
</script>

<style>
.premier-league-hero {
    background: linear-gradient(135deg, #38003c 0%, #00ff85 100%);
    background-size: cover;
    position: relative;
    overflow: hidden;
}

.premier-league-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iNTAiIGN5PSI1MCIgcj0iMjAiIHN0cm9rZT0icmdiYSgyNTUsIDI1NSwgMjU1LCAwLjEpIiBzdHJva2Utd2lkdGg9IjIiLz48L3N2Zz4=');
    opacity: 0.3;
}

.competition-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.competition-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.competition-card.champions-league:hover {
    border-color: #6f42c1;
}

.competition-card.premier-league:hover {
    border-color: #38003c;
}

.competition-card.world-cup:hover {
    border-color: #198754;
}

.competition-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.bg-purple { background-color: #6f42c1; }
.bg-purple-light { background-color: #e9d8fd; }
.btn-purple { background-color: #6f42c1; color: white; }
.btn-purple:hover { background-color: #59359a; color: white; }

.bg-primary { background-color: #38003c; }
.bg-primary-light { background-color: #e8d6e9; }
.btn-primary { background-color: #38003c; border-color: #38003c; }
.btn-primary:hover { background-color: #2a002d; border-color: #2a002d; }

.bg-success { background-color: #198754; }
.bg-success-light { background-color: #d1e7dd; }

.team-crest {
    width: 32px;
    height: 32px;
    object-fit: contain;
}

.match-row:hover {
    background-color: #f8f9fa;
}

.score-display .badge {
    font-family: 'Courier New', monospace;
}

.vs-text {
    font-weight: bold;
    font-size: 1.1rem;
}

.table th {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
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
</style>
@endsection