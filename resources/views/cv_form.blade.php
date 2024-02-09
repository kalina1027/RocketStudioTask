@extends('layout')
@section('title', 'Създаване на CV')

@section('script')
<script type="text/javascript" src="{{asset('js/applications.js') }}"></script>
@endsection

@section('content')
<div>
    <div class="d-flex flex-column align-items-center">
        @if ($errors->any())
            <div class="alert alert-danger form-errors col-lg-4" role="alert">
                <div class="error-message"> {{ __('Грешка!') }} </div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
                
        <form method="post" action="{{ route('cv.store') }}" enctype="multipart/form-data" class="d-flex flex-column gap-3 col-lg-4" id="addCVForm">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Име *" value="{{ old('first_name') }}" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="middle_name" name="middle_name" placeholder="Презиме" value="{{ old('middle_name') }}">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Фамилия *" value="{{ old('last_name') }}" required>
            </div>     
            <div class="form-group">
                <input type="email" class="form-control" id="email" name="email" placeholder="Имейл *" value="{{ old('email') }}" required>
            </div>                  
            <div class="form-group">
                <label for="birth_date" class="form-label">Дата на раждане * </label>
                <input type="datetime-local" class="form-control" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" required> 
            </div>          
            <div class="form-group d-flex gap-1">
                <select class="form-select" aria-label="Изберете университет" name="university" value="{{ old('university') }}" required>
                    <option selected>Изберете университет</option>
                    @foreach($allUniversities as $uni)
                        <option value="{{$uni->id}}">{{$uni->title}}</option>
                    @endforeach
                </select>
                <button id="addUniversityBtn" type="button" class="btn border rounded" style="height: fit-content" data-bs-toggle="modal" data-bs-target="#modal">
                  <i class="bi bi-pencil-fill"></i>
                </button>
            </div>
            <div class="form-group">
                <label class="form-label">Умения в технологии (изберете няколко) * </label>
                <div class="d-flex gap-1">
                    <select class="form-select" multiple aria-label="Умения в технологии" name="technologies[]" required>
                        @foreach($allTechnologies as $tech)
                            <option value="{{$tech->id}}">{{$tech->title}}</option>
                        @endforeach
                    </select>
                    <button id="addTechnologyBtn" type="button" class="btn border rounded" style="height: fit-content" data-bs-toggle="modal" data-bs-target="#modal">
                      <i class="bi bi-pencil-fill"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Запис на CV</button>
            @if(session()->get('response') != null)
                <div class="alert {{ session()->get('status')=='success' ? 'alert-success' : 'alert-danger' }}" role="alert">
                    {{ session()->get('response') }}
                </div>
            @endif
        </form>
    </div>
</div>

<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="modalLabel">Въведи нов университет</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="{{ route('university.store') }}" enctype="multipart/form-data" id="addUniversityForm">
                @csrf
                <div class="form-fields d-flex flex-column gap-3">
                    <div class="form-group">
                        <input type="text" class="form-control" name="title" placeholder="Име на университет *" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="rating" placeholder="Акредитационна оценка">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary d-block ms-auto mt-3 mb-2 w-20" >Запис</button>
                <div class="alert d-none" role="alert"></div>
            </form>
            <form method="post" action="{{ route('technology.store') }}" enctype="multipart/form-data" id="addTechnologyForm" class="d-none">
                @csrf
                <div class="form-fields d-flex flex-column gap-3">
                    <div class="form-group">
                        <input type="text" class="form-control" id="title" name="title" placeholder="Технология *" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary d-block ms-auto mt-3 mb-2 w-20" >Запис</button>
                <div class="alert d-none" role="alert"></div>
            </form>
        </div>
    </div>
</div>
@endsection