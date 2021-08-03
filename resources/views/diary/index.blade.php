@extends('layouts.app')

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
</div>
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <!-- Card header -->
                <div class="card-header border-0">
                    <h3 class="mb-0">Consultation's Health Diary <a href="{{ route('services.diary.create', $consultationId) }}"><i class="fas fa-plus-square text-danger ml-1"></i> Log Record</a></h3>
                </div>
                <!-- Light table -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">From</th>
                                    <th scope="col" class="sort" data-sort="name">Data</th>
                                    <th scope="col" class="sort" data-sort="name">Date Created</th>
                                    <th scope="col" class="sort" data-sort="name">Date Updated</th>
                                    <th scope="col" class="sort" data-sort="name">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach($diaries as $diary)
                                <tr>
                                    <th scope="row">
                                        {{ $diary->user->name }}
                                    </th>
                                    <td>
                                        {{ $diary->note }}
                                    </td>
                                    <td>
                                        {{ $diary->created_at }}
                                    </td>
                                    <td>
                                        {{ $diary->updated_at}}
                                    </td>
                                    <td>
                                        <a href="{{ route('services.diary.edit', [$consultationId, $diary->id]) }}" class="btn btn-primary my-4">Update</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Card footer -->
                <div class="card-footer py-4">
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth')
</div>
@endsection

@push('js')
@endpush