@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="text-center fw-bold mb-5 text-primary">🩺 Liste des admissions</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Âge</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admissions as $admission)
            <tr>
                <td>{{ $admission->name }}</td>
                <td>{{ $admission->age }}</td>
                <td>{{ $admission->status }}</td>
                <td>
                    <form action="{{ route('admissions.discharge', $admission->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Libérer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
