<!DOCTYPE html>
<html>
<head>
    <title>Select Seat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Select Seat for Match</h1>
        
        <div class="card mb-4">
            <div class="card-body">
                <h3>Match: {{ $match['team1'] }} vs {{ $match['team2'] }}</h3>
                <p>Date: {{ $match['match_date']->format('Y-m-d H:i') }}</p>
                <p>Stadium: {{ $match['stadium'] }}</p>
                <p>Match ID: {{ $match['id'] }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4>Stadium Layout</h4>
                <div class="text-center">
                    <!-- <div class="bg-success text-white p-3 mb-4">PITCH</div>
                     -->
                    <div class="stadium-seats">
                        @for($row = 'A'; $row <= 'E'; $row++)
                            <div class="d-flex justify-content-center mb-2">
                                @for($seat = 1; $seat <= 20; $seat++)
                                    <button class="btn btn-outline-primary mx-1 seat-btn" 
                                            style="width: 40px;"
                                            data-seat="{{ $row }}{{ $seat }}">
                                        {{ $seat }}
                                    </button>
                                @endfor
                            </div>
                        @endfor
                    </div>
                </div>

                <form method="POST" action="{{ route('stadium.select') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="match_id" value="{{ $match['id'] }}">
                    <input type="hidden" name="selected_seat" id="selected_seat">
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-success btn-lg" id="confirm-btn" disabled>
                            Confirm Seat
                        </button>
                        <a href="javascript:history.back()" class="btn btn-secondary btn-lg">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const seatButtons = document.querySelectorAll('.seat-btn');
            const selectedSeatInput = document.getElementById('selected_seat');
            const confirmBtn = document.getElementById('confirm-btn');
            
            seatButtons.forEach(button => {
                button.addEventListener('click', function() {
                    seatButtons.forEach(btn => {
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-outline-primary');
                    });
                    
                    this.classList.remove('btn-outline-primary');
                    this.classList.add('btn-primary');
                    
                    const seat = this.dataset.seat;
                    selectedSeatInput.value = seat;
                    confirmBtn.disabled = false;
                });
            });
        });
    </script>
</body>
</html>