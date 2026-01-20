<!DOCTYPE html>
<html>
<head>
    <title>Select Seat - {{ $match['team1'] }} vs {{ $match['team2'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .stadium-wrapper { max-width: 1000px; margin: auto; padding: 20px; }

        .stadium-layout {
            display: grid;
            grid-template-areas: ". north ." "west pitch east" ". south .";
            grid-template-columns: 1fr 400px 1fr;
            grid-template-rows: auto 300px auto;
            gap: 20px;
        }

        .pitch-area {
            grid-area: pitch;
            background: linear-gradient(to bottom, #2a8c2a, #1e7a1e);
            border: 4px solid #fff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pitch { width: 80%; height: 70%; border: 2px solid white; }

        .stand-container {
            background: #f8f9fa;
            border: 3px solid #6c757d;
            border-radius: 10px;
            padding: 10px;
        }

        .north-stand { grid-area: north; border-top: 6px solid #dc3545; }
        .south-stand { grid-area: south; border-bottom: 6px solid #28a745; }
        .east-stand  { grid-area: east; border-right: 6px solid #fd7e14; }
        .west-stand  { grid-area: west; border-left: 6px solid #ffc107; }

        .seat-row { display: flex; gap: 2px; justify-content: center; }
        .row-label { width: 15px; font-size: 8px; font-weight: bold; }

        .seat {
            width: 12px;
            height: 12px;
            border: 1px solid #495057;
            cursor: pointer;
        }

        .seat.category1 { background: #dc3545; }
        .seat.category2 { background: #fd7e14; }
        .seat.category3 { background: #ffc107; }
        .seat.category4 { background: #28a745; }

        .seat.selected { background: #007bff !important; }

        .seat.sold {
            background-color: #ffffff !important;
            border: 1px solid #ccc !important;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .seat-info-panel {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
    </style>
</head>

<body>
<div class="container mt-4">

    <div class="card mb-3">
        <div class="card-body">
            <h1>{{ $match['team1'] }} vs {{ $match['team2'] }}</h1>
            <p><strong>Stadium:</strong> {{ $match['stadium'] }} |
               <strong>Available:</strong> <span id="available-seats">0</span></p>
        </div>
    </div>

    <div class="stadium-wrapper">
        <div class="stadium-layout">

            <div class="stand-container north-stand">
                <div id="north-stand-seats"></div>
            </div>

            <div class="stand-container west-stand">
                <div id="west-stand-seats"></div>
            </div>

            <div class="pitch-area">
                <div class="pitch"></div>
            </div>

            <div class="stand-container east-stand">
                <div id="east-stand-seats"></div>
            </div>

            <div class="stand-container south-stand">
                <div id="south-stand-seats"></div>
            </div>

        </div>
    </div>

    <div id="seat-info-card" class="seat-info-panel d-none">
        <h5>Selected Seats</h5>
        <div id="selected-seats-list"></div>
        <p class="fs-5"><strong>Total:</strong> $<span id="total-price">0.00</span></p>
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

function generateSeats() {
    const configs = [
        { id: 'north', rows: 8, seats: 20, el: 'north-stand-seats' },
        { id: 'south', rows: 8, seats: 20, el: 'south-stand-seats' },
        { id: 'east', rows: 12, seats: 10, el: 'east-stand-seats' },
        { id: 'west', rows: 12, seats: 10, el: 'west-stand-seats' }
    ];

    configs.forEach(conf => {
        const container = document.getElementById(conf.el);

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

                if (!isSold) {
                    seat.onclick = () => toggleSeat(sid);
                }

                rowDiv.appendChild(seat);
            }

            container.appendChild(rowDiv);
        }
    });

    document.getElementById('available-seats').textContent =
        document.querySelectorAll('.seat:not(.sold)').length;
}

function toggleSeat(sid) {
    const el = document.querySelector(`[data-seat-id="${sid}"]`);

    if (selectedSeats.has(sid)) {
        selectedSeats.delete(sid);
        el.classList.remove('selected');
    } else {
        if (selectedSeats.size >= maxSeats) {
            alert(`Max ${maxSeats} seats`);
            return;
        }
        selectedSeats.add(sid);
        el.classList.add('selected');
    }

    updateUI();
}

function updateUI() {
    const list = document.getElementById('selected-seats-list');
    const jsonInput = document.getElementById('selected_seats_json');

    let total = 0;
    let data = [];
    list.innerHTML = '';

    selectedSeats.forEach(sid => {
        const el = document.querySelector(`[data-seat-id="${sid}"]`);
        const price = parseFloat(el.dataset.price);
        total += price;

        data.push({
            id: sid,
            stand: el.dataset.stand,
            row: el.dataset.row,
            number: parseInt(el.dataset.number),
            category: el.dataset.category,
            price: price,
            info: el.dataset.info
        });

        const item = document.createElement('div');
        item.className = 'p-2 mb-1 bg-light border-start border-primary border-4 d-flex justify-content-between';
        item.innerHTML = `<span>${el.dataset.info}</span><strong>$${price}</strong>`;
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
</script>

</body>
</html>
