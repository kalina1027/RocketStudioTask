@extends('layout')
@section('title', 'Справки')
@section('script')
<script type="text/javascript" src="{{asset('js/reports.js') }}"></script>
<script type="text/javascript">
    const routes = {
        birthdate: "{{ route('report.birthdate') }}",
        ageTechnologies: "{{ route('report.age-technologies') }}",
    };
</script>
@endsection
@section('content')
<div>
    <h4 class="text-center mb-3">По период</h4>
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
        <form method="post" action="{{ route('report.birthdate') }}" enctype="multipart/form-data" class="d-flex flex-column gap-3" id="reportsForm">
            @csrf
            <div class="d-flex flex-column align-items-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="form-group">
                        <label for="start_date" class="form-label">Начална дата</label>
                        <input type="datetime-local" class="form-control" name="start_date" id="start_date" value="{{ old('start_date') }}" > 
                    </div>
                    <div class="form-group">
                        <label for="end_date" class="form-label">Крайна дата</label>
                        <input type="datetime-local" class="form-control" name="end_date" id="end_date" value="{{ old('end_date') }}" > 
                    </div>
                </div>
                <div class="d-flex flex-column gap-2" style="max-width: 430px">
                    <button type="submit" class="btn btn-primary" id="filter_birthdate">Покажи CV-та на кандидати родени в периода</button>
                    <button type="submit" class="btn btn-warning" id="filter_ageTechnologies">Покажи брой кандидати групирани по възраст със списък от умения за периода</button>
                </div>
            </div>
            <div class="alert d-none" role="alert"></div>
        </form>
    </div>
    <h2 class="text-center mb-3 mt-4"> {{ __('Резултати') }}</h2>
    @php
        $cvs = session()->get('cvs');
        $startDate = session()->get('startDate');
        $endDate = session()->get('endDate');
        $title = session()->get('title');
    @endphp
    <h5 class="text-center">@isset($title) {{ $title }} @endisset</h5>
    <div class="w-75 mx-auto">
        @if(isset($cvs))
        <div class="text-center mb-3">
            <h4>За периода</h4>
            <div><span>@isset($startDate) {{ $startDate }} @endisset</span> - <span>@isset($endDate) {{ $endDate }} @endisset</span></div>
        </div>
        <table class="table">
          <thead>
            <tr>
                @if(isset($cvs[0]['age']))
                    <th scope="col">Брой</th>
                    <th scope="col">Възраст</th>
                    <th scope="col">Технологии</th>
                @else
                    <th scope="col">#</th>
                    <th scope="col">Име</th>
                    <th scope="col">Имейл</th>
                    <th scope="col">Дата на раждане</th>
                    <th scope="col">Университет</th>
                    <th scope="col">Технологии</th>
                @endif
            </tr>
          </thead>
          <tbody>
            @foreach($cvs as $cv)
            <tr>
                @if(isset($cv['age']))
                    <td>{{ $cv['count'] }}</td>
                    <td>{{ $cv['age'] }}</td>
                    <td>{{ $cv['technologies'] }}</td>
                @else
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
                @endif

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