@extends('layout')
@section('title', 'Справки')

@section('content')
<div>
    <h4 class="text-center mb-3">Кандидати родени в период</h4>
    <div class="d-flex flex-column align-items-center">
        @if ($errors->any())
            <div class="alert alert-danger form-errors" role="alert">
                <div class="error-message"> {{ __('Грешка!') }} </div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif    
        <form method="post" action="{{ route('report.birthdate') }}" enctype="multipart/form-data" class="d-flex flex-column gap-3" id="filterByBirthdateForm">
            @csrf
            <div class="d-flex align-items-end gap-2">
                <div class="form-group">
                    <label for="start_date" class="form-label">Начална дата</label>
                    <input type="datetime-local" class="form-control" name="start_date" id="start_date" value="{{ old('start_date') }}" > 
                </div>
                <div class="form-group">
                    <label for="end_date" class="form-label">Крайна дата</label>
                    <input type="datetime-local" class="form-control" name="end_date" id="end_date" value="{{ old('end_date') }}" > 
                </div>
                <button type="submit" class="btn btn-primary">Покажи</button>
            </div>
            <div class="alert d-none" role="alert"></div>
        </form>
    </div>
    <h2 class="text-center mb-3 mt-4"> {{ __('Резултати') }}</h2>
    <div class="w-75 mx-auto">
        @php
            $cvs = session()->get('cvs');
            $startDate = session()->get('startDate');
            $endDate = session()->get('endDate');
        @endphp
        @if(isset($cvs))
        <div class="text-center mb-3">
            <h4>За периода</h4>
            <div><span>@isset($startDate) {{ $startDate }} @endisset</span> - <span>@isset($endDate) {{ $endDate }} @endisset</span></div>
        </div>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Име</th>
              <th scope="col">Имейл</th>
              <th scope="col">Дата на раждане</th>
              <th scope="col">Университет</th>
              <th scope="col">Технологии</th>
            </tr>
          </thead>
          <tbody>
            @foreach($cvs as $cv)
            <tr>
                <th scope="row">{{$cv['id']}}</th>
                <td>{{ $cv['candidate']['first_name'] }} {{ $cv['candidate']['middle_name'] }} {{ $cv['candidate']['last_name'] }}</td>
                <td>{{ $cv['candidate']['email'] }}</td>
                <td>{{ $cv['candidate']['birth_date'] }} г.</td>
                <td>
                   {{ $cv['university']['title'] }}
                </td>
                <td>
                    @foreach($cv['technologies'] as $tech)
                        {{$tech['title']}} <br>
                    @endforeach
                </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @else
            @if(session()->get('status') == 'fail')
                <p class="text-center">{{ session()->get('response') }}</p>
            @endif
        @endif
    </div>
</div>
@endsection