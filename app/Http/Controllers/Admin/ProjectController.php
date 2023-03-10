<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $filter = $request->query('filter');
    $request->session()->put('filter', $filter);
    $search = $request->query("search");
    $query = Project::orderBy("updated_at", "DESC");

    if ($filter === "bozze") {
        $query->where("is_published", false);
    } elseif ($filter === "pubblicati") {
        $query->where("is_published", true);
    }
    
    if ($search) {
        $query->where("title", "LIKE", "%$search%");
    }

    $projects = $query->paginate(10);

    $params = [
        'filter' => $filter,
        'search' => $search,
    ];

    $queryString = http_build_query($params);


    return view("admin.projects.index", compact("projects", "filter", "queryString", "search"));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.projects.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        "title"=>"required|string|unique:projects|min:5|max:50",
        "description"=>"required|string",
        "image"=>"image|nullable|mimes:jpeg,jpg,png",
        "github"=>"required|url|max:100",
        ], [
            "title.required" => "ERROR - il titolo è obbligatorio",
            "title.unique" => "ERROR - il titolo $request->title è gia presente",
            "title.min" => "ERROR - la lunghezza del titolo deve essere almeno di 5 caratteri",
            "title.max" => "ERROR - la lunghezza del titolo non deve superare i 50 caratteri",
            "description.required" => "ERROR - la descrizione è obbligatoria",
            "image.image" => "ERROR - sono accettati solo formati di tipo jpeg, jpg, png",
            "github.required" => "ERROR - il link al progetto è obbligatorio",
            "github.url"=> "ERROR - devi inserire un URL",
            "github.max"=> "ERROR - la lunghezza del titolo non deve superare i 100 caratteri, controlla che sia un link github",
        ]);
        $data = $request->all();
        $project=new Project();
        if(Arr::exists($data,"image")){
            $img_url=Storage::put("projects",$data["image"]);
            $data["image"] =$img_url;
        };
        $project->fill($data);
        $project->is_published=Arr::exists($data,"is_published");
        $project->save();
        session()->flash('success', 'Creazione avvenuta con successo!');
        return redirect()->route("admin.projects.index");

    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view("admin.projects.show", compact("project"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $types=Type::all();
        return view ("admin.projects.edit", compact("project","types"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $data = $request->all();
        $request->validate([
            "title"=>["required","string", "min:5", "max:50", Rule::unique("projects")->ignore($project->id)],
            "description"=>"required|string",
            "image"=>"image|nullable|mimes:jpeg,jpg,png",
            "github"=>"required|url|max:100",
            "type_id"=>"nullable|exists:types,id"
            ], [
                "title.required" => "ERROR - il titolo è obbligatorio",
                "title.min" => "ERROR - la lunghezza del titolo deve essere almeno di 5 caratteri",
                "title.max" => "ERROR - la lunghezza del titolo non deve superare i 50 caratteri",
                "description.required" => "ERROR - la descrizione è obbligatoria",
                "image.image" => "ERROR - sono accettati solo formati di tipo jpeg, jpg, png",
                "github.required" => "ERROR - il link al progetto è obbligatorio",
                "github.url"=> "ERROR - devi inserire un URL",
                "github.max"=> "ERROR - la lunghezza del titolo non deve superare i 100 caratteri, controlla che sia un link github",
                "type_id"=>"ERROR - tipo non valido"
            ]);
            if(Arr::exists($data,"image")){
                if($project->image)Storage::delete($project->image);
                $img_url=Storage::put("projects",$data["image"]);
                $data["image"] =$img_url;
            };
            $data["is_published"]=Arr::exists($data,"is_published");
        $project->update($data);
        $project->save();
        return to_route("admin.projects.show", $project->id)->with("success", "Modifica avvenuta con successo.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
       if($project->image)Storage::delete($project->image);
        $project->delete();
        return to_route("admin.projects.index")->with("delete", "Il Progetto $project->title è stato eliminato");
    }

    public function togglePubblication(Project $project){
        $project->is_published=!$project->is_published;
        $action=$project->is_published ? "Pubblicato" : "Messo in Bozza";
        $project->save();
        return to_route("admin.projects.index")->with("type","success")->with("msg","Il Progetto è stato $action con successo");
    }

    public function trash(){
        $projects=Project::onlyTrashed()->get();
        return view("admin.projects.trash.index", compact("projects"));
    }

    public function restore(int $id){
        $project = Project::onlyTrashed()->findOrFail($id);
        $project->restore();
        return to_route("admin.projects.index")->with("message","Il Progetto $project->title è stato ripristinato")->with("type", "success");
    }

    public function drop(int $id){
        $project = Project::onlyTrashed()->findOrFail($id);
        $project->forceDelete();
        return to_route("admin.projects.trash.index")->with("recupero","Il Progetto $project->title è stato eliminato definitivamente")->with("type","danger");
    }

    public function dropAll(){
        Project::onlyTrashed()->forceDelete();
       
        return to_route("admin.projects.trash.index")
            ->with("segnalazione","Sono stati eliminati definitivamente tutti i Progetti")
            ->with("type","danger");
           
    }
    

    
}