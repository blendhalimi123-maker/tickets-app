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
        // Fetch SportMonks in-play events from backend proxy
        const URL = '/api/sportmonks/inplay';

        async function loadEvents() {
            try {
                const res = await fetch(URL, { credentials: 'same-origin' });
                if (!res.ok) throw new Error('Network response was not ok');
                const data = await res.json();

                // SportMonks wraps results under `data` key
                const events = data.data || [];
                if (!events.length) {
                    document.getElementById('standings-body').innerHTML = `
                        <tr><td colspan="8" class="text-center py-5">No live events right now.</td></tr>`;
                    return;
                }

                renderEvents(events);
                document.getElementById('update-time').innerText = new Date().toLocaleTimeString();
            } catch (err) {
                console.error('Failed to load SportMonks events', err);
                document.getElementById('standings-body').innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center text-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            Failed to load live events.
                        </td>
                    </tr>`;
            }
        }

        function renderEvents(items) {
            const tbody = document.getElementById('standings-body');
            let html = '';

            items.forEach(ev => {
                const home = (ev.participants || []).find(p => p.type === 'home') || ev.localTeam || {};
                const away = (ev.participants || []).find(p => p.type === 'visitor') || ev.visitorTeam || {};
                const score = (ev.scores && ev.scores.ft) ? `${ev.scores.ft.home || 0} - ${ev.scores.ft.away || 0}` : (ev.time || ev.status || '0 - 0');
                const league = ev.league ? ev.league.data?.name || '' : (ev.league?.name || '');

                html += `
                    <tr>
                        <td class="fw-bold">${league}</td>
                        <td>
                            <img src="${home.image_path || ''}" style="width:25px; margin-right:8px;" onerror="this.style.display='none'">${home.name || home.short_code || 'Home'}
                        </td>
                        <td class="text-center"><strong>${score}</strong></td>
                        <td>
                            ${away.name || away.short_code || 'Away'}
                            <img src="${away.image_path || ''}" style="width:25px; margin-left:8px;" onerror="this.style.display='none'">
                        </td>
                        <td class="text-end small text-muted">${ev.time || ev.status || ''}</td>
                    </tr>`;
            });

            tbody.innerHTML = html;
        }

        loadEvents();
    });
</script>
@endsection