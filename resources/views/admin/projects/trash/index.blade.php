@extends("layouts.app")

@section("title","Projects")

@section("content")
@if(session('recupero'))
    <div class="alert text-center alert-{{ session('type') }}">
        {{ session('recupero') }}
    </div>
@endif
@if(session('segnalazione'))
    <div class="alert text-center alert-{{ session('type') }}">
        {{ session('segnalazione') }}
    </div>
@endif


<header id="myIndex-delete">
<div class="container">
   
   <a href="{{route("admin.projects.index")}}">Torna ai Progetti</a>
   <form class="" action="{{route("admin.projects.trash.dropAll")}}" method="POST">
@csrf
@method("DELETE")
<button class="btn btn-danger deletes-form">Svuota Cestino</button>

</form>
    <table class="table table-dark table-striped-columns">
        <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Titolo</th>
              <th scope="col">Descrizione</th>
              <th scope="col">GIT Hub</th>
              <th scope="col"></th>

            </tr>
          </thead>
          <tbody>
           @forelse ($projects as $project)
           <tr>
            <th scope="row">{{$project->id}}</th>
            <td>{{$project->title}}</td>
            <td>{{ Str::limit($project->description, 50)}}</td>
            <td>{{ Str::limit($project->description, 20)}}</td>
            <td class="text-center">
                <form class="delete-form d-inline" data-project="{{$project->title}}"  action="{{route("admin.projects.trash.drop", $project->id)}}" method="POST">
                    @csrf
                    @method("DELETE")
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i>
                    </button>
                  </form>
            </td>
            <td>
                <form action="{{route("admin.projects.trash.restore", $project->id)}}" method="POST">
                    @csrf
                    @method("PATCH")
                    <button>Ripristina</button>
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
      const confirm = window.confirm(`Sei sicuro di voler eliminare DEFINITIVAMENTE il progetto ${title}?`);
      if (confirm) form.submit();
    })
  });
</script>
<script>
const deleteAllBtn = document.querySelector('.deletes-form');
deleteAllBtn.addEventListener('click', function(event) {
  event.preventDefault();
  const count = deleteAllBtn.dataset.count;
  const confirmDelete = confirm(`Sei sicuro di voler eliminare tutti i progetti?`);
  if (confirmDelete) {
    deleteAllBtn.closest('form').submit();
  }
});

  </script>
@endsection