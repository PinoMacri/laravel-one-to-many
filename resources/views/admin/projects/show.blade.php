@extends ("layouts.app")

@section("title", $project->title)

@section ("content")
@if (session("success"))
    <div class="alert alert-warning alert-dismissible text-center">
        <div class="d-flex justify-content-center">
            {{ session("success") }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

<header>
    <div class="container">
        <h1 class="my-5">
            {{$project->title}}
        </h1>
        <p>
            {{$project->description}}
        </p>

         <a href="{{$project->github}}" target="_blank">Link Progetto a GitHub</a>
         <a href="{{route("admin.projects.index")}}" class="d-block">Ritorna ai Progetti</a>
         <img src="{{asset("storage/" . $project->image)}}" alt="">

         <div>
            <strong>Stato:</strong> {{$project->is_published ? "Pubblicato" : "Bozza"}}
        </div>

       
    </div>
    
</header>

@endsection