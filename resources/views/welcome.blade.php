<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <link href="{{asset('css/home.css')}}" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="text-center">
                    <h2>Laravel + Google Drive</h2>
                </div>
                <ul class="fordtreeview list-group col-md-4 col-md-offset-4">
                    <li class="list-group-item">
                        <form action="{{route('create_dir')}}" method="post">
                            {{csrf_field()}}
                            <input type="text" class="form-control menufilter" name="create_field" placeholder="Search"/>
                            <input type="hidden" name="parent_dir" value="{{$parent_dir}}">
                            <div class="text-right">
                                <button class="btn btn-success">Create Folder</button>
                            </div>
                        </form>
                    </li>
                    <li class="list-group-item">
                        <span class="hasSub"><i class="glyphicon glyphicon-folder-close"></i>
                            <a href="{{route('list')}}">Root</a>
                        </span>
                        <ul class="list-group expanded">
                            @if(count($folders) > 0)
                                @foreach($folders as $folder)
                                <li class="list-group-item list-group-folder"><i class="glyphicon glyphicon-folder-close"></i>
                                    <a href="{{ route('list-folder-contents',$folder['filename']) }}">
                                        {{$folder['filename']}}
                                    </a>
                                    <a class="" href="{{route('delete_dir', $folder['filename'])}}">
                                        <p class="pull-right">Delete</p>
                                    </a>
                                </li>
                                @endforeach
                            @endif
                            
                            @if(count($files) > 0)
                                @foreach($files as $key=>$file)
                                <li class="list-group-item list-group-files @if($option == 1) list-group-folder @endif"><i class="glyphicon glyphicon-file"></i>
                                    @if($option == 1)
                                        {{$file['filename']}}
                                        <a class="" href="{{route('delete_file', $file['filename'])}}">
                                            <p class="pull-right">Delete</p>
                                        </a>
                                    @else
                                        {{$key}}
                                        <a class="" href="{{route('delete_file', $key)}}">
                                            <p class="pull-right">Delete</p>
                                        </a>
                                    @endif
                                    
                                </li>
                                @endforeach
                            @else
                                @if($option == 2)
                                <!-- <li class="list-group-item"><i class="glyphicon glyphicon-file"></i>
                                    No data!
                                </li> -->
                                @endif
                            @endif
                        </ul>
                    </li>
                </ul> 
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

        <script src="{{asset('js/home.js')}}"></script>
    </body>
</html>
