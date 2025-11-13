@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-center">Admettre un patient</h1>

    <div class="card shadow rounded-4">
        <div class="card-body">
            <form method="POST" action="{{ route('admissions.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Prénom</label>
                        <input type="text" name="patient[firstname]" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nom</label>
                        <input type="text" name="patient[lastname]" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Médecin traitant</label>
                        <input type="text" name="patient[doctor]" class="form-control" placeholder="Nom du médecin" required>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label">Chambre</label>
                    <select name="room_id" class="form-select" required>
                        @foreach($rooms as $room)
                            @if($room->status == 'libre')
                                <option value="{{ $room->id }}">Chambre n°{{ $room->number }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success mt-3 w-100">Admettre</button>
            </form>
        </div>
    </div>
</div>
@endsection
