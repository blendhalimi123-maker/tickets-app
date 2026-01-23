<!DOCTYPE html>
<html>
<head>
    <title>Select Seat - {{ $match['team1'] }} vs {{ $match['team2'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: radial-gradient(circle at top, #222 0, #000 55%, #111 100%);
            min-height: 100vh;
        }

        .stadium-wrapper {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px;
        }

        .stadium-card {
            background: rgba(15, 15, 20, 0.96);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6);
            padding: 24px 24px 32px;
            color: #f8f9fa;
        }

        .match-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .match-teams {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: .03em;
        }

        .match-meta {
            font-size: .9rem;
            opacity: .8;
        }

        .stadium-layout {
            position: relative;
            margin-top: 10px;
            padding: 18px;
            border-radius: 22px;
            background: radial-gradient(circle at top, #0b1220 0, #05070d 55%, #020307 100%);
            box-shadow: inset 0 0 40px rgba(0, 0, 0, 0.8);
            overflow: hidden;
            min-height: 420px;
        }

        .overview-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .overview-card {
            background: rgba(2, 6, 23, 0.75);
            border: 1px solid rgba(148, 163, 184, 0.18);
            border-radius: 18px;
            padding: 14px;
            box-shadow: 0 18px 45px rgba(0,0,0,.55);
        }

        .overview-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .overview-title h6 {
            margin: 0;
            font-weight: 800;
            letter-spacing: .06em;
            text-transform: uppercase;
            font-size: .8rem;
            color: rgba(226,232,240,.9);
        }

        .overview-hint {
            font-size: .8rem;
            color: rgba(148,163,184,.9);
        }

        .stadium-svg {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 14px;
            background: radial-gradient(circle at 50% 40%, rgba(34,197,94,.20), rgba(2,6,23,.35));
        }

        .section {
            cursor: pointer;
            transition: filter .12s ease-out, transform .12s ease-out, opacity .12s ease-out;
            transform-origin: 50% 50%;
        }
        .section:hover { filter: brightness(1.12); }
        .section.is-active { filter: brightness(1.25) saturate(1.05); }
        .section.is-disabled { cursor: not-allowed; opacity: .45; }

        .seat-picker {
            display: none;
        }

        .seat-picker.is-open {
            display: block;
        }

        .picker-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 12px;
        }

        .picker-stand {
            font-weight: 900;
            font-size: 1.1rem;
            letter-spacing: .04em;
        }

        .picker-sub {
            font-size: .85rem;
            color: rgba(148,163,184,.95);
        }

        .stand-container {
            position: relative;
            padding: 16px 10px;
            border-radius: 18px;
            background: linear-gradient(145deg, rgba(20, 20, 28, .98), rgba(8, 8, 14, .98));
            border: 1px solid rgba(255, 255, 255, 0.06);
            box-shadow:
                0 16px 40px rgba(0, 0, 0, 0.9),
                inset 0 0 30px rgba(255, 255, 255, 0.02);
        }

        .stand-label {
            position: absolute;
            top: 4px;
            left: 12px;
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: rgba(248, 250, 252, 0.65);
        }

        .seat-row {
            display: flex;
            gap: 2px;
            justify-content: center;
            margin-bottom: 2px;
        }

        .row-label {
            width: 18px;
            font-size: 8px;
            font-weight: 700;
            color: rgba(148, 163, 184, 0.9);
        }

        .seat {
            width: 11px;
            height: 11px;
            border-radius: 3px;
            border: 1px solid rgba(15, 23, 42, 0.9);
            cursor: pointer;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.45);
            transition: transform .08s ease-out, box-shadow .08s ease-out, background-color .1s ease-out;
        }

        .seat.category1 { background: #dc2626; }
        .seat.category2 { background: #ea580c; }
        .seat.category3 { background: #eab308; }
        .seat.category4 { background: #22c55e; }

        .seat.selected {
            background: #0ea5e9 !important;
            transform: translateY(-1px) scale(1.05);
            box-shadow: 0 0 0 1px #38bdf8, 0 4px 10px rgba(8, 47, 73, 0.9);
        }

        .seat.sold {
            background: #020617 !important;
            border-color: #64748b !important;
            cursor: not-allowed;
            opacity: .55;
            box-shadow: none;
        }

        .seat-info-panel {
            background: #170202;
            border-radius: 18px;
            padding: 18px 20px;
            margin-top: 20px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.9);
            border: 1px solid rgba(148, 163, 184, 0.45);
            color: #d80000;
        }

        .legend-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(15, 23, 42, .9);
            font-size: .75rem;
            color: #cbd5f5;
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
        }
    </style>
</head>

<body>
<div class="stadium-wrapper mt-4">
    <div class="stadium-card">
        <div class="match-header">
            <div>
                <div class="match-teams">
                    {{ $match['team1'] }} <span class="text-muted">vs</span> {{ $match['team2'] }}
                </div>
                <div class="match-meta">
                    Stadium: {{ $match['stadium'] }}
                    &nbsp;•&nbsp;
                    Available seats: <span id="available-seats">0</span>
                </div>
            </div>
        </div>

        <div class="stadium-layout">
            <div class="overview-grid">
                <div class="overview-card">
                    <div class="overview-title">
                        <h6>Stadium Map</h6>
                        <div class="overview-hint">Click a stand to pick seats</div>
                    </div>

                    <svg class="stadium-svg" viewBox="0 0 800 520" role="img" aria-label="Stadium overview">
                        <defs>
                            <linearGradient id="bowl" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0" stop-color="#111827" />
                                <stop offset="1" stop-color="#020617" />
                            </linearGradient>
                            <linearGradient id="pitch" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0" stop-color="#16a34a" />
                                <stop offset="1" stop-color="#15803d" />
                            </linearGradient>
                        </defs>

                        <rect x="30" y="20" width="740" height="480" rx="90" fill="url(#bowl)" stroke="rgba(255,255,255,.14)" />
                        <rect x="85" y="65" width="630" height="390" rx="70" fill="rgba(0,0,0,.30)" stroke="rgba(255,255,255,.10)" />

                        <rect x="230" y="130" width="340" height="260" rx="10" fill="url(#pitch)" stroke="rgba(255,255,255,.8)" />
                        <rect x="260" y="160" width="280" height="200" rx="8" fill="transparent" stroke="rgba(255,255,255,.75)" />
                        <circle cx="400" cy="260" r="38" fill="transparent" stroke="rgba(255,255,255,.75)" />
                        <line x1="400" y1="130" x2="400" y2="390" stroke="rgba(255,255,255,.75)" />

                        <g class="section" data-stand="north">
                            <path d="M120 75 H680 Q705 75 705 100 V145 Q705 160 690 160 H110 Q95 160 95 145 V100 Q95 75 120 75Z"
                                  fill="rgba(234,179,8,.85)" stroke="rgba(255,255,255,.25)" />
                            <text x="400" y="125" text-anchor="middle" fill="#0b1220" font-weight="900" font-size="18">NORTH</text>
                        </g>

                        <g class="section" data-stand="south">
                            <path d="M110 360 H690 Q705 360 705 375 V420 Q705 445 680 445 H120 Q95 445 95 420 V375 Q95 360 110 360Z"
                                  fill="rgba(234,88,12,.86)" stroke="rgba(255,255,255,.25)" />
                            <text x="400" y="412" text-anchor="middle" fill="#0b1220" font-weight="900" font-size="18">SOUTH</text>
                        </g>

                        <g class="section" data-stand="west">
                            <path d="M95 165 H200 V355 H95 Q70 355 70 330 V190 Q70 165 95 165Z"
                                  fill="rgba(220,38,38,.86)" stroke="rgba(255,255,255,.25)" />
                            <text x="132" y="265" text-anchor="middle" fill="#0b1220" font-weight="900" font-size="18" transform="rotate(-90 132 265)">WEST</text>
                        </g>

                        <g class="section" data-stand="east">
                            <path d="M600 165 H705 Q730 165 730 190 V330 Q730 355 705 355 H600 Z"
                                  fill="rgba(34,197,94,.86)" stroke="rgba(255,255,255,.25)" />
                            <text x="668" y="265" text-anchor="middle" fill="#0b1220" font-weight="900" font-size="18" transform="rotate(90 668 265)">EAST</text>
                        </g>

                        <text x="400" y="495" text-anchor="middle" fill="rgba(226,232,240,.75)" font-size="12">Tip: click a stand, then choose seats</text>
                    </svg>
                </div>

                <div id="seat-picker" class="overview-card seat-picker">
                    <div class="picker-header">
                        <div>
                            <div class="picker-stand" id="picker-stand-title">Choose seats</div>
                            <div class="picker-sub">Max 4 seats • Sold seats are disabled</div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-light" id="close-picker-btn">Close</button>
                    </div>

                    <div class="stand-container">
                        <span class="stand-label" id="stand-label-dynamic">Stand</span>
                        <div id="active-stand-seats"></div>
                    </div>
                </div>
            </div>
        </div>
    
        <div id="seat-info-card" class="seat-info-panel d-none">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0">Selected Seats</h5>
            <div class="d-flex gap-2 small">
                <span class="legend-pill"><span class="legend-dot" style="background:#dc2626"></span> Category 1</span>
                <span class="legend-pill"><span class="legend-dot" style="background:#ea580c"></span> Category 2</span>
                <span class="legend-pill"><span class="legend-dot" style="background:#eab308"></span> Category 3</span>
                <span class="legend-pill"><span class="legend-dot" style="background:#22c55e"></span> Category 4</span>
            </div>
        </div>
        <div id="selected-seats-list" class="small"></div>
        <p class="fs-5 mt-3 mb-0"><strong>Total:</strong> $<span id="total-price">0.00</span></p>
        </div>

        <form method="POST" action="{{ route('cart.add-multiple-seats') }}" id="stadium-cart-form">
        @csrf

        <input type="hidden" name="api_game_id" value="{{ $match['id'] }}">
        <input type="hidden" name="home_team" value="{{ $match['team1'] }}">
        <input type="hidden" name="away_team" value="{{ $match['team2'] }}">
        <input type="hidden" name="match_date" value="{{ $match['match_date'] }}">
        <input type="hidden" name="stadium" value="{{ $match['stadium'] }}">
        <input type="hidden" name="selected_seats_json" id="selected_seats_json" value="[]">

        <div class="d-flex justify-content-end mt-4">
            <button type="button" class="btn btn-outline-secondary me-2" onclick="clearSelection()">Clear</button>

            <button type="submit" class="btn btn-success btn-lg" id="add-to-cart-btn" disabled>
                Add <span id="seat-count">0</span> Seats to Cart -
                $<span id="confirm-price">0.00</span>
            </button>
        </div>
        </form>

    </div>

</div>

<script>
let selectedSeats = new Set();
const maxSeats = 4;

const seatPrices = {
    category1: 85,
    category2: 65,
    category3: 45,
    category4: 35
};

const soldSeats = new Set(@json($soldSeats ?? []));

let activeStand = null;

function generateSeats() {
    updateAvailableCount();
}

function updateAvailableCount() {
    const allConfigs = [
        { id: 'north', rows: 8, seats: 20 },
        { id: 'south', rows: 8, seats: 20 },
        { id: 'east', rows: 12, seats: 10 },
        { id: 'west', rows: 12, seats: 10 }
    ];

    let available = 0;
    allConfigs.forEach(conf => {
        for (let r = 1; r <= conf.rows; r++) {
            const rowLetter = String.fromCharCode(64 + r);
            for (let s = 1; s <= conf.seats; s++) {
                const sid = `${conf.id}_${rowLetter}_${s}`;
                if (!soldSeats.has(sid)) available++;
            }
        }
    });
    document.getElementById('available-seats').textContent = available;
}

function renderStandSeats(standId) {
    const container = document.getElementById('active-stand-seats');
    container.innerHTML = '';

    const conf = (standId === 'north' || standId === 'south')
        ? { id: standId, rows: 8, seats: 20 }
        : { id: standId, rows: 12, seats: 10 };

    for (let r = 1; r <= conf.rows; r++) {
        const rowLetter = String.fromCharCode(64 + r);
        const rowDiv = document.createElement('div');
        rowDiv.className = 'seat-row';

        const label = document.createElement('div');
        label.className = 'row-label';
        label.textContent = rowLetter;
        rowDiv.appendChild(label);

        for (let s = 1; s <= conf.seats; s++) {
            const sid = `${conf.id}_${rowLetter}_${s}`;
            const cat = r <= 2 ? 'category1' : r <= 4 ? 'category2' : r <= 6 ? 'category3' : 'category4';
            const isSold = soldSeats.has(sid);

            const seat = document.createElement('div');
            seat.className = `seat ${isSold ? 'sold' : cat}`;
            seat.dataset.seatId = sid;
            seat.dataset.price = seatPrices[cat];
            seat.dataset.stand = conf.id;
            seat.dataset.row = rowLetter;
            seat.dataset.number = s;
            seat.dataset.category = cat;
            seat.dataset.info = `${conf.id.toUpperCase()} - Row ${rowLetter}, Seat ${s}`;

            if (selectedSeats.has(sid)) {
                seat.classList.add('selected');
            }

            if (!isSold) {
                seat.onclick = () => toggleSeat(sid);
            }

            rowDiv.appendChild(seat);
        }

        container.appendChild(rowDiv);
    }
}

function setActiveStand(standId) {
    activeStand = standId;

    document.querySelectorAll('.section').forEach(el => {
        el.classList.toggle('is-active', el.dataset.stand === standId);
    });

    const picker = document.getElementById('seat-picker');
    picker.classList.add('is-open');

    const title = document.getElementById('picker-stand-title');
    const label = document.getElementById('stand-label-dynamic');
    const standName = standId.charAt(0).toUpperCase() + standId.slice(1);
    title.textContent = `${standName} Stand`;
    label.textContent = `${standName} Stand`;

    renderStandSeats(standId);
}

function toggleSeat(sid) {
    const el = document.querySelector(`[data-seat-id="${sid}"]`);

    if (selectedSeats.has(sid)) {
        selectedSeats.delete(sid);
        if (el) el.classList.remove('selected');
    } else {
        if (selectedSeats.size >= maxSeats) {
            alert(`Max ${maxSeats} seats`);
            return;
        }
        selectedSeats.add(sid);
        if (el) el.classList.add('selected');
    }

    updateUI();

    if (activeStand) {
        renderStandSeats(activeStand);
    }
}

function updateUI() {
    const list = document.getElementById('selected-seats-list');
    const jsonInput = document.getElementById('selected_seats_json');

    let total = 0;
    let data = [];
    list.innerHTML = '';

    selectedSeats.forEach(sid => {
        const el = document.querySelector(`[data-seat-id="${sid}"]`);
        let price = 0;
        let stand = '';
        let row = '';
        let number = 0;
        let category = '';
        let info = '';

        if (el) {
            price = parseFloat(el.dataset.price);
            stand = el.dataset.stand;
            row = el.dataset.row;
            number = parseInt(el.dataset.number);
            category = el.dataset.category;
            info = el.dataset.info;
        } else {
            const parts = sid.split('_');
            stand = parts[0] || '';
            row = parts[1] || '';
            number = parseInt(parts[2] || '0');
            const r = (row.charCodeAt(0) || 65) - 64;
            category = r <= 2 ? 'category1' : r <= 4 ? 'category2' : r <= 6 ? 'category3' : 'category4';
            price = seatPrices[category] || 0;
            info = `${stand.toUpperCase()} - Row ${row}, Seat ${number}`;
        }

        total += price;

        data.push({
            id: sid,
            stand: stand,
            row: row,
            number: number,
            category: category,
            price: price,
            info: info
        });

        const item = document.createElement('div');
        item.className = 'p-2 mb-1 bg-light border-start border-primary border-4 d-flex justify-content-between';
        item.innerHTML = `<span>${info}</span><strong>$${price}</strong>`;
        list.appendChild(item);
    });

    jsonInput.value = JSON.stringify(data);
    document.getElementById('seat-count').textContent = selectedSeats.size;
    document.getElementById('total-price').textContent = total.toFixed(2);
    document.getElementById('confirm-price').textContent = total.toFixed(2);
    document.getElementById('add-to-cart-btn').disabled = selectedSeats.size === 0;
    document.getElementById('seat-info-card').classList.toggle('d-none', selectedSeats.size === 0);
}

function clearSelection() {
    selectedSeats.forEach(sid => {
        document.querySelector(`[data-seat-id="${sid}"]`).classList.remove('selected');
    });
    selectedSeats.clear();
    updateUI();
}

document.addEventListener('DOMContentLoaded', generateSeats);

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.section').forEach(el => {
        el.addEventListener('click', () => {
            const stand = el.dataset.stand;
            if (!stand) return;
            setActiveStand(stand);
        });
    });

    const closeBtn = document.getElementById('close-picker-btn');
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            document.getElementById('seat-picker').classList.remove('is-open');
            document.querySelectorAll('.section').forEach(el => el.classList.remove('is-active'));
            activeStand = null;
        });
    }
});
</script>

</body>
</html>
