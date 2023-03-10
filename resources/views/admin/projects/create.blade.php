@extends("layouts.app")

@section("title","Crea Progetto")

@section("content")
<main id="myCreate">
  
  <div class="container">
    

    <div class="container my-5">
     <form class="row g-3" action="{{route("admin.projects.store")}}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Titolo -->
        <div class="d-flex">
          <div class="col-md-6">
              <label for="title" class="form-label">Titolo</label>
              <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $project->title) }}">
            @if($errors->has("title"))
            <ul class="alert list-unstyled alert-danger m-0  d-flex flex-column justify-content-center">
            @foreach ($errors->get('title') as $error)
                <li class="m-0">{{ $error }}</li>
            @endforeach
            </ul>
            @endif
            </div>
            <!-- Tipo -->
            <div>
            <label for="type_id" class="form-label">Tipi</label>
            <select class="form-select" name="type_id" id="type_id">
            <option value="">Nessun Tipo</option>
            @foreach($types as $type)
            <option value="{{$type->id}}"@if($project->type_id == $type->id) selected @endif>{{$type->label}}</option>
            @endforeach  
            </select>  
            @if($errors->has("type_id"))
            <ul class="alert list-unstyled alert-danger m-0  d-flex flex-column justify-content-center">
            @foreach ($errors->get('type_id') as $error)
                <li class="m-0">{{ $error }}</li>
            @endforeach
            </ul>
            @endif
            </div>
            </div>        
          </div>
        <!-- GIT Hub -->
        <div class="col-md-6">
          <label for="github" class="form-label">Link GIT-Hub</label>
          <input type="text" class="form-control  @error('github') is-invalid @enderror" id="github" name="github" @error("github") value="" @enderror value="{{ old('github') }}">
          @if($errors->has("github"))
        <ul class="alert list-unstyled alert-danger ps-2 d-flex flex-column justify-content-center">
        @foreach ($errors->get('github') as $error)
            <li class="m-0">{{ $error }}</li>
        @endforeach
        </ul>
        @endif
        </div>
        <!-- Descrizione -->
        <div class="col-12">
          <label for="description" class="form-label">Descrizione</label>
          <textarea class="form-control @error('description')is-invalid @enderror" name="description" id="description" cols="173" rows="3">@error('description')@enderror {{old('description')}}</textarea>
          @if($errors->has("description"))
          <ul class="alert list-unstyled alert-danger ps-2 d-flex flex-column justify-content-center">
            @foreach ($errors->get('description') as $error)
                <li class="m-0">{{ $error }}</li>
            @endforeach
            </ul>
            @endif
        </div>
        <!-- Immagine -->
        <div class="col-md-6 mb-3">
          <label for="image" class="form-label">Immagine</label>
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" >
            @if($errors->has("image"))
            <ul class="alert list-unstyled alert-danger ps-2 d-flex flex-column justify-content-center">
              @foreach ($errors->get('image') as $error)
                  <li class="m-0">{{ $error }}</li>
              @endforeach
              </ul>
              @endif
            </div>

            <div class="col-md-1">
              <img class="img-create" id="img-preview"
              src="https://marcolanci.it/utils/placeholder.jpg" alt="">
            </div>
          </div>
        
          
      </div>
        <!-- Bottone -->
        <div class="col-12">
          <button type="submit" class="btn btn-success">Aggiungi</button>
          <a href="{{route("admin.projects.index")}}" class="d-block">Ritorna ai Progetti</a>   
        </div>
      </form> 
              
    </div>
</main>
@endsection

@section("scripts")
<script>
  const placeholder="https://marcolanci.it/utils/placeholder.jpg";
  const imageInput=document.getElementById("image");
  const imagePreview=document.getElementById("img-preview");
  imageInput.addEventListener("change", ()=>{
    if(imageInput.files && imageInput.files[0]){
      const reader=new FileReader();
      reader.readAsDataURL(imageInput.files[0]);
      reader.onload=e=>{
        imagePreview.src=e.target.result;
      }
    } else {
      imagePreview.src=placeholder;
    }
  })
</script>
@endsection