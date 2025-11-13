@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="text-center fw-bold mb-4 text-primary"><button class="btn btn-outline-primary filter-btn active" data-filter="all">🏨 Tableau de bord des chambres </button></h2>

    {{-- 🔍 Filtres de statut --}}
    <div class="text-center mb-5">
       {{-- <button class="btn btn-outline-primary filter-btn active" data-filter="all">Toutes</button> --}}
        <button class="btn btn-outline-success filter-btn" data-filter="libre">Libres 🟢</button>
        <button class="btn btn-outline-warning filter-btn" data-filter="en_preparation">En préparation 🟡</button>
        <button class="btn btn-outline-info filter-btn" data-filter="en_nettoyage">En nettoyage 🔵</button>
        <button class="btn btn-outline-danger filter-btn" data-filter="occupee">Occupées 🔴</button>
    </div>

    {{-- 🧩 Grille des chambres --}}
    <div class="row justify-content-center" id="rooms-container">
        @foreach($rooms as $room)
            @php
                switch($room->status){
                    case 'libre': 
                        $bg='border-success'; $badge='success'; $icon='🟢'; $statusLabel='Libre'; break;
                    case 'en_preparation': 
                        $bg='border-warning'; $badge='warning'; $icon='🟡'; $statusLabel='En préparation'; break;
                    case 'en_nettoyage': 
                        $bg='border-info'; $badge='info'; $icon='🔵'; $statusLabel='En nettoyage'; break;
                    case 'occupee': 
                        $bg='border-danger'; $badge='danger'; $icon='🔴'; $statusLabel='Occupée'; break;
                    default: 
                        $bg='border-secondary'; $badge='secondary'; $icon='⚪'; $statusLabel='Inconnu';
                }
                $patient = $room->activeAdmission ? $room->activeAdmission->patient : null;
            @endphp

            <div class="col-lg-4 col-md-6 col-sm-12 mb-4 room-card-wrapper" data-status="{{ $room->status }}">
                <div class="card shadow-sm border-3 {{ $bg }} room-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">{{ $icon }} Chambre {{ $room->number }}</h5>
                            <span class="badge bg-{{ $badge }}">{{ $statusLabel }}</span>
                        </div>

                        <div class="mb-3 small text-muted">
                            <p class="mb-1"><strong>👤 Patient :</strong> {{ $patient ? $patient->firstname.' '.$patient->lastname : '-' }}</p>
                            <p class="mb-1"><strong>🩺 Médecin :</strong> {{ $patient ? $patient->doctor : '-' }}</p>
                            <p class="mb-1"><strong>📋 Motif :</strong> {{ $room->activeAdmission ? $room->activeAdmission->motif : '-' }}</p>
                            <p class="mb-1"><strong>💳 Paiement :</strong> {{ $room->activeAdmission ? $room->activeAdmission->payment_mode : '-' }}</p>
                        </div>

                        {{-- 🔁 Modifier le statut (seulement si la chambre n’est pas occupée) --}}
                        @if($room->status != 'occupee')
                        <form method="POST" action="{{ route('rooms.updateStatus', $room->id) }}" class="mb-3">
                            @csrf
                            <div class="input-group">
                                <select name="status" class="form-select" required>
                                    <option value="libre" {{ $room->status=='libre'?'selected':'' }}>Libre 🟢</option>
                                    <option value="en_preparation" {{ $room->status=='en_preparation'?'selected':'' }}>En préparation 🟡</option>
                                    <option value="en_nettoyage" {{ $room->status=='en_nettoyage'?'selected':'' }}>En nettoyage 🔵</option>
                                </select>
                                <button type="submit" class="btn btn-outline-primary">Mettre à jour</button>
                            </div>
                        </form>
                        @endif

                        {{-- 🔓 Libérer la chambre --}}
                        @if($room->status=='occupee' && $room->activeAdmission)
                            <form method="POST" action="{{ route('admissions.discharge', $room->activeAdmission->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100 mb-2">
                                    🔓 Libérer la chambre
                                </button>
                            </form>
                        @endif

                        {{-- ➕ Ajouter un patient --}}
                        @if(in_array($room->status,['libre','en_preparation']) && !$room->activeAdmission)
                            <button class="btn btn-outline-success w-100 mb-2 toggle-form" data-id="{{ $room->id }}">
                                ➕ Ajouter un patient
                            </button>

                            <form method="POST" action="{{ route('admissions.store') }}" id="form-{{ $room->id }}" style="display:none;">
                                @csrf
                                <input type="hidden" name="room_id" value="{{ $room->id }}">
                                <div class="mb-2"><input type="text" name="patient[firstname]" placeholder="Prénom" class="form-control" required></div>
                                <div class="mb-2"><input type="text" name="patient[lastname]" placeholder="Nom" class="form-control" required></div>
                                <div class="mb-2"><input type="text" name="patient[doctor]" placeholder="Médecin" class="form-control"></div>
                                <div class="mb-2"><input type="text" name="admission[motif]" placeholder="Motif" class="form-control"></div>
                                <div class="mb-3"><input type="text" name="admission[payment_mode]" placeholder="Mode de règlement" class="form-control"></div>
                                <button type="submit" class="btn btn-success w-100">✅ Admettre</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- 💡 JS --}}
<script>
document.querySelectorAll('.toggle-form').forEach(btn=>{
    btn.addEventListener('click',()=>{
        const id = btn.dataset.id;
        const form = document.getElementById('form-'+id);
        form.style.display = form.style.display==='none'?'block':'none';
    });
});

const filterButtons = document.querySelectorAll('.filter-btn');
const cards = document.querySelectorAll('.room-card-wrapper');

filterButtons.forEach(btn=>{
    btn.addEventListener('click',()=>{
        filterButtons.forEach(b=>b.classList.remove('active'));
        btn.classList.add('active');
        const filter = btn.dataset.filter;

        cards.forEach(card=>{
            if(filter === 'all' || card.dataset.status === filter){
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>
{{-- 💅 Styles --}}
<style>
.room-card {
    border-radius: 1rem;
    transition: all 0.25s ease-in-out;
    background: #fff;
}
.room-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}
.filter-btn {
    margin: 0 .25rem;
    border-radius: 50px;
    font-weight: 500;
    transition: 0.2s;
}
.filter-btn.active {
    background-color: #0d6efd;
    color: #fff !important;
}
</style>
@endsection
