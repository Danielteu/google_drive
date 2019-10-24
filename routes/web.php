<?php
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $folders = [];
    $files   = [];
    $option  = 0;
    $parent_dir = '';
    return view('welcome', compact('folders', 'files', 'option','parent_dir'));
});
 
Route::get('list', function() {
    $dir = '/';
    $recursive = false; // Get subdirectories also?
    $contents = collect(Storage::cloud()->listContents($dir, $recursive));
    $folders  = $contents->where('type', '=', 'dir');
    $files    = $contents->where('type', '=', 'file');
    $option   = 1;
    $parent_dir = '';
    return view('welcome', compact('folders','files','option','parent_dir'));
})->name('list');

Route::get('list-folder-contents/{id}', function($id) {
    // The human readable folder name to get the contents of...
    // For simplicity, this folder is assumed to exist in the root directory.
    $folder = $id;
    // Get root directory contents...
    $contents = collect(Storage::cloud()->listContents('/', false));

    // Find the folder you are looking for...
    $dir = $contents->where('type', '=', 'dir')
        ->where('filename', '=', $folder)
        ->first(); // There could be duplicate directory names!

    if ( ! $dir) {
        return back();
        return 'No such folder!';
    }

    // Get the files inside the folder...
    $files = collect(Storage::cloud()->listContents($dir['path'], false))
        ->where('type', '=', 'file');
    $folders = collect(Storage::cloud()->listContents($dir['path'], false))
        ->where('type', '=', 'dir');

    $files = $files->mapWithKeys(function($file) {
        $filename = $file['filename'].'.'.$file['extension'];
        $path = $file['path'];

        return [$filename => $path];
    });

    $option   = 2;
    // $folders = [];
    $parent_dir = $id;
    return view('welcome', compact('files','folders','option','parent_dir'));
})->name('list-folder-contents');
 /* 
Route::post('create-dir', function(Request $request) {
    if($request->create_field) {
        Storage::cloud()->makeDirectory($request->create_field);
        return 'Directory was created in Google Drive';
    }else
        return 'Please enter the name';
})->name('create_dir');
 */
Route::post('create-dir', function(Request $request) {
    if(!$request->parent_dir) {
        $parent_dir = '/';
        Storage::cloud()->makeDirectory($request->create_field);
        return 'Directory was created in Google Drive';
    }
    else
        $parent_dir = $request->parent_dir;
           
    if($request->create_field) {
        $sub_dir = $request->create_field;
        $dir     = '/';
        $recursive = false; // Get subdirectories also?
        $contents = collect(Storage::cloud()->listContents($dir, $recursive));

        $dir = $contents->where('type', '=', 'dir')
            ->where('filename', '=', $parent_dir)
            ->first(); // There could be duplicate directory names!

        if ( ! $dir) {
            return 'Directory does not exist!';
        }

        // Create sub dir
        Storage::cloud()->makeDirectory($dir['path'].'/'.$sub_dir);
    }
    else
        return 'Please enter the name';    

    return 'Sub Directory was created in Google Drive';
})->name('create_dir');

Route::get('delete/{id}', function($id) {
    $filename = $id;
    // Now find that file and use its ID (path) to delete it
    $dir = '/';
    $recursive = false; // Get subdirectories also?
    $contents = collect(Storage::cloud()->listContents($dir, $recursive));

    $file = $contents
        ->where('type', '=', 'file')
        ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
        ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
        ->first(); // there can be duplicate file names!

    Storage::cloud()->delete($file['path']);

    return 'File was deleted from Google Drive';
})->name('delete_file');

Route::get('delete-dir/{id}', function($id) {
    $directoryName = $id;
    $dir = '/';
    $recursive = true; // Get subdirectories also?
    $contents = collect(Storage::cloud()->listContents($dir, $recursive));

    $directory = $contents
        ->where('type', '=', 'dir')
        ->where('filename', '=', $directoryName)
        ->first(); // there can be duplicate file names!

    Storage::cloud()->deleteDirectory($directory['path']);

    return 'Directory was deleted from Google Drive';
})->name('delete_dir');
