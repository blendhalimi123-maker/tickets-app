function normalizeName(value) {
    return String(value || '')
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, ' ')
        .trim();
}

function namesMatch(a, b) {
    const na = normalizeName(a);
    const nb = normalizeName(b);
    if (!na || !nb) return false;
    if (na === nb) return true;
    if (na.length >= 4 && nb.length >= 4 && (na.includes(nb) || nb.includes(na))) return true;
    return false;
}

function findLiveMatchIdForTeam(matches, teamNames) {
    if (!Array.isArray(matches) || !teamNames.length) return null;

    for (const match of matches) {
        const participants = Array.isArray(match?.participants) ? match.participants : [];
        const participantNames = participants
            .map((p) => p?.name)
            .filter(Boolean);

        for (const candidate of teamNames) {
            for (const pname of participantNames) {
                if (namesMatch(candidate, pname)) {
                    return match?.id ?? null;
                }
            }
        }
    }

    return null;
}

function buildTeamNamesFromDataset(dataset) {
    const raw = [dataset.teamName, dataset.teamShortName, dataset.teamTla].filter(Boolean);
    const unique = Array.from(new Set(raw.map((s) => String(s).trim()).filter(Boolean)));
    return unique;
}

let liveChannelInitialized = false;
let liveChannel = null;
let liveChannelHandler = null;

function ensureLiveChannel(onEvent) {
    if (liveChannelInitialized) return;
    liveChannelInitialized = true;

    if (typeof window === 'undefined' || typeof window.Echo === 'undefined') return;

    try {
        liveChannel = window.Echo.channel('football.live');
        liveChannelHandler = (e) => onEvent?.(e);
        liveChannel.listen('LiveMatchesUpdated', liveChannelHandler);
    } catch (err) {
        // no-op: Reverb/Echo not configured
    }
}

let currentInstance = null;
let lastSnapshot = null;
let inplayHydrated = false;

async function hydrateInplayOnce() {
    if (inplayHydrated) return;
    inplayHydrated = true;

    try {
        const res = await fetch('/api/sportmonks/inplay', { headers: { Accept: 'application/json' } });
        if (!res.ok) return;
        const json = await res.json();
        const fixtures = Array.isArray(json?.data) ? json.data : [];
        lastSnapshot = fixtures.map((f) => ({
            id: f?.id ?? null,
            participants: Array.isArray(f?.participants) ? f.participants : [],
        }));
    } catch {
        // ignore
    }
}

function mountNextActionButton(modalEl) {
    const btn = modalEl.querySelector('.js-team-next-action');
    if (!btn) return;

    const teamId = String(btn.dataset.teamId || '').trim();
    const teamNames = buildTeamNamesFromDataset(btn.dataset);

    if (!teamId || !teamNames.length) {
        btn.classList.add('d-none');
        return;
    }

    if (currentInstance?.destroy) currentInstance.destroy();

    let isFavorited = Boolean(window.TeamFavorites?.isFavorited?.(teamId));
    let liveMatchId = null;

    const fixturesHref = `/team/${encodeURIComponent(teamId)}/fixtures`;
    const fixturesText = `See Next ${teamNames[0]} Games`;

    function render() {
        isFavorited = Boolean(window.TeamFavorites?.isFavorited?.(teamId));

        if (!isFavorited) {
            liveMatchId = null;
            btn.classList.add('d-none');
            btn.setAttribute('aria-hidden', 'true');
            return;
        }

        btn.classList.remove('d-none');
        btn.removeAttribute('aria-hidden');

        if (liveMatchId) {
            btn.textContent = 'Live Now \u2013 View Match';
            btn.setAttribute('href', `/match/${encodeURIComponent(liveMatchId)}`);
            btn.classList.add('is-live');
            btn.classList.remove('is-fixtures');
            btn.classList.add('btn-outline-danger');
            btn.classList.remove('btn-outline-primary');
        } else {
            btn.textContent = fixturesText;
            btn.setAttribute('href', fixturesHref);
            btn.classList.remove('is-live');
            btn.classList.add('is-fixtures');
            btn.classList.add('btn-outline-primary');
            btn.classList.remove('btn-outline-danger');
        }
    }

    function applySnapshot(snapshot) {
        if (!isFavorited) return;
        const matchId = findLiveMatchIdForTeam(snapshot, teamNames);
        liveMatchId = matchId || null;
        render();
    }

    function onFavoriteUpdated(e) {
        const updatedId = String(e?.detail?.teamId || '').trim();
        if (updatedId !== teamId) return;
        render();
        if (isFavorited && Array.isArray(lastSnapshot)) {
            applySnapshot(lastSnapshot);
        }
    }

    function onLiveMatchesUpdated(e) {
        const matches = Array.isArray(e?.matches) ? e.matches : [];
        lastSnapshot = matches;
        applySnapshot(matches);
    }

    window.addEventListener('favorite-updated', onFavoriteUpdated);

    ensureLiveChannel(onLiveMatchesUpdated);
    hydrateInplayOnce().finally(() => {
        if (Array.isArray(lastSnapshot)) applySnapshot(lastSnapshot);
        render();
    });

    render();

    currentInstance = {
        destroy() {
            window.removeEventListener('favorite-updated', onFavoriteUpdated);
        },
    };
}

function initTeamInfoModalNextAction() {
    const modalEl = document.getElementById('teamInfoModal');
    if (!modalEl) return;

    modalEl.addEventListener('shown.bs.modal', () => mountNextActionButton(modalEl));
    modalEl.addEventListener('hidden.bs.modal', () => {
        if (currentInstance?.destroy) currentInstance.destroy();
        currentInstance = null;
    });
}

document.addEventListener('DOMContentLoaded', initTeamInfoModalNextAction);
