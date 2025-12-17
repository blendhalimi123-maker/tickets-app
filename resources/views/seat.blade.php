<!DOCTYPE html>
<html>
<head>
    <title>Select Seat - {{ $match['team1'] }} vs {{ $match['team2'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .stadium-wrapper {
            position: relative;
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .stadium-layout {
            display: grid;
            grid-template-areas:
                ". north ."
                "west pitch east"
                ". south .";
            grid-template-columns: 1fr 400px 1fr;
            grid-template-rows: 180px 300px 180px;
            gap: 20px;
            margin: 0 auto;
        }
        
        .pitch-area {
            grid-area: pitch;
            background: linear-gradient(to bottom, #2a8c2a, #1e7a1e);
            border: 4px solid white;
            border-radius: 8px;
            position: relative;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .pitch {
            width: 80%;
            height: 70%;
            border: 2px solid white;
            border-radius: 4px;
            position: relative;
        }
        
        .center-circle {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 40px;
            height: 40px;
            border: 2px solid white;
            border-radius: 50%;
        }
        
        .center-line {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 100%;
            background: white;
        }
        
        .penalty-area {
            position: absolute;
            width: 60px;
            height: 25px;
            border: 2px solid white;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .penalty-left {
            left: 10px;
        }
        
        .penalty-right {
            right: 10px;
        }
        
        .stand-container {
            background: #f8f9fa;
            border: 3px solid #6c757d;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .north-stand {
            grid-area: north;
            border-top: 6px solid #dc3545;
        }
        
        .south-stand {
            grid-area: south;
            border-bottom: 6px solid #28a745;
        }
        
        .east-stand {
            grid-area: east;
            border-right: 6px solid #fd7e14;
        }
        
        .west-stand {
            grid-area: west;
            border-left: 6px solid #ffc107;
        }
        
        .stand-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 1.1rem;
            color: #495057;
        }
        
        .seating-section {
            display: flex;
            flex-direction: column;
            gap: 4px;
            width: 100%;
            max-height: 100%;
            overflow-y: auto;
            padding: 5px;
        }
        
        .seat-row {
            display: flex;
            gap: 2px;
            align-items: center;
            justify-content: center;
        }
        
        .row-label {
            width: 20px;
            font-size: 10px;
            font-weight: bold;
            color: #495057;
            text-align: center;
        }
        
        .seat {
            width: 18px;
            height: 18px;
            background: #6c757d;
            border: 1px solid #495057;
            border-radius: 2px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: white;
            transition: all 0.2s;
        }
        
        .seat:hover {
            transform: scale(1.2);
            box-shadow: 0 0 5px rgba(0,0,0,0.3);
        }
        
        .seat.selected {
            background: #007bff;
            border-color: #0056b3;
            box-shadow: 0 0 8px rgba(0,123,255,0.5);
        }
        
        .seat.sold {
            background: #adb5bd;
            cursor: not-allowed;
            opacity: 0.4;
        }
        
        .seat.category1 { background: #dc3545; }
        .seat.category2 { background: #fd7e14; }
        .seat.category3 { background: #ffc107; color: #000; }
        .seat.category4 { background: #28a745; }
        
        .seat-number {
            display: none;
        }
        
        .seat:hover .seat-number {
            display: block;
        }
        
        .stadium-legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 30px 0;
            flex-wrap: wrap;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #dee2e6;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .seat-sample {
            width: 20px;
            height: 20px;
            border-radius: 3px;
            border: 1px solid #000;
        }
        
        .seat-info-panel {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            padding: 20px;
            margin-top: 20px;
            border: 1px solid #dee2e6;
        }
        
        .selected-seat-item {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
            border-left: 4px solid #007bff;
        }
        
        @media (max-width: 992px) {
            .stadium-layout {
                grid-template-areas:
                    "north"
                    "west"
                    "pitch"
                    "east"
                    "south";
                grid-template-columns: 1fr;
                grid-template-rows: repeat(5, auto);
                gap: 15px;
            }
            
            .stand-container {
                min-height: 120px;
            }
            
            .seating-section {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .seat-row {
                flex-direction: column;
                gap: 2px;
            }
        }
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
                'category1' => 85.00,
                'category2' => 65.00,
                'category3' => 45.00,
                'category4' => 35.00,
            ]);
        @endphp

        <div class="card mb-4">
            <div class="card-body">
                <h1 class="mb-3">{{ $gameData['title'] }}</h1>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($gameData['match_date'])->format('Y-m-d H:i') }}</p>
                        <p><strong>Stadium:</strong> {{ $gameData['stadium'] }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Match ID:</strong> {{ $match['id'] }}</p>
                        <p><strong>Available Seats:</strong> <span id="available-seats">Loading...</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="stadium-legend">
            <div class="legend-item">
                <div class="seat-sample category1"></div>
                <span>VIP - ${{ $gamePrices['category1'] }}</span>
            </div>
            <div class="legend-item">
                <div class="seat-sample category2"></div>
                <span>Premium - ${{ $gamePrices['category2'] }}</span>
            </div>
            <div class="legend-item">
                <div class="seat-sample category3"></div>
                <span>Standard - ${{ $gamePrices['category3'] }}</span>
            </div>
            <div class="legend-item">
                <div class="seat-sample category4"></div>
                <span>Economy - ${{ $gamePrices['category4'] }}</span>
            </div>
            <div class="legend-item">
                <div class="seat-sample sold"></div>
                <span>Sold/Unavailable</span>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="text-center mb-4">Select Your Seats</h4>
                
                <div class="stadium-wrapper">
                    <div class="stadium-layout">
                        <!-- North Stand -->
                        <div class="stand-container north-stand">
                            <div class="stand-title">North Stand</div>
                            <div class="seating-section" id="north-stand-seats"></div>
                        </div>
                        
                        <!-- West Stand -->
                        <div class="stand-container west-stand">
                            <div class="stand-title">West Stand</div>
                            <div class="seating-section" id="west-stand-seats"></div>
                        </div>
                        
                        <!-- Pitch -->
                        <div class="pitch-area">
                            <div class="pitch">
                                <div class="center-circle"></div>
                                <div class="center-line"></div>
                                <div class="penalty-area penalty-left"></div>
                                <div class="penalty-area penalty-right"></div>
                            </div>
                        </div>
                        
                        <!-- East Stand -->
                        <div class="stand-container east-stand">
                            <div class="stand-title">East Stand</div>
                            <div class="seating-section" id="east-stand-seats"></div>
                        </div>
                        
                        <!-- South Stand -->
                        <div class="stand-container south-stand">
                            <div class="stand-title">South Stand</div>
                            <div class="seating-section" id="south-stand-seats"></div>
                        </div>
                    </div>
                </div>
                
                <div id="seat-info-card" class="seat-info-panel mt-4 d-none">
                    <h5>Selected Seats</h5>
                    <div id="selected-seats-list"></div>
                    <div class="mt-3 pt-3 border-top">
                        <p class="fs-5"><strong>Total:</strong> $<span id="total-price">0.00</span></p>
                        <button type="button" class="btn btn-danger w-100" onclick="clearSelection()">
                            Clear All Seats
                        </button>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('cart.add-multiple-seats') }}" class="mt-4" id="stadium-cart-form">
                    @csrf
                    <input type="hidden" name="api_game_id" value="{{ $match['id'] }}">
                    <input type="hidden" name="home_team" value="{{ $gameData['home_team'] }}">
                    <input type="hidden" name="away_team" value="{{ $gameData['away_team'] }}">
                    <input type="hidden" name="match_date" value="{{ $gameData['match_date'] }}">
                    <input type="hidden" name="stadium" value="{{ $gameData['stadium'] }}">
                    <input type="hidden" name="selected_seats_json" id="selected_seats_json" value="[]">
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="javascript:history.back()" class="btn btn-secondary btn-lg me-md-2">Back</a>
                        
                        <button type="button" class="btn btn-danger btn-lg me-md-2" onclick="clearSelection()">
                            Clear All Seats
                        </button>
                        
                        <button type="submit" class="btn btn-success btn-lg" id="add-to-cart-btn" disabled>
                            <i class="fas fa-cart-plus"></i> Add <span id="seat-count">0</span> Seat(s) to Cart - $<span id="confirm-price">0.00</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let selectedSeats = new Set();
        let seatPrices = {
            'category1': {{ $gamePrices['category1'] }},
            'category2': {{ $gamePrices['category2'] }},
            'category3': {{ $gamePrices['category3'] }},
            'category4': {{ $gamePrices['category4'] }}
        };
        
        const maxSeatsPerOrder = 4;
        const soldSeats = new Set(['north_A_1', 'north_A_2', 'north_B_5', 'east_C_10', 'west_A_8']);
        
        function generateSeats() {
            const stands = [
                { id: 'north', rows: 15, seatsPerRow: 30, elementId: 'north-stand-seats' },
                { id: 'south', rows: 15, seatsPerRow: 30, elementId: 'south-stand-seats' },
                { id: 'east', rows: 20, seatsPerRow: 20, elementId: 'east-stand-seats' },
                { id: 'west', rows: 20, seatsPerRow: 20, elementId: 'west-stand-seats' }
            ];
            
            stands.forEach(stand => {
                const container = document.getElementById(stand.elementId);
                container.innerHTML = '';
                
                for (let rowNum = 1; rowNum <= stand.rows; rowNum++) {
                    const rowLetter = String.fromCharCode(64 + rowNum);
                    const rowDiv = document.createElement('div');
                    rowDiv.className = 'seat-row';
                    
                    const rowLabel = document.createElement('div');
                    rowLabel.className = 'row-label';
                    rowLabel.textContent = rowLetter;
                    rowDiv.appendChild(rowLabel);
                    
                    for (let seatNum = 1; seatNum <= stand.seatsPerRow; seatNum++) {
                        const seatId = `${stand.id}_${rowLetter}_${seatNum}`;
                        const category = getCategoryByRow(rowNum);
                        const isSold = soldSeats.has(seatId);
                        
                        const seat = document.createElement('div');
                        seat.className = `seat ${category} ${isSold ? 'sold' : ''}`;
                        seat.dataset.seatId = seatId;
                        seat.dataset.category = category;
                        seat.dataset.price = seatPrices[category];
                        seat.dataset.row = rowLetter;
                        seat.dataset.number = seatNum;
                        seat.dataset.stand = stand.id;
                        
                        if (!isSold) {
                            seat.addEventListener('click', function() {
                                toggleSeatSelection(seatId);
                            });
                        }
                        
                        const seatNumber = document.createElement('span');
                        seatNumber.className = 'seat-number';
                        seatNumber.textContent = seatNum;
                        seat.appendChild(seatNumber);
                        
                        rowDiv.appendChild(seat);
                    }
                    
                    container.appendChild(rowDiv);
                }
            });
            
            updateAvailableSeats();
        }
        
        function getCategoryByRow(rowNum) {
            if (rowNum <= 4) return 'category1';
            if (rowNum <= 8) return 'category2';
            if (rowNum <= 12) return 'category3';
            return 'category4';
        }
        
        function toggleSeatSelection(seatId) {
            const seatElement = document.querySelector(`[data-seat-id="${seatId}"]`);
            
            if (selectedSeats.has(seatId)) {
                selectedSeats.delete(seatId);
                seatElement.classList.remove('selected');
            } else {
                if (selectedSeats.size >= maxSeatsPerOrder) {
                    alert(`Maximum ${maxSeatsPerOrder} seats per order`);
                    return;
                }
                selectedSeats.add(seatId);
                seatElement.classList.add('selected');
            }
            
            updateSelectionUI();
        }
        
        function updateSelectionUI() {
            const seatsArray = Array.from(selectedSeats);
            const addToCartBtn = document.getElementById('add-to-cart-btn');
            const seatCountSpan = document.getElementById('seat-count');
            const confirmPriceSpan = document.getElementById('confirm-price');
            const infoCard = document.getElementById('seat-info-card');
            const selectedSeatsList = document.getElementById('selected-seats-list');
            const totalPriceSpan = document.getElementById('total-price');
            const selectedSeatsJson = document.getElementById('selected_seats_json');
            
            let totalPrice = 0;
            let seatsData = [];
            
            seatsArray.forEach(seatId => {
                const seatElement = document.querySelector(`[data-seat-id="${seatId}"]`);
                totalPrice += parseFloat(seatElement.dataset.price);
                
                seatsData.push({
                    id: seatId,
                    stand: seatElement.dataset.stand,
                    row: seatElement.dataset.row,
                    number: seatElement.dataset.number,
                    category: seatElement.dataset.category,
                    price: parseFloat(seatElement.dataset.price)
                });
            });
            
            selectedSeatsJson.value = JSON.stringify(seatsData);
            
            seatCountSpan.textContent = selectedSeats.size;
            confirmPriceSpan.textContent = totalPrice.toFixed(2);
            totalPriceSpan.textContent = totalPrice.toFixed(2);
            
            addToCartBtn.disabled = selectedSeats.size === 0;
            
            if (selectedSeats.size > 0) {
                infoCard.classList.remove('d-none');
                
                selectedSeatsList.innerHTML = '';
                seatsArray.forEach(seatId => {
                    const seatElement = document.querySelector(`[data-seat-id="${seatId}"]`);
                    const seatDiv = document.createElement('div');
                    seatDiv.className = 'selected-seat-item';
                    seatDiv.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${seatElement.dataset.stand} Stand</strong><br>
                                Row ${seatElement.dataset.row}, Seat ${seatElement.dataset.number}<br>
                                <small class="text-muted">${seatElement.dataset.category.replace('category', 'Category ')}</small>
                            </div>
                            <div class="text-end">
                                <strong>$${parseFloat(seatElement.dataset.price).toFixed(2)}</strong><br>
                                <button type="button" class="btn btn-sm btn-outline-danger mt-1" 
                                        onclick="removeSeat('${seatId}')">Remove</button>
                            </div>
                        </div>
                    `;
                    selectedSeatsList.appendChild(seatDiv);
                });
            } else {
                infoCard.classList.add('d-none');
            }
        }
        
        function removeSeat(seatId) {
            selectedSeats.delete(seatId);
            const seatElement = document.querySelector(`[data-seat-id="${seatId}"]`);
            if (seatElement) {
                seatElement.classList.remove('selected');
            }
            updateSelectionUI();
        }
        
        function clearSelection() {
            selectedSeats.forEach(seatId => {
                const seatElement = document.querySelector(`[data-seat-id="${seatId}"]`);
                if (seatElement) {
                    seatElement.classList.remove('selected');
                }
            });
            selectedSeats.clear();
            updateSelectionUI();
        }
        
        function updateAvailableSeats() {
            const totalSeats = 15*30*2 + 20*20*2;
            const available = totalSeats - soldSeats.size;
            document.getElementById('available-seats').textContent = available;
        }
        
        document.getElementById('stadium-cart-form').addEventListener('submit', function(e) {
            if (selectedSeats.size === 0) {
                e.preventDefault();
                alert('Please select at least one seat');
                return;
            }
            
            const btn = document.getElementById('add-to-cart-btn');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding to Cart...';
            btn.disabled = true;
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            generateSeats();
            updateSelectionUI();
        });
    </script>
</body>
</html>