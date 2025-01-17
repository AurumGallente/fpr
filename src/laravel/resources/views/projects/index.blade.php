<x-app>
    <hr class="hr-blurry " />
    <div class="">
        <div class="row">
            <div class="col-3">
                <a href="{{route('projects.create')}}" class="card-link btn btn-success col-span-2">Create new</a>
            </div>
        </div>
        <br />
        <div class="row bg-light">
        @foreach ($projects as $project)
                <div class="card m-4" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">{{$project->name}}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{$project->created_at}}</h6>
                        <p class="card-text">{{$project->description}}</p>
                        <a href="{{route('projects.show', $project->id)}}" class="card-link btn btn-info col-span-2">View</a>
                    </div>
                </div>
        @endforeach
        </div>
    </div>
    <hr class="hr-blurry " />
    <div class="row">
        <div class="col-9">
            {{ $projects->links() }}
        </div>
    </div>

</x-app>
