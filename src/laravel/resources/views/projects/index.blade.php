<x-app>
    <hr class="hr-blurry " />
    <div class="">
        <div class="row">
            <div class="col-3">
                <a href="{{route('projects.create')}}" dusk="create_project" class="card-link btn btn-success col-span-2">Create new</a>
            </div>
        </div>
        <br />
        <div class="row">
        @foreach ($projects as $project)
            <div class="col-3 d-flex align-items-stretch col-auto mb-3">
                <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                    <div class="card-body" style="min-width: 300px">
                        <h5 class="card-title">{{$project->name}}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{$project->created_at}}</h6>
                        <p class="card-text">{{$project->description}}</p>
                        <a dusk="edit{{$project->id}}" href="{{route('projects.show', $project->id)}}" class="card-link btn btn-info col-span-2">View</a>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    </div>
    <hr class="hr-blurry " />
    <div class="row">
        <div class="col-9">
            {{ $projects->links('pagination::bootstrap-4') }}
        </div>
    </div>

</x-app>
