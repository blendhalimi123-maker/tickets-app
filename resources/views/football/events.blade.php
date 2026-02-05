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

            <div class="mt-3 standings-legend">
                <div class="card border-0 mt-2">
                    <div class="card-body p-3 small text-muted">
                        <div class="mb-1"><span class="legend-box legend-cl"></span> Promotion - Champions League (League
                            phase)</div>
                        <div class="mb-1"><span class="legend-box legend-el"></span> Promotion - Europa League (League
                            phase)</div>
                        <div class="mb-1"><span class="legend-box legend-conf"></span> Promotion - Conference League
                            (Qualification)</div>
                        <div class="mb-1"><span class="legend-box legend-rel"></span> Relegation - LaLiga2</div>
                        <div class="mt-2 small text-muted">If points are tied at the end of the competition, head-to-head
                            matches will be the tie-breaker.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="teamInfoModal" tabindex="-1" aria-labelledby="teamInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="teamInfoModalLabel">Team Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="teamInfoContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

            const nameToSportmonksId = {
                "FC Barcelona": "83",
                "Real Madrid": "3468",
                "Atlético Madrid": "10",
                "Atlético": "10",
                "Villarreal": "3477",
                "Real Betis": "19",
                "Sevilla FC": "20",
                "Real Sociedad": "28",
                "Athletic Bilbao": "32",
                "Athletic Club": "32",
                "Girona": "9355",
                "Valencia CF": "100",
                "Celta Vigo": "123",
                "Osasuna": "328",
                "Getafe": "230",
                "Espanyol": "225",
                "Mallorca": "101",
                "Rayo Vallecano": "243",
                "Alavés": "281",
                "Elche": "221",
                "Levante": "12",
                "Real Oviedo": "280"
            };

            function normalizeNameForMap(n) {
                if (!n) return '';
                return String(n)
                    .toLowerCase()
                    .normalize('NFKD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/\b(?:fc|cf|club|de|del|rcd|rc|ca|cd|ud|sd|balompie|futbol|football)\b/g, ' ')
                    .replace(/[^a-z0-9]/g, '');
            }
            const nameToSportmonksIdNormalized = {};
            Object.keys(nameToSportmonksId).forEach(k => {
                const nk = normalizeNameForMap(k);
                if (nk) nameToSportmonksIdNormalized[nk] = String(nameToSportmonksId[k]);
            });

            let tableRows = [];
            let favoritesSet = new Set();
            const TeamFavorites = window.TeamFavorites || null;

            function syncFavoritesFromStorage() {
                try {
                    if (TeamFavorites?.getFavoriteSet) {
                        favoritesSet = TeamFavorites.getFavoriteSet();
                        return;
                    }
                    const raw = window.localStorage?.getItem('favorite_teams_v1');
                    const parsed = raw ? JSON.parse(raw) : [];
                    favoritesSet = new Set(Array.isArray(parsed) ? parsed.map(String) : []);
                } catch (e) {
                    favoritesSet = new Set();
                }
            }

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
                    tableRows = Array.isArray(data.table) ? data.table : (Array.isArray(data.standings) ? data.standings : []);
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

                    document.getElementById('update-time').textContent = new Date().toLocaleString();
                } catch (err) {
                    console.error('Load error:', err);
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

            function resolveTeamId(obj) {
                if (!obj) return '';
                if (obj.data && obj.data.id != null) return String(obj.data.id);
                if (obj.team && obj.team.data && obj.team.data.id != null) return String(obj.team.data.id);
                if (obj.team && obj.team.id != null) return String(obj.team.id);
                if (obj.id != null) return String(obj.id);
                if (obj.api_id != null) return String(obj.api_id);
                if (obj.apiTeamId != null) return String(obj.apiTeamId);
                if (obj.team_id != null) return String(obj.team_id);
                if (obj._id != null) return String(obj._id);
                return '';
            }

            function getTeamId(objOrId, nameOverride) {
                let id = '';
                if (objOrId == null) id = '';
                else if (typeof objOrId === 'string' || typeof objOrId === 'number') id = String(objOrId);
                else id = resolveTeamId(objOrId);

                const namesToTry = [];
                if (nameOverride) namesToTry.push(nameOverride);
                if (objOrId && typeof objOrId === 'object') {
                    if (objOrId.name) namesToTry.push(objOrId.name);
                    if (objOrId.shortName) namesToTry.push(objOrId.shortName);
                    if (objOrId.short_code) namesToTry.push(objOrId.short_code);
                    if (objOrId.teamName) namesToTry.push(objOrId.teamName);
                    if (objOrId.team && typeof objOrId.team === 'object') {
                        if (objOrId.team.name) namesToTry.push(objOrId.team.name);
                        if (objOrId.team.shortName) namesToTry.push(objOrId.team.shortName);
                    }
                    if (objOrId.data && typeof objOrId.data === 'object') {
                        if (objOrId.data.name) namesToTry.push(objOrId.data.name);
                        if (objOrId.data.shortName) namesToTry.push(objOrId.data.shortName);
                    }
                }

                for (const name of namesToTry) {
                    if (!name) continue;
                    const exact = nameToSportmonksId[String(name).trim()];
                    if (exact) return String(exact);
                    const nk = normalizeNameForMap(name);
                    if (nk && nameToSportmonksIdNormalized[nk]) return String(nameToSportmonksIdNormalized[nk]);
                }

                if (!id) {
                    id = id || '';
                }

                return id || '';
            }

            function teamLinkHtml(teamId, teamName) {
                const id = teamId == null ? '' : String(teamId);
                const name = escapeHtml(teamName || '');
                if (!id) return `<span class="team-name">${name}</span>`;
                return `<a href="/squad/${encodeURIComponent(id)}" class="team-name">${name}</a>`;
            }

            const subscriptions = {};
            const teamSubscriptions = {};
            function subscribeToTeamChannel(teamId) {
                return;
            }

            function unsubscribeFromTeamChannel(teamId) {
                return;
            }

            function subscribeToGameChannel(apiId) {
                return;
            }

            function unsubscribeFromGameChannel(apiId) {
                return;
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
                syncFavoritesFromStorage();
                if (!isAuthenticated) return;
                try {
                    const r = await fetch(favoritesUrl, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
                    if (!r.ok) return;
                    const j = await r.json();
                    favoritesSet = new Set((j.favorites || []).map(String));
                    try {
                        TeamFavorites?.writeFavoriteTeamIds?.([...favoritesSet]);
                    } catch (e) { }
                    for (const id of favoritesSet) {
                        subscribeToTeamChannel(id);
                    }
                } catch (e) {
                    console.warn('Failed to load favorite teams', e);
                }
            }

            async function toggleTeamFav(teamId, name, crest, el) {
                if (!teamId) {
                    console.warn('No team ID provided');
                    return;
                }

                const idStr = String(teamId);
                const nowFav = !favoritesSet.has(idStr);
                try {
                    TeamFavorites?.setFavorited?.(idStr, nowFav);
                } catch (e) { }
                syncFavoritesFromStorage();
                if (nowFav) {
                    subscribeToTeamChannel(idStr);
                } else {
                    unsubscribeFromTeamChannel(idStr);
                }

                try {
                    el?.classList?.toggle('text-warning', nowFav);
                    el?.classList?.toggle('text-muted', !nowFav);
                } catch (e) { }

                try {
                    if (nowFav) {
                        showToast(`<strong>${escapeHtml(decodeURIComponent(name || ''))}</strong> added to favorite teams`);
                    } else {
                        showToast(`<strong>${escapeHtml(decodeURIComponent(name || ''))}</strong> removed from favorite teams`);
                    }
                } catch (e) { }

                if (!isAuthenticated) return;

                try {
                    await fetch(`/favorite-teams/${teamId}`, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ name: decodeURIComponent(name || ''), crest: decodeURIComponent(crest || '') })
                    });
                } catch (e) {
                }
            }

            function showTeamInfoModal(teamInfo) {
                console.log('Showing team info modal with data:', teamInfo);

                const modalEl = document.getElementById('teamInfoModal');
                if (!modalEl) {
                    console.error('Modal element not found');
                    return;
                }

                const content = document.getElementById('teamInfoContent');
                if (!content) {
                    console.error('Modal content element not found');
                    return;
                }

                if (!teamInfo) {
                    content.innerHTML = '<p class="text-muted">Team information not available.</p>';
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                    return;
                }

                const team = teamInfo;
                let html = '<div class="team-info-details">';

                if (team.crest) {
                    html += `<div class="text-center mb-4">
                                <img src="${escapeHtml(team.crest)}" alt="${escapeHtml(team.name || 'Team')}" class="img-fluid" style="max-width: 120px; max-height: 120px;">
                            </div>`;
                }

                html += `<h4 class="text-center mb-3 fw-bold">${escapeHtml(team.name || team.shortName || 'Unknown Team')}</h4>`;

                html += '<div class="row g-3">';

                if (team.shortName) {
                    html += `<div class="col-6"><strong>Short Name:</strong></div><div class="col-6">${escapeHtml(team.shortName)}</div>`;
                }

                if (team.tla) {
                    html += `<div class="col-6"><strong>TLA:</strong></div><div class="col-6">${escapeHtml(team.tla)}</div>`;
                }

                if (team.founded) {
                    html += `<div class="col-6"><strong>Founded:</strong></div><div class="col-6">${escapeHtml(String(team.founded))}</div>`;
                }

                if (team.venue) {
                    html += `<div class="col-6"><strong>Stadium:</strong></div><div class="col-6">${escapeHtml(team.venue)}</div>`;
                }

                if (team.address) {
                    html += `<div class="col-6"><strong>Address:</strong></div><div class="col-6">${escapeHtml(team.address)}</div>`;
                }

                if (team.clubColors) {
                    html += `<div class="col-6"><strong>Club Colors:</strong></div><div class="col-6">${escapeHtml(team.clubColors)}</div>`;
                }

                if (team.website) {
                    html += `<div class="col-12 mt-2"><a href="${escapeHtml(team.website)}" target="_blank" class="btn btn-sm btn-outline-primary w-100">
                                <i class="fas fa-external-link-alt me-1"></i> Visit Official Website
                            </a></div>`;
                }

                const teamId = team.id != null ? String(team.id) : '';
                const teamName = team.shortName || team.name || 'Team';
                const shortName = team.shortName || '';
                const tla = team.tla || '';

                if (teamId) {
                    html += `<div class="col-12 mt-2">
                                <a class="btn btn-sm btn-outline-primary w-100 team-next-action-btn js-team-next-action d-none"
                                   href="/team/${encodeURIComponent(teamId)}/fixtures"
                                   data-team-id="${escapeHtml(teamId)}"
                                   data-team-name="${escapeHtml(teamName)}"
                                   data-team-short-name="${escapeHtml(shortName)}"
                                   data-team-tla="${escapeHtml(tla)}">
                                   See Next ${escapeHtml(teamName)} Games
                                </a>
                            </div>`;
                }

                html += '</div>';

                html += '<hr>';
                html += '<div class="row g-3">';
                if (team.topScorerNow) {
                    const ts = team.topScorerNow;
                    const playerName = (ts.player && (ts.player.name || ts.player.fullName)) || (ts.playerName || ts.name) || 'Unknown';
                    const goals = ts.numberOfGoals ?? ts.goals ?? (ts.score ?? '');
                    html += `<div class="col-12"><strong>Top scorer (this season):</strong> ${escapeHtml(playerName)} ${goals ? ('— ' + escapeHtml(String(goals)) + ' goals') : ''}</div>`;
                } else {
                    html += `<div class="col-12"><strong>Top scorer (this season):</strong> N/A</div>`;
                }

                if (team.topScorerAllTime) {
                    const ta = team.topScorerAllTime;
                    const pname = (ta.player && (ta.player.name || ta.player.fullName)) || ta.name || 'Unknown';
                    const pgoals = ta.goals ?? ta.numberOfGoals ?? '';
                    html += `<div class="col-12"><strong>Top scorer (all time):</strong> ${escapeHtml(pname)} ${pgoals ? ('— ' + escapeHtml(String(pgoals)) + ' goals') : ''}</div>`;
                } else {
                    html += `<div class="col-12"><strong>Top scorer (all time):</strong> N/A</div>`;
                }

                if (team.nextMatch) {
                    const nm = team.nextMatch;
                    const home = nm.homeTeam || nm.home || nm.homeTeamInfo || {};
                    const away = nm.awayTeam || nm.away || nm.awayTeamInfo || {};
                    const homeName = home.name || home.shortName || home.teamName || home.team?.name || '';
                    const awayName = away.name || away.shortName || away.teamName || away.team?.name || '';
                    const utc = nm.utcDate || nm.match_date || nm.date || nm.kickoff || nm.start || '';
                    const when = utc ? new Date(utc).toLocaleString() : (nm.status || 'TBD');
                    html += `<div class="col-12"><strong>Next match:</strong> ${escapeHtml(homeName)} vs ${escapeHtml(awayName)} <span class="text-muted">(${escapeHtml(when)})</span></div>`;
                } else {
                    html += `<div class="col-12"><strong>Next match:</strong> N/A</div>`;
                }

                html += '</div></div>';

                content.innerHTML = html;

                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }

            document.addEventListener('click', function (ev) {
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
                    const hid = getTeamId(home);
                    const aid = getTeamId(away);
                    html += `
                                <tr>
                                    <td class="fw-bold">${league}</td>
                                    <td>
                                        <img src="${home.image_path || ''}" style="width:25px; margin-right:8px;" onerror="this.style.display='none'">${teamLinkHtml(hid, home.name || home.short_code || 'Home')}${teamStarHtml(hid, home.name || '', home.image_path || '')}
                                    </td>
                                    <td class="text-center"><strong>${score}</strong></td>
                                    <td>
                                        ${teamLinkHtml(aid, away.name || away.short_code || 'Away')}${teamStarHtml(aid, away.name || '', away.image_path || '')}
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

                    const homeId = getTeamId(home);
                    const awayId = getTeamId(away);
                    html += `
                                <tr>
                                    <td class="fw-bold">${idx + 1}</td>
                                    <td>
                                        ${teamLinkHtml(homeId, home.name || home.shortName || 'Home')}${teamStarHtml(homeId || '', home.name || home.shortName || '', home.crest || home.logo || '')}
                                    </td>
                                    <td class="text-center"><strong>${m.score && m.score.fullTime ? ((m.score.fullTime.homeTeam || 0) + ' - ' + (m.score.fullTime.awayTeam || 0)) : 'vs'}</strong></td>
                                    <td>
                                        ${teamLinkHtml(awayId, away.name || away.shortName || 'Away')}${teamStarHtml(awayId || '', away.name || away.shortName || '', away.crest || away.logo || '')}
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
                    const idStr = resolveTeamId(team);
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
                    const teamId = getTeamId(team, team.name);
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
                                            ${teamLinkHtml(teamId, teamName)}
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

                    const hId = getTeamId(homeObj);
                    const aId = getTeamId(awayObj);
                    html += `
                                <tr class="table-secondary">
                                    <td></td>
                                    <td>${teamLinkHtml(hId, home)}${teamStarHtml(hId || '', home, homeObj.crest || homeObj.logo || '')}</td>
                                    <td class="text-center"><strong>vs</strong></td>
                                    <td>${teamLinkHtml(aId, away)}${teamStarHtml(aId || '', away, awayObj.crest || awayObj.logo || '')}</td>
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

                const idStr = String(teamId);
                const nowFav = !favoritesSet.has(idStr);
                try { TeamFavorites?.setFavorited?.(idStr, nowFav); } catch (e) { }
                syncFavoritesFromStorage();
                renderStandings(tableRows);

                if (!isAuthenticated) return;

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
                        try { showToast('Failed to toggle favorite team'); } catch (e) { }
                        return;
                    }

                    if (json.status === 'favorited') {
                        favoritesSet.add(String(teamId));
                        try { TeamFavorites?.setFavorited?.(teamId, true); } catch (e) { }
                        if (json.teamInfo) {
                            showTeamInfoModal(json.teamInfo);
                        }
                    } else if (json.status === 'unfavorited') {
                        favoritesSet.delete(String(teamId));
                        try { TeamFavorites?.setFavorited?.(teamId, false); } catch (e) { }
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
        .standings-hero {
            padding: 22px;
            border-radius: 18px;
            background:
                radial-gradient(circle at 18% 0%, rgba(0, 255, 133, 0.35), transparent 45%),
                linear-gradient(135deg, #38003c 0%, #0f172a 75%);
            color: #fff;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.18);
        }

        .standings-hero .input-group-text,
        .standings-hero .form-control {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.22);
            color: #fff;
        }

        .standings-hero .form-control::placeholder {
            color: rgba(255, 255, 255, 0.65);
        }

        .standings-hero .form-check-label {
            color: rgba(255, 255, 255, 0.88);
        }

        .standings-subtitle {
            color: rgba(255, 255, 255, 0.82);
            font-size: 0.95rem;
        }

        .standings-league {
            background: rgba(255, 255, 255, 0.10);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.22);
            padding: 0.45rem 0.75rem;
            border-radius: 999px;
            font-weight: 700;
        }

        .standings-updated {
            background: rgba(0, 0, 0, 0.22);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.18);
            padding: 0.45rem 0.75rem;
            border-radius: 999px;
            font-weight: 600;
        }

        .standings-toolbar {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }

        .standings-search {
            max-width: 340px;
        }

        .standings-favs {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .standings-card {
            border: 0;
            border-radius: 18px;
            overflow: hidden;
        }

        .standings-table-wrap {
            max-height: 70vh;
            overflow: auto;
        }

        .standings-table thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background: #f8fafc;
            font-size: 0.82rem;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            color: #334155;
            border-bottom: 1px solid rgba(15, 23, 42, 0.10);
            padding: 14px 12px;
        }

        .standings-table td {
            padding: 14px 12px;
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
        }

        .standings-table tbody tr {
            transition: background-color 0.18s ease;
        }

        .standings-table tbody tr:hover {
            background: rgba(15, 23, 42, 0.02);
        }

        .pos-col {
            width: 90px;
        }

        .pos-pill {
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

        .team-cell {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 240px;
        }

        .team-crest {
            width: 26px;
            height: 26px;
            object-fit: contain;
            filter: drop-shadow(0 1px 1px rgba(0, 0, 0, 0.12));
        }

        .team-name {
            font-weight: 700;
            color: #0f172a;
        }

        .favorite-team-btn {
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

        .favorite-team-btn:hover {
            transform: translateY(-1px);
            color: #f5c518;
        }

        .favorite-team-btn.is-favorited {
            color: #f5c518;
        }

        .favorite-team-btn:disabled {
            opacity: 0.55;
            transform: none;
        }

        .rank-cl {
            background: rgba(0, 255, 133, 0.05);
        }

        .rank-cl .pos-pill {
            background: rgba(0, 255, 133, 0.22);
            color: #064e3b;
        }

        .rank-el {
            background: rgba(217, 119, 6, 0.05);
        }

        .rank-el .pos-pill {
            background: rgba(217, 119, 6, 0.14);
            color: #7c2d12;
        }

        .rank-rel {
            background: rgba(239, 68, 68, 0.05);
        }

        .rank-rel .pos-pill {
            background: rgba(239, 68, 68, 0.16);
            color: #991b1b;
        }

        .standings-legend .legend-box {
            display: inline-block;
            width: 14px;
            height: 14px;
            border-radius: 3px;
            margin-right: 8px;
            vertical-align: middle;
        }

        .standings-legend .legend-cl {
            background: rgba(0, 255, 133, 0.22);
        }

        .standings-legend .legend-el {
            background: rgba(59, 130, 246, 0.14);
        }

        .standings-legend .legend-rel {
            background: rgba(239, 68, 68, 0.16);
        }

        .standings-legend .legend-conf {
            background: #D97706;
        }

        #teamInfoModal .modal-content {
            border: none;
            border-radius: 12px;
        }

        #teamInfoModal .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0;
        }

        #teamInfoModal .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        #teamInfoModal .team-info-details {
            font-size: 0.95rem;
        }

        #teamInfoModal .team-info-details strong {
            color: #495057;
        }
    </style>
@endsection