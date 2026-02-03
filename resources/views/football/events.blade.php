@extends('layouts.app')

@section('content')
    <div class="container-xl standings-page py-2">
        <div class="standings-hero mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3">
                <div>
                    <h1 class="h3 fw-bold mb-1">League Standings</h1>
                    <div class="standings-subtitle">Star teams to save them as favorites.</div>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <span id="league-name" class="badge standings-league">La Liga</span>
                    <span class="badge standings-updated">Updated: <span id="update-time">-</span></span>
                </div>
            </div>

            <div class="standings-toolbar mt-3">
                <div class="input-group input-group-sm standings-search">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="team-search" placeholder="Search team...">
                </div>
                <div class="form-check form-switch standings-favs">
                    <input class="form-check-input" type="checkbox" id="favorites-only">
                    <label class="form-check-label" for="favorites-only">Favorites only</label>
                </div>
            </div>
        </div>

        <div class="card standings-card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive standings-table-wrap">
                    <table class="table standings-table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="pos-col">Pos</th>
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
                                    <div class="mt-2 text-muted">Loading standings...</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const standingsUrl = '/api/football/la-liga/standings';
            const favoritesUrl = '/favorite-teams';
            const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
            const searchEl = document.getElementById('team-search');
            const favoritesOnlyEl = document.getElementById('favorites-only');

            let tableRows = [];
            let favoritesSet = new Set();

            function escapeHtml(unsafe) {
                return String(unsafe)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/\"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function rowClass(pos) {
                const p = parseInt(pos, 10);
                if (!Number.isFinite(p)) return '';
                if (p <= 4) return 'rank-cl';
                if (p <= 6) return 'rank-el';
                if (p >= 18) return 'rank-rel';
                return '';
            }

            async function loadEvents() {
                try {
                    const res = await fetch(standingsUrl, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
                    if (!res.ok) throw new Error('Network response was not ok');
                    const data = await res.json();
                    const name = (data.competition && (data.competition.name || data.competition.code)) || 'La Liga';
                    document.getElementById('league-name').textContent = name;
                    tableRows = Array.isArray(data.table) ? data.table : [];
                    renderStandings(tableRows);
                    document.getElementById('update-time').textContent = new Date().toLocaleString();
                    return;

                    const events = data.data || [];
                    if (!events.length) {
                        try {
                            const roundId = 373230;
                            const sres = await fetch(`/api/sportmonks/standings/${roundId}`, { credentials: 'same-origin' });
                            if (sres.ok) {
                                const sjson = await sres.json();
                                const standings = sjson.data || [];
                                if (standings.length) {
                                    renderStandings(standings);
                                    document.getElementById('update-time').innerText = new Date().toLocaleTimeString();

                                    try {
                                        const prem = await fetch('/api/football/premier-league', { credentials: 'same-origin' });
                                        if (prem.ok) {
                                            const pjson = await prem.json();
                                            const pmatches = (pjson.matches || []).slice(0, 6);
                                            if (pmatches.length) {
                                                renderScheduledMatches(pmatches);
                                            }
                                        }
                                    } catch (pmErr) {
                                        console.warn('Failed to fetch Premier League schedule', pmErr);
                                    }

                                    return;
                                }
                            }
                        } catch (sErr) {
                            console.warn('Standings fetch failed', sErr);
                        }

                        try {
                            const r = await fetch('/api/football/premier-league', { credentials: 'same-origin' });
                            if (r.ok) {
                                const js = await r.json();
                                const matches = js.matches || [];
                                if (matches.length) {
                                    renderMatches(matches);
                                    document.getElementById('update-time').innerText = new Date().toLocaleTimeString();
                                    return;
                                }
                            }
                        } catch (fallbackErr) {
                            console.warn('Fallback matches fetch failed', fallbackErr);
                        }

                        document.getElementById('standings-body').innerHTML = `
                            <tr><td colspan="8" class="text-center py-5">No live events right now.</td></tr>`;
                        return;
                    }

                    renderEvents(events);
                    document.getElementById('update-time').innerText = new Date().toLocaleTimeString();
                } catch (err) {
                    document.getElementById('standings-body').innerHTML = `
                        <tr>
                            <td colspan="8" class="text-center text-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                Failed to load standings.
                            </td>
                        </tr>`;
                }
            }

            function escapeAttr(s) {
                return String(s || '').replace(/"/g, '&quot;').replace(/'/g, "&#39;");
            }

            function teamStarHtml(teamId, teamName, crest) {
                const id = teamId == null ? '' : String(teamId);
                const isFav = favoritesSet.has(id);
                const cls = isFav ? 'text-warning' : 'text-muted';
                return ` <a href="#" class="team-fav" data-team-id="${escapeAttr(id)}" data-team-name="${escapeAttr(teamName)}" data-crest="${escapeAttr(crest)}" title="Toggle favorite">
                            <i class="fas fa-star ${cls}"></i>
                        </a>`;
            }

            const subscriptions = {};
            const teamSubscriptions = {};
            function subscribeToTeamChannel(teamId) {
                if (typeof window.Echo === 'undefined') return;
                if (!teamId) return;
                if (teamSubscriptions[teamId]) return;
                try {
                    teamSubscriptions[teamId] = window.Echo.private(`team.${teamId}`)
                        .listen('TeamUpdated', (e) => {
                            showToast(e.message || 'Update for your favorite team');
                        });
                } catch (err) {
                    console.warn('Subscribe to team channel failed', err);
                }
            }

            function unsubscribeFromTeamChannel(teamId) {
                if (typeof window.Echo === 'undefined') return;
                if (!teamId) return;
                if (!teamSubscriptions[teamId]) return;
                try {
                    window.Echo.leave(`team.${teamId}`);
                    delete teamSubscriptions[teamId];
                } catch (err) {
                    console.warn('Unsubscribe from team channel failed', err);
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

            async function loadFavoriteTeams() {
                if (!isAuthenticated) return;
                try {
                    const r = await fetch(favoritesUrl, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
                    if (!r.ok) return;
                    const j = await r.json();
                    favoritesSet = new Set((j.favorites || []).map(String));
                    // subscribe to team channels for websocket notifications
                    for (const id of favoritesSet) {
                        subscribeToTeamChannel(id);
                    }
                } catch (e) {
                    console.warn('Failed to load favorite teams', e);
                }
            }

            async function toggleTeamFav(teamId, name, crest, el) {
                if (!teamId) return;
                try {
                    const res = await fetch(`/favorite-teams/${teamId}`, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ name: decodeURIComponent(name || ''), crest: decodeURIComponent(crest || '') })
                    });
                    if (!res.ok) throw new Error('Network response not ok');
                    const j = await res.json();
                    if (j.status === 'favorited') {
                        favoritesSet.add(String(teamId));
                        subscribeToTeamChannel(teamId);
                        el.classList.remove('text-muted'); el.classList.add('text-warning');
                        showToast(`<strong>${escapeHtml(decodeURIComponent(name || ''))}</strong> added to favorite teams`);
                        window.dispatchEvent(new CustomEvent('favorite-updated', { detail: { teamId: teamId, status: 'favorited', team: j.team || null } }));
                    } else if (j.status === 'unfavorited') {
                        favoritesSet.delete(String(teamId));
                        unsubscribeFromTeamChannel(teamId);
                        el.classList.remove('text-warning'); el.classList.add('text-muted');
                        showToast(`<strong>${escapeHtml(decodeURIComponent(name || ''))}</strong> removed from favorite teams`);
                        window.dispatchEvent(new CustomEvent('favorite-updated', { detail: { teamId: teamId, status: 'unfavorited' } }));
                    }
                } catch (e) {
                    console.warn('Failed to toggle team favorite', e);
                }
            }

            document.addEventListener('click', function(ev) {
                const a = ev.target.closest && ev.target.closest('.team-fav');
                if (!a) return;
                ev.preventDefault();
                const teamId = a.dataset.teamId;
                const name = a.dataset.teamName;
                const crest = a.dataset.crest;
                const icon = a.querySelector('i');
                toggleTeamFav(teamId, name, crest, icon);
            });

            function renderEvents(items) {
                const tbody = document.getElementById('standings-body');
                let html = '';

                items.forEach(ev => {
                    const home = (ev.participants || []).find(p => p.type === 'home') || ev.localTeam || {};
                    const away = (ev.participants || []).find(p => p.type === 'visitor') || ev.visitorTeam || {};
                    const score = (ev.scores && ev.scores.ft) ? `${ev.scores.ft.home || 0} - ${ev.scores.ft.away || 0}` : (ev.time || ev.status || '0 - 0');
                    const league = ev.league ? ev.league.data?.name || '' : (ev.league?.name || '');
                    const hid = home.id || home.team_id || home.data?.id || home._id || home.id_team || '';
                    const aid = away.id || away.team_id || away.data?.id || away._id || away.id_team || '';
                    html += `
                        <tr>
                            <td class="fw-bold">${league}</td>
                            <td>
                                <img src="${home.image_path || ''}" style="width:25px; margin-right:8px;" onerror="this.style.display='none'">${home.name || home.short_code || 'Home'}${teamStarHtml(hid, home.name || '', home.image_path || '')}
                            </td>
                            <td class="text-center"><strong>${score}</strong></td>
                            <td>
                                ${away.name || away.short_code || 'Away'}${teamStarHtml(aid, away.name || '', away.image_path || '')}
                                <img src="${away.image_path || ''}" style="width:25px; margin-left:8px;" onerror="this.style.display='none'">
                            </td>
                            <td class="text-end small text-muted">${ev.time || ev.status || ''}</td>
                        </tr>`;
                });

                tbody.innerHTML = html;
            }

            function renderMatches(matches) {
                const tbody = document.getElementById('standings-body');
                let html = '';

                matches.forEach((m, idx) => {
                    const home = m.homeTeam || (m.teams && m.teams.home) || {};
                    const away = m.awayTeam || (m.teams && m.teams.away) || {};
                    const utc = m.utcDate || m.match_date || m.date || null;
                    const when = utc ? new Date(utc).toLocaleString() : (m.status || 'TBD');

                    html += `
                        <tr>
                            <td class="fw-bold">${idx + 1}</td>
                            <td>
                                ${home.name || home.shortName || 'Home'}${teamStarHtml(home.id || home.apiTeamId || home.team_id || '', home.name || home.shortName || '', home.crest || home.logo || '')}
                            </td>
                            <td class="text-center"><strong>${m.score && m.score.fullTime ? ((m.score.fullTime.homeTeam || 0) + ' - ' + (m.score.fullTime.awayTeam || 0)) : 'vs'}</strong></td>
                            <td>
                                ${away.name || away.shortName || 'Away'}${teamStarHtml(away.id || away.apiTeamId || away.team_id || '', away.name || away.shortName || '', away.crest || away.logo || '')}
                            </td>
                            <td class="text-end small text-muted">${when}</td>
                        </tr>`;
                });

                tbody.innerHTML = html;
            }

            function renderStandings(table) {
                const tbody = document.getElementById('standings-body');
                if (!Array.isArray(table) || table.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="8" class="text-center py-5 text-muted">No standings available.</td></tr>`;
                    return;
                }

                const q = (searchEl?.value || '').trim().toLowerCase();
                const onlyFav = !!favoritesOnlyEl?.checked;

                const filtered = table.filter((row) => {
                    const team = row.team || {};
                    const idStr = team.id != null ? String(team.id) : '';
                    const nameStr = team.name ? String(team.name) : '';
                    if (q && !nameStr.toLowerCase().includes(q)) return false;
                    if (onlyFav) return idStr && favoritesSet.has(idStr);
                    return true;
                });

                if (filtered.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="8" class="text-center py-5 text-muted">No teams found.</td></tr>`;
                    return;
                }

                let html = '';
                filtered.forEach((row) => {
                    const pos = row.position ?? '';
                    const team = row.team || {};
                    const teamId = team.id != null ? String(team.id) : '';
                    const teamName = team.name || '';
                    const crest = team.crest || team.crestUrl || '';
                    const fav = teamId && favoritesSet.has(teamId);

                    html += `
                        <tr class="${rowClass(pos)}">
                            <td class="pos-col"><span class="pos-pill">${escapeHtml(pos)}</span></td>
                            <td>
                                <div class="team-cell">
                                    <button type="button" class="favorite-team-btn ${fav ? 'is-favorited' : ''}" data-team-id="${escapeHtml(teamId)}" data-team-name="${encodeURIComponent(teamName)}" data-team-crest="${encodeURIComponent(crest)}" aria-label="Favorite team">
                                        <i class="${fav ? 'fas' : 'far'} fa-star"></i>
                                    </button>
                                    <img class="team-crest" src="${escapeHtml(crest)}" onerror="this.style.display='none'">
                                    <span class="team-name">${escapeHtml(teamName)}</span>
                                </div>
                            </td>
                            <td class="text-center">${escapeHtml(row.playedGames ?? '')}</td>
                            <td class="text-center">${escapeHtml(row.won ?? '')}</td>
                            <td class="text-center">${escapeHtml(row.draw ?? '')}</td>
                            <td class="text-center">${escapeHtml(row.lost ?? '')}</td>
                            <td class="text-center">${escapeHtml(row.goalDifference ?? '')}</td>
                            <td class="text-center fw-bold">${escapeHtml(row.points ?? '')}</td>
                        </tr>`;
                });

                tbody.innerHTML = html;
            }

            function renderScheduledMatches(matches) {
                const tbody = document.getElementById('standings-body');
                let html = tbody.innerHTML;

                html += `
                    <tr><td colspan="8"><hr></td></tr>`;

                matches.forEach((m) => {
                    const homeObj = m.homeTeam || (m.home || {});
                    const awayObj = m.awayTeam || (m.away || {});
                    const home = homeObj.name || homeObj.shortName || 'Home';
                    const away = awayObj.name || awayObj.shortName || 'Away';
                    const utc = m.utcDate || m.match_date || m.date || null;
                    const when = utc ? new Date(utc).toLocaleString() : (m.status || 'TBD');

                    html += `
                        <tr class="table-secondary">
                            <td></td>
                            <td>${home}${teamStarHtml(homeObj.id || homeObj.apiTeamId || homeObj.team_id || '', home, homeObj.crest || homeObj.logo || '')}</td>
                            <td class="text-center"><strong>vs</strong></td>
                            <td>${away}${teamStarHtml(awayObj.id || awayObj.apiTeamId || awayObj.team_id || '', away, awayObj.crest || awayObj.logo || '')}</td>
                            <td class="text-end small text-muted">${when}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>`;
                });

                tbody.innerHTML = html;
            }

            document.getElementById('standings-body').addEventListener('click', async function (e) {
                const btn = e.target.closest('.favorite-team-btn');
                if (!btn) return;

                const teamId = btn.dataset.teamId;
                if (!teamId) return;

                if (!isAuthenticated) {
                    window.location.href = '/login';
                    return;
                }

                btn.disabled = true;
                try {
                    const name = btn.dataset.teamName ? decodeURIComponent(btn.dataset.teamName) : '';
                    const crest = btn.dataset.teamCrest ? decodeURIComponent(btn.dataset.teamCrest) : '';
                    const res = await fetch(`/favorite-teams/${teamId}`, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ name, crest })
                    });

                    const text = await res.text().catch(() => '');
                    let json = {};
                    try { json = text ? JSON.parse(text) : {}; } catch (e) { json = {}; }

                    if (!res.ok) {
                        console.error('Favorite-team toggle failed', res.status, text);
                        try { showToast('Failed to toggle favorite team'); } catch (e) {}
                        return;
                    }

                    if (json.status === 'favorited') {
                        favoritesSet.add(String(teamId));
                    } else if (json.status === 'unfavorited') {
                        favoritesSet.delete(String(teamId));
                    }

                    renderStandings(tableRows);
                } catch (err) {
                } finally {
                    btn.disabled = false;
                }
            });

            searchEl?.addEventListener('input', function () {
                renderStandings(tableRows);
            });

            favoritesOnlyEl?.addEventListener('change', function () {
                renderStandings(tableRows);
            });

            loadFavoriteTeams().then(loadEvents);
        });
    </script>

    <style>
        .standings-hero{
            padding: 22px;
            border-radius: 18px;
            background:
                radial-gradient(circle at 18% 0%, rgba(0,255,133,0.35), transparent 45%),
                linear-gradient(135deg, #38003c 0%, #0f172a 75%);
            color: #fff;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.18);
        }

        .standings-hero .input-group-text,
        .standings-hero .form-control{
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.22);
            color: #fff;
        }

        .standings-hero .form-control::placeholder{
            color: rgba(255,255,255,0.65);
        }

        .standings-hero .form-check-label{
            color: rgba(255,255,255,0.88);
        }

        .standings-subtitle{
            color: rgba(255,255,255,0.82);
            font-size: 0.95rem;
        }

        .standings-league{
            background: rgba(255,255,255,0.10);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.22);
            padding: 0.45rem 0.75rem;
            border-radius: 999px;
            font-weight: 700;
        }

        .standings-updated{
            background: rgba(0,0,0,0.22);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.18);
            padding: 0.45rem 0.75rem;
            border-radius: 999px;
            font-weight: 600;
        }

        .standings-toolbar{
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }

        .standings-search{
            max-width: 340px;
        }

        .standings-favs{
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .standings-card{
            border: 0;
            border-radius: 18px;
            overflow: hidden;
        }

        .standings-table-wrap{
            max-height: 70vh;
            overflow: auto;
        }

        .standings-table thead th{
            position: sticky;
            top: 0;
            z-index: 2;
            background: #f8fafc;
            font-size: 0.82rem;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            color: #334155;
            border-bottom: 1px solid rgba(15,23,42,0.10);
            padding: 14px 12px;
        }

        .standings-table td{
            padding: 14px 12px;
            border-bottom: 1px solid rgba(15,23,42,0.06);
        }

        .standings-table tbody tr{
            transition: background-color 0.18s ease;
        }

        .standings-table tbody tr:hover{
            background: rgba(15,23,42,0.02);
        }

        .pos-col{
            width: 90px;
        }

        .pos-pill{
            width: 38px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            font-weight: 800;
            font-size: 0.85rem;
            background: #f1f5f9;
            color: #0f172a;
        }

        .team-cell{
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 240px;
        }

        .team-crest{
            width: 26px;
            height: 26px;
            object-fit: contain;
            filter: drop-shadow(0 1px 1px rgba(0,0,0,0.12));
        }

        .team-name{
            font-weight: 700;
            color: #0f172a;
        }

        .favorite-team-btn{
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            background: transparent;
            color: #94a3b8;
            padding: 0;
            line-height: 1;
            transition: transform 0.15s ease, color 0.15s ease;
        }

        .favorite-team-btn:hover{
            transform: translateY(-1px);
            color: #f5c518;
        }

        .favorite-team-btn.is-favorited{
            color: #f5c518;
        }

        .favorite-team-btn:disabled{
            opacity: 0.55;
            transform: none;
        }

        .rank-cl{
            background: rgba(0,255,133,0.05);
        }

        .rank-cl .pos-pill{
            background: rgba(0,255,133,0.22);
            color: #064e3b;
        }

        .rank-el{
            background: rgba(59,130,246,0.04);
        }

        .rank-el .pos-pill{
            background: rgba(59,130,246,0.14);
            color: #1d4ed8;
        }

        .rank-rel{
            background: rgba(239,68,68,0.05);
        }

        .rank-rel .pos-pill{
            background: rgba(239,68,68,0.16);
            color: #991b1b;
        }
    </style>
@endsection
