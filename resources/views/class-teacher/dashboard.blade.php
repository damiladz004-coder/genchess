@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <h2 class="text-3xl gc-heading">Class Teacher Dashboard</h2>

    <div class="gc-panel p-4">
        <h3 class="text-lg font-semibold mb-2">Assigned Classes</h3>
        <div class="overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>School</th>
                        <th>Class</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classTeachers as $ct)
                        <tr>
                            <td>{{ $ct->classroom->school->school_name ?? 'N/A' }}</td>
                            <td class="font-medium text-slate-800">{{ $ct->classroom->name ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
