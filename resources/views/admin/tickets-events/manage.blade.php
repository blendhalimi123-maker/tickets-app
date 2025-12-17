@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card">
        <div class="card-header">
            <h3>Manage Game & Prices - ID: {{ $gameId }}</h3>
        </div>
        
        <div class="card-body">
            <form action="{{ route('admin.tickets.update', $gameId) }}" method="POST">
                @csrf
                @method('PUT')
                
                <h4>Game Details</h4>
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label>Game Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $gameData['title'] }}" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label>Stadium/Venue</label>
                        <input type="text" name="stadium" class="form-control" value="{{ $gameData['stadium'] }}" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label>Home Team</label>
                        <input type="text" name="home_team" class="form-control" value="{{ $gameData['home_team'] }}" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label>Away Team</label>
                        <input type="text" name="away_team" class="form-control" value="{{ $gameData['away_team'] }}" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label>Match Date & Time</label>
                        <input type="datetime-local" name="match_date" class="form-control" 
                               value="{{ date('Y-m-d\TH:i', strtotime($gameData['match_date'])) }}" required>
                    </div>
                </div>
                
                <hr>
                
                <h4>Seat Prices (All Stands)</h4>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Category 1 (VIP) Price</label>
                        <input type="number" step="0.01" name="category1" class="form-control" value="{{ $prices['category1'] }}" required>
                        <small class="text-muted">Rows 1-4 (All Stands)</small>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label>Category 2 (Premium) Price</label>
                        <input type="number" step="0.01" name="category2" class="form-control" value="{{ $prices['category2'] }}" required>
                        <small class="text-muted">Rows 5-8 (All Stands)</small>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label>Category 3 (Standard) Price</label>
                        <input type="number" step="0.01" name="category3" class="form-control" value="{{ $prices['category3'] }}" required>
                        <small class="text-muted">Rows 9-12 (All Stands)</small>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label>Category 4 (Economy) Price</label>
                        <input type="number" step="0.01" name="category4" class="form-control" value="{{ $prices['category4'] }}" required>
                        <small class="text-muted">Rows 13+ (All Stands)</small>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Save All Changes</button>
                    <a href="javascript:history.back()" class="btn btn-secondary ms-2">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection