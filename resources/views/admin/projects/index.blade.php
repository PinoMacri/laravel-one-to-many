@extends("layouts.app")

@section("title","Projects")

@section("content")
@if(session("delete"))
<div class="alert alert-danger text-center">
{{session("delete")}}
</div>
@endif

@if(session()->has('msg'))
    <div class="alert text-center alert-{{ session('type') }}">
        {{ session('msg') }}
    </div>
@endif
@if(session('message'))
    <div class="alert text-center alert-{{ session('type') }}">
        {{ session('message') }}
    </div>
@endif

@foreach ($projects as $project)
<div class="alert text-center alert-success alert-dismissible fade show" role="alert" style="display:none;" id="create-success-alert">
  <p>Il Progetto {{$project->title}} è stato creato con sucesso!</p>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endforeach

<header id="myIndex">
<div class="container">
    <h1 class="my-4">Progetti</h1>
    <div class="d-flex justify-content-between mb-3">
      <a class="btn mb-3 btn-small btn-success" href="{{route("admin.projects.create")}}">Aggiungi <i class="fa-solid fa-plus"></i></a>
      <a href="{{route("admin.projects.trash.index")}}">Cestino</a>
      <div class="d-flex">
        <form action="{{route("admin.projects.index")}}" method="GET">
          <div class="input-group pe-3">
            <button class="btn btn-outline-secondary" type="submit">Filtra</button>
            <select class="form-select" name="filter" id="filter">
              <option {{ $filter === null ? 'selected' : '' }} value="">Tutti</option>
              <option {{ $filter === 'pubblicati' ? 'selected' : '' }} value="pubblicati">Pubblicati</option>
              <option {{ $filter === 'bozze' ? 'selected' : '' }} value="bozze">Bozze</option>
          </select>
          
          </div>
        </form>
        
        
        <form method="GET" action="{{route("admin.projects.index")}}">
          <div class="input-group ">
            <button class="btn btn-outline-secondary" type="submit">Cerca</button>
            <input type="text" class="form-control" placeholder="Nome Progetto" name="search" value="{{ old('search', $search) }}">
            <input type="hidden" name="filter" value="{{ session('filter') }}">
          </div>
        </form>
        
      </div>
    </div>
   
    <table class="table table-dark table-striped-columns table-index">
        <thead>
            <tr>
              <th class="my-th" scope="col">ID</th>
              <th class="my-th" scope="col">Titolo</th>
              <th class="my-th" scope="col">Tipo</th>
              <th class="my-th" scope="col">Descrizione</th>
              <th class="my-th" scope="col">GIT Hub</th>
              <th class="my-th" scope="col">Stato</th>
              <th class="my-th" scope="col">Azione</th>

            </tr>
          </thead>
          <tbody>
           @forelse ($projects as $project)
           <tr>
            <th class="my-id" scope="row">{{$project->id}}</th>
            <td>{{$project->title}}</td>
            <td><p class="p-0 text-center m-0" style="background-color: {{$project->type->color}}">{{$project->type->label}}</p></td>
            <td>{{ Str::limit($project->description, 50)}}</td>
            <td>{{ Str::limit($project->github, 20)}}</td>
            <td>
              <form action="{{route("admin.projects.toggle", $project->id)}}" method="POST">
              @method("PATCH")
              @csrf
              <button type="submit" class="btn btn-outline">
                <i class="fas fa-toggle-{{$project->is_published ? "on" : "off"}}  {{$project->is_published ? "text-success" : "text-danger"}}"></i>
              </button>
              </form>
            </td>

            <td class="text-center">
                <a href="{{route("admin.projects.show", $project->id)}}" class="btn btn-small btn-primary"><i class="fa-solid fa-eye"></i></a>
                <a href="{{route("admin.projects.edit", $project->id)}}" class="btn btn-small btn-warning"><i class="fa-solid fa-pen"></i></a>
                <form class="delete-form d-inline" data-project="{{$project->title}}"  action="{{route("admin.projects.destroy", $project->id)}}" method="POST">
                  @csrf
                  @method("DELETE")
                  <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i>
                  </button>
                </form>
            </td>
           
          </tr>
           @empty
            <tr>
                <th scope="row" colspan="5" class="text-center">Non ci sono Progetti</th>
            </tr>
           @endforelse
          </tbody>
    </table>
   <div>
     {{$projects->links()}}
   </div>
</div>
</header>
@endsection

@section("scripts")
<script>
  const deleteForms=document.querySelectorAll(".delete-form");
  deleteForms.forEach(form=>{
    form.addEventListener("submit", (event)=>{
      event.preventDefault();
      const title=form.getAttribute("data-project");
      const confirm = window.confirm(`Sei sicuro di voler eliminare il progetto ${title}?`);
      if (confirm) form.submit();
    })
  });
</script>
@if (session()->has('success'))
    <script>
        document.getElementById('create-success-alert').style.display = 'block';
    </script>
@endif
@endsection