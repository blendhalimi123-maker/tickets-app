<!DOCTYPE html>
<html>
<head>
    <title>Select Seat - {{ $match['team1'] }} vs {{ $match['team2'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .stadium-container {
            position: relative;
            width: 100%;
            height: 600px;
            max-width: 900px;
            margin: 0 auto;
            border: 3px solid #333;
            border-radius: 10px;
            background: #f8f9fa;
            overflow: hidden;
        }
        
        .pitch {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            height: 200px;
            background: #2a8c2a;
            border: 3px solid white;
            border-radius: 5px;
        }
        
        .center-circle {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px;
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
        
        .stand {
            position: absolute;
            background: #e9ecef;
            border: 2px solid #adb5bd;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
        }
        
        .north-stand {
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 550px;
            height: 130px;
        }
        
        .south-stand {
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 550px;
            height: 130px;
        }
        
        .east-stand {
            top: 170px;
            right: 20px;
            width: 130px;
            height: 260px;
        }
        
        .west-stand {
            top: 170px;
            left: 20px;
            width: 130px;
            height: 260px;
        }
        
        .seating-area {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-top: 10px;
            padding: 5px;
        }
        
        .seat-row {
            display: flex;
            gap: 3px;
            align-items: center;
            justify-content: center;
        }
        
        .row-label {
            width: 25px;
            font-size: 11px;
            font-weight: bold;
            color: #495057;
            text-align: center;
        }
        
        .seat {
            width: 20px;
            height: 20px;
            background: #28a745;
            border: 1px solid #1c7430;
            border-radius: 3px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            color: white;
            margin: 1px;
        }
        
        .seat:hover {
            transform: scale(1.1);
        }
        
        .seat.selected {
            background: #007bff;
            border-color: #0056b3;
        }
        
        .seat.sold {
            background: #dc3545;
            cursor: not-allowed;
            opacity: 0.6;
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
            margin-top: 20px;
            flex-wrap: wrap;
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
        
        .seat-info-card {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 300px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            z-index: 1000;
            padding: 15px;
        }
        
        @media (max-width: 768px) {
            .stadium-container {
                height: 500px;
            }
            
            .seat-info-card {
                position: static;
                width: 100%;
                margin-top: 20px;
            }
        }
        
        .selected-seat-item {
            background: #f8f9fa;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            border-left: 4px solid #007bff;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="card mb-4">
            <div class="card-body">
                <h1 class="mb-3">{{ $match['team1'] }} vs {{ $match['team2'] }}</h1>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Date:</strong> {{ $match['match_date']->format('Y-m-d H:i') }}</p>
                        <p><strong>Stadium:</strong> {{ $match['stadium'] }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Match ID:</strong> {{ $match['id'] }}</p>
                        <p><strong>Available Seats:</strong> <span id="available-seats">Loading...</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="stadium-legend mb-4">
            <div class="legend-item">
                <div class="seat-sample category1"></div>
                <span>Category 1 - Premium</span>
            </div>
            <div class="legend-item">
                <div class="seat-sample category2"></div>
                <span>Category 2 - Standard Plus</span>
            </div>
            <div class="legend-item">
                <div class="seat-sample category3"></div>
                <span>Category 3 - Standard</span>
            </div>
            <div class="legend-item">
                <div class="seat-sample category4"></div>
                <span>Category 4 - Economy</span>
            </div>
            <div class="legend-item">
                <div class="seat-sample sold"></div>
                <span>Sold/Unavailable</span>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="text-center mb-4">Select Your Seats</h4>
                
                <div class="stadium-container" id="stadium-container">
                    <div class="pitch">
                        <div class="center-circle"></div>
                        <div class="center-line"></div>
                    </div>
                    
                    <div class="stand north-stand">
                        <h6>North Stand</h6>
                        <div class="seating-area" id="north-stand-seats"></div>
                    </div>
                    
                    <div class="stand south-stand">
                        <h6>South Stand</h6>
                        <div class="seating-area" id="south-stand-seats"></div>
                    </div>
                    
                    <div class="stand east-stand">
                        <h6>East Stand</h6>
                        <div class="seating-area" id="east-stand-seats"></div>
                    </div>
                    
                    <div class="stand west-stand">
                        <h6>West Stand</h6>
                        <div class="seating-area" id="west-stand-seats"></div>
                    </div>
                </div>
                
                <div id="seat-info-card" class="seat-info-card d-none">
                    <h5>Selected Seats</h5>
                    <div id="selected-seats-list"></div>
                    <div class="mt-3">
                        <p><strong>Total:</strong> £<span id="total-price">0.00</span></p>
                        <button type="button" class="btn btn-danger w-100" onclick="clearSelection()">
                            Clear All Seats
                        </button>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('cart.addStadiumSeats') }}" class="mt-4" id="stadium-cart-form">
                    @csrf
                    <input type="hidden" name="match_id" value="{{ $match['id'] }}">
                    <input type="hidden" name="match_info" value="{{ json_encode($match) }}">
                    <input type="hidden" name="selected_seats" id="selected_seats" value="[]">
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="javascript:history.back()" class="btn btn-secondary btn-lg me-md-2">Back</a>
                        
                        <button type="button" class="btn btn-danger btn-lg me-md-2" onclick="clearSelection()">
                            Clear All Seats
                        </button>
                        
                        <button type="submit" class="btn btn-success btn-lg" id="add-to-cart-btn" disabled>
                            <i class="fas fa-cart-plus"></i> Add <span id="seat-count">0</span> Seat(s) to Cart - £<span id="confirm-price">0.00</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let selectedSeats = new Set();
        const seatPrices = {
            'category1': 85.00,
            'category2': 65.00,
            'category3': 45.00,
            'category4': 35.00
        };
        
        const maxSeatsPerOrder = 6;
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
                    alert(`Maximum ${maxSeatsPerOrder} tickets per order`);
                    return;
                }
                selectedSeats.add(seatId);
                seatElement.classList.add('selected');
            }
            
            updateSelectionUI();
        }
        
        function updateSelectionUI() {
            const seatsArray = Array.from(selectedSeats);
            const selectedSeatsInput = document.getElementById('selected_seats');
            const addToCartBtn = document.getElementById('add-to-cart-btn');
            const seatCountSpan = document.getElementById('seat-count');
            const confirmPriceSpan = document.getElementById('confirm-price');
            const infoCard = document.getElementById('seat-info-card');
            const selectedSeatsList = document.getElementById('selected-seats-list');
            const totalPriceSpan = document.getElementById('total-price');
            
            const seatsData = seatsArray.map(seatId => {
                const seatElement = document.querySelector(`[data-seat-id="${seatId}"]`);
                return {
                    id: seatId,
                    stand: seatElement.dataset.stand,
                    row: seatElement.dataset.row,
                    number: seatElement.dataset.number,
                    category: seatElement.dataset.category,
                    price: parseFloat(seatElement.dataset.price)
                };
            });
            
            selectedSeatsInput.value = JSON.stringify(seatsData);
            
            let totalPrice = 0;
            seatsArray.forEach(seatId => {
                const seatElement = document.querySelector(`[data-seat-id="${seatId}"]`);
                totalPrice += parseFloat(seatElement.dataset.price);
            });
            
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
                                <strong>£${parseFloat(seatElement.dataset.price).toFixed(2)}</strong><br>
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