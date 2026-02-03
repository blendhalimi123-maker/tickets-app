const STORAGE_KEY = 'favorite_teams_v1';

function toTeamId(value) {
    if (value == null) return '';
    return String(value).trim();
}

function safeJsonParse(raw) {
    try {
        return JSON.parse(raw);
    } catch {
        return null;
    }
}

function readFavoriteTeamIds() {
    if (typeof window === 'undefined' || !window.localStorage) return [];
    const raw = window.localStorage.getItem(STORAGE_KEY);
    if (!raw) return [];
    const parsed = safeJsonParse(raw);
    if (!Array.isArray(parsed)) return [];
    return parsed.map(toTeamId).filter(Boolean);
}

function writeFavoriteTeamIds(ids) {
    if (typeof window === 'undefined' || !window.localStorage) return;
    const unique = Array.from(new Set(ids.map(toTeamId).filter(Boolean)));
    window.localStorage.setItem(STORAGE_KEY, JSON.stringify(unique));
}

function getFavoriteSet() {
    return new Set(readFavoriteTeamIds());
}

function isFavorited(teamId) {
    const id = toTeamId(teamId);
    if (!id) return false;
    return getFavoriteSet().has(id);
}

function dispatchFavoriteUpdated(teamId, status) {
    const id = toTeamId(teamId);
    if (!id) return;
    window.dispatchEvent(
        new CustomEvent('favorite-updated', {
            detail: { teamId: id, status },
        }),
    );
}

function setFavorited(teamId, shouldFavorite) {
    const id = toTeamId(teamId);
    if (!id) return { teamId: id, status: 'unfavorited' };

    const set = getFavoriteSet();
    const next = Boolean(shouldFavorite);

    if (next) {
        set.add(id);
    } else {
        set.delete(id);
    }

    writeFavoriteTeamIds([...set]);
    dispatchFavoriteUpdated(id, next ? 'favorited' : 'unfavorited');

    return { teamId: id, status: next ? 'favorited' : 'unfavorited' };
}

function toggleFavorited(teamId) {
    const id = toTeamId(teamId);
    if (!id) return { teamId: id, status: 'unfavorited' };
    return setFavorited(id, !isFavorited(id));
}

window.TeamFavorites = {
    STORAGE_KEY,
    getFavoriteSet,
    isFavorited,
    setFavorited,
    toggleFavorited,
    readFavoriteTeamIds,
    writeFavoriteTeamIds,
};

