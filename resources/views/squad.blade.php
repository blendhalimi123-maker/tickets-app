@extends('layouts.app')

@section('title', 'Team Squad')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-gradient rounded-4 p-4 d-flex align-items-center justify-content-between shadow-sm squad-hero">
                <div>
                    <h2 class="mb-1 fw-bold">Team Squad</h2>
                    <p class="mb-0 text-muted">Browse players, numbers and positions. Team ID: <span class="fw-semibold">{{ $teamId ?? '—' }}</span></p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="text-end">
                        <div class="small text-muted">Players</div>
                        <div class="h5 mb-0">{{ $squad->count() }}</div>
                    </div>
                    <button class="btn btn-outline-light" onclick="history.back()"><i class="fas fa-arrow-left me-1"></i> Back</button>
                </div>
            </div>
        </div>

        <div class="col-12">
            @if(!empty($error))
                <div class="alert alert-danger">{{ $error }}</div>
            @endif

            @if($squad->isEmpty())
                <div class="alert alert-info">No squad information available.</div>
            @else
                <div class="mb-3 d-flex justify-content-end">
                    <input id="squadSearch" class="form-control form-control-sm" placeholder="Search players..." style="max-width: 320px;">
                </div>

                @php
                    $groups = $squad->groupBy(function ($m) { return $m['position'] ?? 'Other'; });
                    $positionOrder = ['Goalkeeper', 'Defender', 'Midfielder', 'Attacker'];
                    $positionLabels = ['Goalkeeper' => 'Keepers', 'Defender' => 'Defenders', 'Midfielder' => 'Midfielders', 'Attacker' => 'Attackers'];
                @endphp

                @foreach($positionOrder as $pos)
                    @php $group = $groups->get($pos) ?? collect(); @endphp
                    @if($group->isNotEmpty())
                        <h5 class="mt-4 mb-3 text-uppercase text-muted small">{{ $positionLabels[$pos] ?? $pos }}</h5>
                        <div class="row g-3" id="group-{{ Illuminate\Support\Str::slug($pos) }}">
                            @foreach($group as $member)
                                <div class="col-6 col-sm-4 col-md-3">
                                    <div class="card player-card h-100 border-0 shadow-sm">
                                        <div class="card-body d-flex flex-column align-items-center text-center p-3">
                                            @if(!empty($member['image']))
                                                <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}" class="player-avatar mb-2 rounded-circle" onerror="this.style.display='none'">
                                            @else
                                                <div class="player-avatar mb-2 rounded-circle bg-light d-flex align-items-center justify-content-center text-muted">—</div>
                                            @endif

                                            <div class="fw-bold">{{ $member['name'] ?? '—' }}</div>
                                            <div class="small text-muted mb-2">{{ $member['position'] ?? '—' }}</div>

                                            <div class="d-flex gap-2 mt-auto align-items-center w-100 justify-content-center">
                                                @if(!empty($member['number']))
                                                    <span class="badge bg-primary px-3 py-2">#{{ $member['number'] }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach

                @foreach($groups->except($positionOrder) as $pos => $group)
                    @if($group->isNotEmpty())
                        <h5 class="mt-4 mb-3 text-uppercase text-muted small">{{ $pos ?: 'Other' }}</h5>
                        <div class="row g-3">
                            @foreach($group as $member)
                                <div class="col-6 col-sm-4 col-md-3">
                                    <div class="card player-card h-100 border-0 shadow-sm">
                                        <div class="card-body d-flex flex-column align-items-center text-center p-3">
                                            @if(!empty($member['image']))
                                                <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}" class="player-avatar mb-2 rounded-circle" onerror="this.style.display='none'">
                                            @else
                                                <div class="player-avatar mb-2 rounded-circle bg-light d-flex align-items-center justify-content-center text-muted">—</div>
                                            @endif

                                            <div class="fw-bold">{{ $member['name'] ?? '—' }}</div>
                                            <div class="small text-muted mb-2">{{ $member['position'] ?? '—' }}</div>

                                            <div class="d-flex gap-2 mt-auto align-items-center w-100 justify-content-center">
                                                @if(!empty($member['number']))
                                                    <span class="badge bg-primary px-3 py-2">#{{ $member['number'] }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach

            @endif
        </div>

        @if(!$squad->isEmpty())
        <div class="col-12">
            <div class="lineup-builder-wrap rounded-4 shadow overflow-hidden">
                <div class="lineup-header d-flex align-items-center justify-content-between px-4 py-3">
                    <div class="d-flex align-items-center gap-3">
                        <!-- <i class="fas fa-futbol lineup-icon"></i> -->
                        <div>
                            <h4 class="mb-0 fw-bold text-white">Create Your Lineup</h4>
                            <span class="lineup-sub">Click a slot then pick a player</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <select id="formationSelect" class="form-select form-select-sm lineup-select">
                            <option value="4-3-3" selected>4-3-3</option>
                            <option value="4-4-2">4-4-2</option>
                            <option value="3-5-2">3-5-2</option>
                            <option value="3-4-3">3-4-3</option>
                            <option value="4-2-3-1">4-2-3-1</option>
                            <option value="5-3-2">5-3-2</option>
                            <option value="4-1-4-1">4-1-4-1</option>
                        </select>
                        <button id="resetLineup" class="btn btn-sm btn-outline-light"><i class="fas fa-undo me-1"></i>Reset</button>
                    </div>
                </div>

                <div class="pitch-container" id="pitchContainer">
                    <svg class="pitch-svg" viewBox="0 0 600 880" preserveAspectRatio="xMidYMid meet">
                        <rect x="0" y="0" width="600" height="880" rx="12" fill="url(#grassGrad)"/>
                        <defs>
                            <linearGradient id="grassGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#2a2a3a"/>
                                <stop offset="50%" stop-color="#23233a"/>
                                <stop offset="100%" stop-color="#2a2a3a"/>
                            </linearGradient>
                        </defs>
                        <rect x="30" y="20" width="540" height="840" fill="none" stroke="rgba(255,255,255,0.18)" stroke-width="2"/>
                        <line x1="30" y1="440" x2="570" y2="440" stroke="rgba(255,255,255,0.18)" stroke-width="2"/>
                        <circle cx="300" cy="440" r="70" fill="none" stroke="rgba(255,255,255,0.18)" stroke-width="2"/>
                        <circle cx="300" cy="440" r="4" fill="rgba(255,255,255,0.25)"/>
                        <rect x="155" y="20" width="290" height="140" fill="none" stroke="rgba(255,255,255,0.18)" stroke-width="2"/>
                        <rect x="210" y="20" width="180" height="55" fill="none" stroke="rgba(255,255,255,0.18)" stroke-width="2"/>
                        <circle cx="300" cy="125" r="4" fill="rgba(255,255,255,0.25)"/>
                        <path d="M 230 20 A 70 70 0 0 1 370 20" fill="none" stroke="rgba(255,255,255,0.18)" stroke-width="2"/>
                        <rect x="155" y="720" width="290" height="140" fill="none" stroke="rgba(255,255,255,0.18)" stroke-width="2"/>
                        <rect x="210" y="805" width="180" height="55" fill="none" stroke="rgba(255,255,255,0.18)" stroke-width="2"/>
                        <circle cx="300" cy="755" r="4" fill="rgba(255,255,255,0.25)"/>
                        <path d="M 230 860 A 70 70 0 0 0 370 860" fill="none" stroke="rgba(255,255,255,0.18)" stroke-width="2"/>
                    </svg>
                    <div class="pitch-slots" id="pitchSlots"></div>
                    <div class="pitch-picker" id="pitchPicker">
                        <div class="pitch-picker-header">
                            <span class="pitch-picker-title"><i class="fas fa-user-plus me-1"></i>Pick Player</span>
                            <button class="pitch-picker-close" id="pickerClose"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="pitch-picker-search">
                            <input id="pickerSearch" class="form-control form-control-sm" placeholder="Search...">
                        </div>
                        <div class="pitch-picker-list" id="pickerList"></div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .squad-hero {
        background: linear-gradient(135deg, rgba(32,124,229,0.12), rgba(88,24,189,0.08));
    }
    .player-card { transition: transform .18s ease, box-shadow .18s ease; }
    .player-card:hover { transform: translateY(-6px); box-shadow: 0 12px 30px rgba(15,23,42,0.12); }
    .player-avatar { width:72px; height:72px; object-fit:cover; border:4px solid rgba(255,255,255,0.6); box-shadow: 0 6px 18px rgba(2,6,23,0.06); }
    @media (prefers-color-scheme: dark) {
        .player-avatar { border-color: rgba(255,255,255,0.06); }
    }

    .lineup-builder-wrap {
        background: #1a1a2e;
    }
    .lineup-header {
        background: linear-gradient(135deg, #38003c 0%, #1a0020 100%);
    }
    .lineup-icon {
        font-size: 1.6rem;
        color: #00ff85;
        animation: spinBall 4s linear infinite;
    }
    @keyframes spinBall { 0%{transform:rotate(0)} 100%{transform:rotate(360deg)} }
    .lineup-sub {
        color: rgba(255,255,255,0.5);
        font-size: .82rem;
    }
    .lineup-select {
        background: rgba(255,255,255,0.1);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.2);
        font-weight: 600;
        min-width: 110px;
    }
    .lineup-select option { background: #1e293b; color: #fff; }

    .pitch-container {
        position: relative;
        width: 100%;
        max-width: 520px;
        margin: 20px auto 30px;
        aspect-ratio: 600/880;
    }
    .pitch-svg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        border-radius: 12px;
    }
    .pitch-slots {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
    }

    .pitch-slot {
        position: absolute;
        transform: translate(-50%, -50%);
        width: 90px;
        text-align: center;
        cursor: pointer;
        transition: transform .2s ease;
        z-index: 2;
    }
    .pitch-slot:hover { transform: translate(-50%, -50%) scale(1.08); }

    .slot-circle {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        border: 3px solid rgba(255,255,255,0.25);
        background: rgba(0,0,0,0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        transition: all .25s ease;
        position: relative;
        overflow: hidden;
    }
    .slot-circle .slot-plus {
        font-size: 1.4rem;
        color: rgba(255,255,255,0.45);
        transition: color .2s;
    }
    .pitch-slot:hover .slot-circle {
        border-color: #ffd700;
        box-shadow: 0 0 20px rgba(255,215,0,0.3);
    }
    .pitch-slot:hover .slot-plus { color: #ffd700; }

    .pitch-slot.filled .slot-circle {
        border: 3px solid #ffd700;
        background: transparent;
        box-shadow: 0 0 16px rgba(255,215,0,0.2);
    }
    .slot-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .slot-name-tag {
        margin-top: 4px;
        display: inline-block;
        background: rgba(0,0,0,0.7);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 4px;
        padding: 2px 8px;
        max-width: 90px;
    }
    .slot-label {
        font-size: .68rem;
        text-transform: uppercase;
        letter-spacing: .3px;
        color: rgba(255,255,255,0.5);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .slot-label-plus {
        font-size: .5rem;
        margin-right: 2px;
        color: #ffd700;
    }
    .pitch-slot.filled .slot-label {
        color: #fff;
        font-weight: 700;
        font-size: .72rem;
    }

    .slot-number {
        position: absolute;
        bottom: -3px;
        right: -3px;
        background: #ffd700;
        color: #1a1a2e;
        font-size: .6rem;
        font-weight: 800;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.4);
    }

    .slot-remove {
        position: absolute;
        top: -4px;
        right: 8px;
        background: #ef4444;
        color: #fff;
        border: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        font-size: .6rem;
        display: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 3;
        padding: 0;
        line-height: 1;
    }
    .pitch-slot.filled:hover .slot-remove { display: flex; }

    .pitch-picker {
        position: absolute;
        right: 8px;
        top: 8px;
        bottom: 8px;
        width: 240px;
        background: rgba(15,20,35,0.95);
        backdrop-filter: blur(12px);
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.1);
        display: none;
        flex-direction: column;
        z-index: 20;
        box-shadow: -4px 0 24px rgba(0,0,0,0.5);
        overflow: hidden;
    }
    .pitch-picker.open { display: flex; }
    .pitch-picker-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        background: rgba(56,0,60,0.6);
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    .pitch-picker-title {
        color: #fff;
        font-weight: 700;
        font-size: .8rem;
    }
    .pitch-picker-close {
        background: none;
        border: none;
        color: rgba(255,255,255,0.5);
        font-size: .85rem;
        cursor: pointer;
        padding: 2px 6px;
        border-radius: 6px;
        transition: all .15s;
    }
    .pitch-picker-close:hover { color: #fff; background: rgba(255,255,255,0.1); }
    .pitch-picker-search {
        padding: 8px 10px;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .pitch-picker-search input {
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.12);
        color: #fff;
        font-size: .78rem;
        border-radius: 8px;
    }
    .pitch-picker-search input::placeholder { color: rgba(255,255,255,0.35); }
    .pitch-picker-list {
        flex: 1;
        overflow-y: auto;
        padding: 4px 6px 8px;
    }
    .pitch-picker-list::-webkit-scrollbar { width: 4px; }
    .pitch-picker-list::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }
    .picker-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 8px;
        border-radius: 8px;
        cursor: pointer;
        transition: background .15s;
        color: #e2e8f0;
        margin-top: 2px;
    }
    .picker-item:hover { background: rgba(255,215,0,0.12); }
    .picker-item.used { opacity: .3; pointer-events: none; }
    .picker-item img {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255,255,255,0.15);
        flex-shrink: 0;
    }
    .picker-item .pi-placeholder {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #334155;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        font-size: .7rem;
        flex-shrink: 0;
    }
    .picker-item .pi-info { min-width: 0; flex: 1; }
    .picker-item .pi-name { font-weight: 600; font-size: .75rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .picker-item .pi-pos { font-size: .62rem; color: #94a3b8; }
    .picker-item .pi-num {
        margin-left: auto;
        background: #ffd700;
        color: #1a1a2e;
        font-weight: 800;
        font-size: .6rem;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    @media (max-width: 640px) {
        .pitch-picker {
            right: 4px;
            top: 4px;
            bottom: 4px;
            width: 190px;
        }
    }

    @media (max-width: 576px) {
        .pitch-container { max-width: 100%; }
        .pitch-slot { width: 70px; }
        .slot-circle { width: 48px; height: 48px; }
        .slot-label { font-size: .58rem; }
        .slot-name-tag { max-width: 70px; padding: 1px 5px; }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const search = document.getElementById('squadSearch');
    if (search) {
        search.addEventListener('input', function(){
            const q = this.value.trim().toLowerCase();
            document.querySelectorAll('.player-card').forEach(card => {
                const name = (card.querySelector('.fw-bold')?.textContent || '').toLowerCase();
                const col = card.closest('[class*="col-"]');
                if (!col) return;
                col.style.display = (!q || name.includes(q)) ? '' : 'none';
            });
        });
    }

    const allPlayers = @json($squad->values()->toArray());

    const formations = {
        '4-3-3': [
            {x:50,y:91,role:'GK'},
            {x:15,y:74,role:'LB'},{x:38,y:76,role:'CB'},{x:62,y:76,role:'CB'},{x:85,y:74,role:'RB'},
            {x:25,y:52,role:'CM'},{x:50,y:50,role:'CM'},{x:75,y:52,role:'CM'},
            {x:18,y:28,role:'LW'},{x:50,y:24,role:'ST'},{x:82,y:28,role:'RW'}
        ],
        '4-4-2': [
            {x:50,y:91,role:'GK'},
            {x:15,y:74,role:'LB'},{x:38,y:76,role:'CB'},{x:62,y:76,role:'CB'},{x:85,y:74,role:'RB'},
            {x:15,y:52,role:'LM'},{x:38,y:54,role:'CM'},{x:62,y:54,role:'CM'},{x:85,y:52,role:'RM'},
            {x:35,y:28,role:'ST'},{x:65,y:28,role:'ST'}
        ],
        '3-5-2': [
            {x:50,y:91,role:'GK'},
            {x:25,y:76,role:'CB'},{x:50,y:78,role:'CB'},{x:75,y:76,role:'CB'},
            {x:10,y:52,role:'LWB'},{x:33,y:54,role:'CM'},{x:50,y:50,role:'CM'},{x:67,y:54,role:'CM'},{x:90,y:52,role:'RWB'},
            {x:35,y:26,role:'ST'},{x:65,y:26,role:'ST'}
        ],
        '3-4-3': [
            {x:50,y:91,role:'GK'},
            {x:25,y:76,role:'CB'},{x:50,y:78,role:'CB'},{x:75,y:76,role:'CB'},
            {x:15,y:54,role:'LM'},{x:40,y:56,role:'CM'},{x:60,y:56,role:'CM'},{x:85,y:54,role:'RM'},
            {x:18,y:28,role:'LW'},{x:50,y:24,role:'ST'},{x:82,y:28,role:'RW'}
        ],
        '4-2-3-1': [
            {x:50,y:91,role:'GK'},
            {x:15,y:76,role:'LB'},{x:38,y:78,role:'CB'},{x:62,y:78,role:'CB'},{x:85,y:76,role:'RB'},
            {x:35,y:60,role:'CDM'},{x:65,y:60,role:'CDM'},
            {x:18,y:40,role:'LW'},{x:50,y:38,role:'CAM'},{x:82,y:40,role:'RW'},
            {x:50,y:22,role:'ST'}
        ],
        '5-3-2': [
            {x:50,y:91,role:'GK'},
            {x:10,y:72,role:'LWB'},{x:30,y:76,role:'CB'},{x:50,y:78,role:'CB'},{x:70,y:76,role:'CB'},{x:90,y:72,role:'RWB'},
            {x:25,y:52,role:'CM'},{x:50,y:50,role:'CM'},{x:75,y:52,role:'CM'},
            {x:35,y:26,role:'ST'},{x:65,y:26,role:'ST'}
        ],
        '4-1-4-1': [
            {x:50,y:91,role:'GK'},
            {x:15,y:76,role:'LB'},{x:38,y:78,role:'CB'},{x:62,y:78,role:'CB'},{x:85,y:76,role:'RB'},
            {x:50,y:60,role:'CDM'},
            {x:15,y:42,role:'LM'},{x:38,y:44,role:'CM'},{x:62,y:44,role:'CM'},{x:85,y:42,role:'RM'},
            {x:50,y:22,role:'ST'}
        ]
    };

    let currentFormation = '4-3-3';
    let slotAssignments = {};
    let activeSlotIdx = null;

    const pitchSlots = document.getElementById('pitchSlots');
    const formationSelect = document.getElementById('formationSelect');
    const resetBtn = document.getElementById('resetLineup');
    const pitchPicker = document.getElementById('pitchPicker');
    const pickerList = document.getElementById('pickerList');
    const pickerSearch = document.getElementById('pickerSearch');
    const pickerClose = document.getElementById('pickerClose');

    function renderSlots() {
        pitchSlots.innerHTML = '';
        const slots = formations[currentFormation];
        slots.forEach((s, i) => {
            const div = document.createElement('div');
            div.className = 'pitch-slot';
            div.style.left = s.x + '%';
            div.style.top = s.y + '%';

            const assigned = slotAssignments[currentFormation]?.[i];
            if (assigned) {
                div.classList.add('filled');
                let imgHtml = '';
                if (assigned.image) {
                    imgHtml = '<img src="'+assigned.image+'" alt="" onerror="this.style.display=\'none\'">';
                } else {
                    imgHtml = '<span style="color:#fff;font-weight:700;font-size:.9rem;">'+(assigned.name?assigned.name.charAt(0):'?')+'</span>';
                }
                let numHtml = assigned.number ? '<span class="slot-number">'+assigned.number+'</span>' : '';
                div.innerHTML = '<button class="slot-remove" data-idx="'+i+'"><i class="fas fa-times"></i></button><div class="slot-circle">'+imgHtml+numHtml+'</div><div class="slot-name-tag"><div class="slot-label">'+shortenName(assigned.name)+'</div></div>';
            } else {
                div.innerHTML = '<div class="slot-circle"><span class="slot-plus"><i class="fas fa-plus"></i></span></div><div class="slot-name-tag"><div class="slot-label"><i class="fas fa-plus slot-label-plus"></i> '+s.role+'</div></div>';
            }

            div.addEventListener('click', function(e) {
                if (e.target.closest('.slot-remove')) {
                    e.stopPropagation();
                    removeFromSlot(i);
                    return;
                }
                openPicker(i);
            });

            pitchSlots.appendChild(div);
        });
    }

    function shortenName(name) {
        if (!name) return '—';
        const parts = name.trim().split(/\s+/);
        if (parts.length <= 1) return name;
        return parts[parts.length - 1];
    }

    function usedPlayerNames() {
        const assignments = slotAssignments[currentFormation] || {};
        return Object.values(assignments).map(p => p.name);
    }

    function openPicker(slotIdx) {
        activeSlotIdx = slotIdx;
        if (pickerSearch) pickerSearch.value = '';
        renderPickerList('');
        pitchPicker.classList.add('open');
        setTimeout(() => pickerSearch && pickerSearch.focus(), 100);
    }

    function closePicker() {
        pitchPicker.classList.remove('open');
        activeSlotIdx = null;
    }

    if (pickerClose) {
        pickerClose.addEventListener('click', closePicker);
    }

    function renderPickerList(query) {
        const used = usedPlayerNames();
        const q = (query || '').toLowerCase();
        let html = '';
        allPlayers.forEach((p, pi) => {
            if (q && !(p.name || '').toLowerCase().includes(q)) return;
            const isUsed = used.includes(p.name);
            const cls = isUsed ? 'picker-item used' : 'picker-item';
            let avatar = '';
            if (p.image) {
                avatar = '<img src="'+p.image+'" alt="" onerror="this.style.display=\'none\'">';
            } else {
                avatar = '<div class="pi-placeholder"><i class="fas fa-user"></i></div>';
            }
            let numBadge = p.number ? '<div class="pi-num">'+p.number+'</div>' : '';
            html += '<div class="'+cls+'" data-pidx="'+pi+'">'+avatar+'<div class="pi-info"><div class="pi-name">'+(p.name||'—')+'</div><div class="pi-pos">'+(p.position||'—')+'</div></div>'+numBadge+'</div>';
        });
        if (!html) html = '<div class="text-center text-muted py-4">No players found</div>';
        pickerList.innerHTML = html;

        pickerList.querySelectorAll('.picker-item:not(.used)').forEach(el => {
            el.addEventListener('click', function() {
                const idx = parseInt(this.dataset.pidx);
                assignPlayer(activeSlotIdx, allPlayers[idx]);
                closePicker();
            });
        });
    }

    function assignPlayer(slotIdx, player) {
        if (!slotAssignments[currentFormation]) slotAssignments[currentFormation] = {};
        slotAssignments[currentFormation][slotIdx] = player;
        renderSlots();
    }

    function removeFromSlot(slotIdx) {
        if (slotAssignments[currentFormation]) {
            delete slotAssignments[currentFormation][slotIdx];
        }
        renderSlots();
    }

    if (pickerSearch) {
        pickerSearch.addEventListener('input', function(){
            renderPickerList(this.value);
        });
    }

    if (formationSelect) {
        formationSelect.addEventListener('change', function(){
            currentFormation = this.value;
            renderSlots();
        });
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', function(){
            slotAssignments[currentFormation] = {};
            renderSlots();
        });
    }

    renderSlots();
});
</script>
@endpush

@endsection
