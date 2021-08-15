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
                    <h3 class="mb-0">Forms @if(auth()->user()->role->value == 0)<a href="{{ route('forms.create') }}"><i class="fas fa-plus-square text-danger ml-1"></i></a>@endif</h3>
                </div>
                <!-- Light table -->
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">Name</th>
                                <th scope="col" class="sort" data-sort="name">Required</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach($forms as $form)
                            <tr>
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <div class="media-body">
                                            <span class="name mb-0 text-sm">{{ $form->name }}</span>
                                        </div>
                                    </div>
                                </th>
                                <td>
                                    {{ $form->required ? 'YES' : null }}
                                </td>
                                <td>
                                    <a href="{{ route('forms.show', $form) }}" class="btn btn-primary my-4">View Form</a>
                                    @if($form->has_answer)
                                    <a href="{{ route('view-answer', ['formId' => $form->id, 'userId' => auth()->user()->id]) }}" class="btn btn-primary my-4">View Answer</a>
                                    @endif
                                    @if(auth()->user()->role->value == 0)
                                    <form method="POST" action="{{ route('forms.destroy', $form->id) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-danger" value="Delete form">
                                        </div>
                                    </form>
                                    @endif
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