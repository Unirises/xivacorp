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
                    <h3 class="mb-0">Personnel List – Change Company & Roles</h3>
                </div>
                <!-- Light table -->
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">User</th>
                                <th scope="col" class="sort" data-sort="budget">Previous Workspace ID</th>
                                <th scope="col" class="sort" data-sort="budget">New Workspace ID</th>
                                <th scope="col" class="sort" data-sort="budget">Previous Role</th>
                                <th scope="col" class="sort" data-sort="budget">Current Role</th>
                                <th scope="col" class="sort" data-sort="status">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach($data as $datum)
                            <tr>

                                <th>
                                    {{ $datum->user->name }}
                                </th>
                                <td>{{ $datum->user->workspace_id }}</td>
                                <td>{{ $datum->workspace_id }}</td>
                                <td>{{ $datum->user->role->description }}</td>
                                <td>
                                    @if($datum->role->value == 1)
                                    {{ $datum->user->hcp_data->type->name }} - {{ $datum->user->hcp_data->prc_id }}
                                    @else
                                    {{ $datum->role->description }}
                                    @endif
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('company-notification.approve', $datum->id) }}">
                                        @if(auth()->user()->role->value == 0)
                                        {{ csrf_field() }}

                                        <div class="form-group">
                                            <input type="submit" class="btn btn-success" value="Update">
                                        </div>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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