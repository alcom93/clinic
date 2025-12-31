@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10">

    <h2 class="text-center text-3xl font-bold text-blue-600 mb-8">
        🏨 Tableau de bord des chambres
    </h2>

    {{-- 🔍 Filtres --}}
    <div class="flex flex-wrap justify-center gap-3 mb-10">
        <button class="filter-btn bg-green-100 text-green-700" data-filter="libre">🟢 Libres</button>
        <button class="filter-btn bg-yellow-100 text-yellow-700" data-filter="en_preparation">🟡 Reservées</button>
        <button class="filter-btn bg-blue-100 text-blue-700" data-filter="en_nettoyage">🔵 Nettoyage</button>
        <button class="filter-btn bg-red-100 text-red-700" data-filter="occupee">🔴 Occupées</button>
        <button class="filter-btn bg-gray-200 text-gray-700" data-filter="non_fonctionnelle"> ⚫ Non fonctionnelle</button>
    </div>

    {{-- 🧩 GRID 3 PAR LIGNE --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="rooms-container">

        @foreach($rooms as $room)
            @php
                $colors = [
                    'libre' => ['border'=>'border-green-500','badge'=>'bg-green-500','icon'=>'🟢','label'=>'Libre'],
                    'en_preparation' => ['border'=>'border-yellow-500','badge'=>'bg-yellow-500','icon'=>'🟡','label'=>'Reservé'],
                    'en_nettoyage' => ['border'=>'border-blue-500','badge'=>'bg-blue-500','icon'=>'🔵','label'=>'Nettoyage'],
                    'occupee' => ['border'=>'border-red-500','badge'=>'bg-red-500','icon'=>'🔴','label'=>'Occupée'],
                    'non_fonctionnelle' => ['border'=>'border-gray-500','badge'=>'bg-gray-500','icon'=>'⚫','label'=>'Non fonctionnelle'],
                ];
                $c = $colors[$room->status];

                $beds = $room->beds ?? 1; 
                $admissions = $room->activeAdmissions ?? ($room->activeAdmission ? collect([$room->activeAdmission]) : collect([]));
                $occupied = $admissions->count();
            @endphp

            <div class="room-card-wrapper" data-status="{{ $room->status }}">
                <div class="bg-white rounded-2xl shadow-lg border-2 {{ $c['border'] }} p-6 hover:shadow-2xl transition text-base">


                    {{-- HEADER --}}
                    <div class="relative mb-4 text-center">
  <h3 class="font-bold text-xl">
    {{ $c['icon'] }}
    @if(Str::startsWith($room->number, ['ST', 'REA']))
        {{ $room->number }}
    @else
        CH {{ $room->number }}
    @endif
</h3>


    <span class="absolute top-0 right-0 text-white text-xs px-3 py-1 rounded-full {{ $c['badge'] }}">
        {{ $c['label'] }}
    </span>
</div>


                    {{-- INFOS --}}
                    <div class="text-sm text-gray-600 space-y-1 mb-4">
                        <p><b>🛏️ Lits :</b> {{ $beds }}</p>
                        <p><b>👤 Occupés :</b> {{ $occupied }} / {{ $beds }}</p>

                        @foreach($admissions as $admission)
                            @php $patient = $admission->patient; @endphp
                         <div class="p-3 mb-2 border rounded-lg bg-gray-50 text-lg leading-relaxed">
    <p>
        <span class="font-bold">👤 Patient :</span>
        <span class="font-extrabold text-xl text-gray-900">
            {{ $patient->firstname }} {{ $patient->lastname }}
        </span>
    </p>

    <p class="mt-1">
        <span class="font-bold">🩺 Médecin :</span>
        {{ $patient->doctor ?? '—' }}
    </p>

    <p>
        <span class="font-bold">📋 Motif :</span>
        {{ $admission->motif ?? '—' }}
    </p>

    <p>
        <span class="font-bold">💳 Paiement :</span>
        {{ $admission->payment_mode ?? '—' }}
    </p>
</div>

                        @endforeach
                    </div>

                    {{-- ➕ ADMISSION (SI LITS LIBRES) --}}
                   @if(
    $occupied < $beds &&
    !in_array($room->status, ['en_nettoyage', 'non_fonctionnelle'])
)

                        <button
                            onclick="toggleForm({{ $room->id }})"
                            class="w-full mb-3 bg-green-500 text-white py-2 rounded-xl font-semibold hover:bg-green-600 transition">
                            ➕ Admettre un patient
                        </button>

                        <form
                            method="POST"
                            action="{{ route('admissions.store') }}"
                            id="form-{{ $room->id }}"
                            class="hidden space-y-2 bg-green-50 p-4 rounded-xl">

                            @csrf
                            <input type="hidden" name="room_id" value="{{ $room->id }}">

                            <input type="text" name="patient[firstname]" placeholder="Prénom"
                                class="w-full border rounded-lg p-2" required>

                            <input type="text" name="patient[lastname]" placeholder="Nom"
                                class="w-full border rounded-lg p-2" required>

                            <input type="text" name="patient[doctor]" placeholder="Médecin"
                                class="w-full border rounded-lg p-2">

                            <select name="admission[motif]"
                            class="w-full border rounded-lg p-2 text-lg" required >
                            <option value="">-Sélectionnez le motif-</option>
                               <option value="HOSPITALISATION">HOSPITALISATION</option>
                                  <option value="INTERVENTION">INTERVENTION</option>
                                  <option value="ADN">ADN</option>
                                  <option value="CBT">CBT</option>
                            
                                  </select>

                            <input type="text" name="admission[payment_mode]" placeholder="Mode de paiement"
                                class="w-full border rounded-lg p-2">

                            <button
                                class="w-full bg-green-600 text-white py-2 rounded-lg font-semibold hover:bg-green-700">
                                ✅ Confirmer l’admission
                            </button>
                        </form>
                    @endif

                    {{-- 🔁 STATUT --}}
                    @if($room->status !== 'occupee')
                        <form method="POST" action="{{ route('rooms.updateStatus',$room->id) }}" class="mt-4">
                            @csrf
                            <select name="status" class="w-full border rounded-lg p-2 mb-2">
                                <option value="libre">Libre</option>
                                <option value="en_preparation">Réservée</option>
                                <option value="en_nettoyage">Nettoyage</option>
                                <option value="non_fonctionnelle">Non fonctionnelle</option>

                            </select>
                            <button class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">
                                🔄 Mettre à jour
                            </button>
                        </form>
                    @endif

                    {{-- 🔓 LIBÉRER --}}
                    @if($occupied > 0)
                        @foreach($admissions as $admission)
                            <form method="POST" action="{{ route('admissions.discharge',$admission->id) }}" class="mt-2">
                                @csrf
                                <button class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600">
                                    🔓 Libérer {{ $admission->patient->firstname }}
                                </button>
                            </form>
                        @endforeach
                    @endif

                </div>
            </div>

        @endforeach

    </div>
</div>

{{-- 💡 JS --}}
<script>
document.querySelectorAll('.filter-btn').forEach(btn=>{
    btn.addEventListener('click',()=>{
        const filter = btn.dataset.filter;
        document.querySelectorAll('.room-card-wrapper').forEach(card=>{
            card.style.display =
                card.dataset.status === filter ? 'block' : 'none';
        });
    });
});
function toggleForm(id) {
    const form = document.getElementById('form-' + id);
    form.classList.toggle('hidden');
}
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
