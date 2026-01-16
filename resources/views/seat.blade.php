<!DOCTYPE html>
<html>
<head>
    <title>Select Seat - {{ $match['team1'] }} vs {{ $match['team2'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .stadium-wrapper { position: relative; width: 100%; max-width: 1000px; margin: 0 auto; padding: 20px; }
        .stadium-layout {
            display: grid;
            grid-template-areas: ". north ." "west pitch east" ". south .";
            grid-template-columns: 1fr 400px 1fr;
            grid-template-rows: auto 300px auto;
            gap: 20px; margin: 0 auto;
        }
        .pitch-area {
            grid-area: pitch; background: linear-gradient(to bottom, #2a8c2a, #1e7a1e);
            border: 4px solid white; border-radius: 8px; position: relative;
            box-shadow: 0 0 20px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;
        }
        .pitch { width: 80%; height: 70%; border: 2px solid white; border-radius: 4px; position: relative; }
        .center-circle { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 40px; height: 40px; border: 2px solid white; border-radius: 50%; }
        .center-line { position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 2px; height: 100%; background: white; }
        .penalty-area { position: absolute; width: 60px; height: 100px; border: 2px solid white; top: 50%; transform: translateY(-50%); }
        .penalty-left { left: 0; border-left: none; }
        .penalty-right { right: 0; border-right: none; }
        .stand-container { background: #f8f9fa; border: 3px solid #6c757d; border-radius: 10px; padding: 10px; display: flex; flex-direction: column; align-items: center; }
        .north-stand { grid-area: north; border-top: 6px solid #dc3545; }
        .south-stand { grid-area: south; border-bottom: 6px solid #28a745; }
        .east-stand { grid-area: east; border-right: 6px solid #fd7e14; }
        .west-stand { grid-area: west; border-left: 6px solid #ffc107; }
        .seating-section { display: flex; flex-direction: column; gap: 2px; width: 100%; overflow: auto; }
        .seat-row { display: flex; gap: 2px; align-items: center; justify-content: center; }
        .row-label { width: 15px; font-size: 8px; font-weight: bold; color: #495057; text-align: center; }
        .seat { width: 12px; height: 12px; background: #6c757d; border: 1px solid #495057; border-radius: 2px; cursor: pointer; position: relative; }
        .seat:hover { transform: scale(1.5); z-index: 10; }
        .seat.selected { background: #007bff !important; border-color: #0056b3; }
        .seat.sold { background: #adb5bd !important; cursor: not-allowed; opacity: 0.4; }
        .seat.category1 { background: #dc3545; }
        .seat.category2 { background: #fd7e14; }
        .seat.category3 { background: #ffc107; }
        .seat.category4 { background: #28a745; }
        .stadium-legend { display: flex; justify-content: center; gap: 15px; margin: 20px 0; padding: 10px; background: #f8f9fa; border-radius: 10px; }
        .legend-item { display: flex; align-items: center; gap: 5px; font-size: 0.85rem; }
        .seat-sample { width: 15px; height: 15px; border-radius: 2px; border: 1px solid #000; }
        .seat-info-panel { background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.15); padding: 20px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container mt-4">
        @php
            $gameData = cache()->get("game_{$match['id']}", [
                'title' => $match['team1'] . ' vs ' . $match['team2'],
                'home_team' => $match['team1'],
                'away_team' => $match['team2'],
                'stadium' => $match['stadium'],
                'match_date' => $match['match_date']->format('Y-m-d H:i:s'),
            ]);
            $gamePrices = cache()->get("prices_{$match['id']}", [
                'category1' => 85.00, 'category2' => 65.00, 'category3' => 45.00, 'category4' => 35.00,
            ]);
        @endphp

        <div class="card mb-4">
            <div class="card-body">
                <h1>{{ $gameData['title'] }}</h1>
                <p><strong>Stadium:</strong> {{ $gameData['stadium'] }} | <strong>Available:</strong> <span id="available-seats">0</span></p>
            </div>
        </div>

        <div class="stadium-legend">
            <div class="legend-item"><div class="seat-sample category1"></div><span>VIP</span></div>
            <div class="legend-item"><div class="seat-sample category2"></div><span>Premium</span></div>
            <div class="legend-item"><div class="seat-sample category3"></div><span>Standard</span></div>
            <div class="legend-item"><div class="seat-sample category4"></div><span>Economy</span></div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="stadium-wrapper">
                    <div class="stadium-layout">
                        <div class="stand-container north-stand"><div class="seating-section" id="north-stand-seats"></div></div>
                        <div class="stand-container west-stand"><div class="seating-section" id="west-stand-seats"></div></div>
                        <div class="pitch-area"><div class="pitch"><div class="center-circle"></div><div class="center-line"></div><div class="penalty-area penalty-left"></div><div class="penalty-area penalty-right"></div></div></div>
                        <div class="stand-container east-stand"><div class="seating-section" id="east-stand-seats"></div></div>
                        <div class="stand-container south-stand"><div class="seating-section" id="south-stand-seats"></div></div>
                    </div>
                </div>

                <div id="seat-info-card" class="seat-info-panel d-none">
                    <h5>Selected Seats</h5>
                    <div id="selected-seats-list"></div>
                    <p class="fs-5 mt-3"><strong>Total:</strong> $<span id="total-price">0.00</span></p>
                </div>

                <form method="POST" action="{{ route('cart.add-multiple-seats') }}" id="stadium-cart-form">
                    @csrf
                    <input type="hidden" name="api_game_id" value="{{ $match['id'] }}">
                    <input type="hidden" name="home_team" value="{{ $gameData['home_team'] }}">
                    <input type="hidden" name="away_team" value="{{ $gameData['away_team'] }}">
                    <input type="hidden" name="match_date" value="{{ $gameData['match_date'] }}">
                    <input type="hidden" name="stadium" value="{{ $gameData['stadium'] }}">
                    <input type="hidden" name="selected_seats_json" id="selected_seats_json" value="[]">
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="button" class="btn btn-outline-secondary" onclick="clearSelection()">Clear</button>
                        <button type="submit" class="btn btn-success btn-lg" id="add-to-cart-btn" disabled>
                            Add <span id="seat-count">0</span> Seats to Cart - $<span id="confirm-price">0.00</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let selectedSeats = new Set();
        const seatPrices = { 'category1': {{ $gamePrices['category1'] }}, 'category2': {{ $gamePrices['category2'] }}, 'category3': {{ $gamePrices['category3'] }}, 'category4': {{ $gamePrices['category4'] }} };
        const maxSeats = 4;
        const soldSeats = new Set(['north_A_1', 'north_A_2']);

        function generateSeats() {
            const configs = [ { id: 'north', rows: 8, seats: 20, el: 'north-stand-seats' }, { id: 'south', rows: 8, seats: 20, el: 'south-stand-seats' }, { id: 'east', rows: 12, seats: 10, el: 'east-stand-seats' }, { id: 'west', rows: 12, seats: 10, el: 'west-stand-seats' } ];
            configs.forEach(conf => {
                const container = document.getElementById(conf.el);
                for (let r = 1; r <= conf.rows; r++) {
                    const rowLetter = String.fromCharCode(64 + r);
                    const rowDiv = document.createElement('div');
                    rowDiv.className = 'seat-row';
                    const label = document.createElement('div'); label.className = 'row-label'; label.textContent = rowLetter; rowDiv.appendChild(label);
                    for (let s = 1; s <= conf.seats; s++) {
                        const sid = `${conf.id}_${rowLetter}_${s}`;
                        const cat = r <= 2 ? 'category1' : r <= 4 ? 'category2' : r <= 6 ? 'category3' : 'category4';
                        const isSold = soldSeats.has(sid);
                        const seat = document.createElement('div');
                        seat.className = `seat ${cat} ${isSold ? 'sold' : ''}`;
                        seat.dataset.seatId = sid; seat.dataset.price = seatPrices[cat]; seat.dataset.info = `${conf.id.toUpperCase()} - Row ${rowLetter}, Seat ${s}`;
                        if (!isSold) seat.onclick = () => toggleSeat(sid);
                        rowDiv.appendChild(seat);
                    }
                    container.appendChild(rowDiv);
                }
            });
            document.getElementById('available-seats').textContent = document.querySelectorAll('.seat:not(.sold)').length;
        }

        function toggleSeat(sid) {
            const el = document.querySelector(`[data-seat-id="${sid}"]`);
            if (selectedSeats.has(sid)) { selectedSeats.delete(sid); el.classList.remove('selected'); }
            else { if (selectedSeats.size >= maxSeats) return alert(`Max ${maxSeats} seats`); selectedSeats.add(sid); el.classList.add('selected'); }
            updateUI();
        }

        function updateUI() {
            const list = document.getElementById('selected-seats-list');
            const jsonInput = document.getElementById('selected_seats_json');
            let total = 0; let data = [];
            list.innerHTML = '';
            selectedSeats.forEach(sid => {
                const el = document.querySelector(`[data-seat-id="${sid}"]`);
                const price = parseFloat(el.dataset.price);
                total += price;
                data.push({ id: sid, price: price, info: el.dataset.info });
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
            selectedSeats.forEach(sid => document.querySelector(`[data-seat-id="${sid}"]`).classList.remove('selected'));
            selectedSeats.clear(); updateUI();
        }

        document.addEventListener('DOMContentLoaded', generateSeats);
    </script>
</body>
</html>