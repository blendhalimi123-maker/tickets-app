@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px;">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">League Standings</h4>
                    <span id="league-name" class="badge bg-light text-dark">Premier League</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Pos</th>
                                    <th>Team</th>
                                    <th class="text-center">P</th>
                                    <th class="text-center">W</th>
                                    <th class="text-center">D</th>
                                    <th class="text-center">L</th>
                                    <th class="text-center">GD</th>
                                    <th class="text-center">Pts</th>
                                </tr>
                            </thead>
                            <tbody id="standings-body">
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status"></div>
                                        <p class="mt-2">Fetching live data from API...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-muted small">
                    Last updated: <span id="update-time">-</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const API_KEY = '76fa58e62936408ca8d3dbb65e50c517';
        const LEAGUE_CODE = 'PL'; 
        const URL = `https://api.football-data.org/v4/competitions/${LEAGUE_CODE}/standings`;

        async function loadStandings() {
            try {
                const response = await fetch(URL, {
                    method: 'GET',
                    headers: {
                        'X-Auth-Token': API_KEY
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP Error: ${response.status}`);
                }

                const data = await response.json();
                
                document.getElementById('league-name').innerText = data.competition.name;
                
                const tableData = data.standings[0].table;
                renderTable(tableData);
                
                document.getElementById('update-time').innerText = new Date().toLocaleTimeString();

            } catch (error) {
                console.error("API Fetch Error:", error);
                document.getElementById('standings-body').innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center text-danger">
                            <i class="fas fa-exclamation-triangle"></i> 
                            Failed to load data. You may have reached your 10-request-per-minute limit.
                        </td>
                    </tr>`;
            }
        }

        function renderTable(teams) {
            const tbody = document.getElementById('standings-body');
            let html = '';

            teams.forEach(item => {
                html += `
                    <tr>
                        <td class="fw-bold">${item.position}</td>
                        <td>
                            <img src="${item.team.crest}" alt="${item.team.name}" style="width: 25px; margin-right: 10px;">
                            <span class="fw-bold">${item.team.name}</span>
                        </td>
                        <td class="text-center">${item.playedGames}</td>
                        <td class="text-center text-success">${item.won}</td>
                        <td class="text-center text-warning">${item.draw}</td>
                        <td class="text-center text-danger">${item.lost}</td>
                        <td class="text-center">${item.goalDifference > 0 ? '+' + item.goalDifference : item.goalDifference}</td>
                        <td class="text-center"><strong>${item.points}</strong></td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
        }

        loadStandings();
    });
</script>
@endsection