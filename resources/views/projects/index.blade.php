<html>
    <head>
    <title>Birdbox</title>
    </head>
    <body>
        <h1>Birdbox</h1>

        <ul>
            @forelse ($projects as $project)
            <li><a href="{{ $project->path() }}">{{$project->title}}</a></li>
            @empty
            <li>No Project Found</li>
            @endforelse
        </ul>
    </body>
</html>