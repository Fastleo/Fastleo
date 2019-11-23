<?php

namespace Fastleo\Fastleo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class FilemanagerController extends Controller
{
    public $folder;

    public $path;

    /**
     * FilemanagerController constructor.
     * @param Request $request
     */
    public function construct(Request $request)
    {
        // Текущая директорая
        $this->folder = $request->get('folder') ?? $request->session()->get('folder') ?? config('fastleo.uploads');
        $request->session()->put('folder', $this->folder);
        $request->session()->save();

        // Абсолютный пють к текущей директории
        $this->path = base_path('storage/app/' . $this->folder);

        // Создание основной директории
        if (!is_dir($this->path)) {
            File::makeDirectory($this->path, $mode = 0777, true, true);
        }

        // Создание временной папки
        if (!is_dir($this->path . '/thumbs')) {
            File::makeDirectory($this->path . '/thumbs', $mode = 0777, true, true);
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getFolders()
    {
        return collect(Storage::directories($this->folder))->flip()->except($this->folder . '/thumbs')->flip();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getFiles()
    {
        $files = Storage::files($this->folder);
        foreach ($files as $k => $file) {

            $result[$k]['filename'] = $file;
            $result[$k]['extension'] = $this->extension($file);
            $result[$k]['preview'] = 'storage/fastleo/ico/' . $result[$k]['extension'] . '.jpg';

            if (in_array($result[$k]['extension'], config('fastleo.images'))) {
                $tmp_filename = str_replace($this->folder, $this->folder . '/thumbs', $file);
                if (!file_exists(base_path('storage/app/' . $tmp_filename))) {
                    Image::make(base_path('storage/app/' . $file))->resize(122, 91)->save(base_path('storage/app/' . $tmp_filename));
                }
                $result[$k]['preview'] = 'storage/' . substr($tmp_filename, 6);
            }
        }
        return collect($result ?? []);
    }

    /**
     * @return array
     */
    public function getFolderUp()
    {
        return collect(explode("/", $this->folder))->slice(0, -1)->implode('/');
    }

    /**
     * Проверка на изображение
     * @param $filename
     * @return bool
     */
    public function extension($filename)
    {
        return pathinfo('storage/app/' . $filename, PATHINFO_EXTENSION);
    }

    /**
     * Falimanager index page
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->construct($request);
        return view('fastleo::filemanager/index', [
            'folders' => $this->getFolders(),
            'files' => $this->getFiles(),
            'up' => $this->getFolderUp(),
        ]);
    }

    /**
     * Uploads files
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploads(Request $request)
    {
        $this->construct($request);
        $files = $request->file('files');
        if (isset($files) and count($files) > 0) {
            foreach ($files as $file) {
                $name = $file->getClientOriginalName();
                $file->move($this->path, $name);
                if (in_array(strtolower($file->getClientOriginalExtension()), config('fastleo.images'))) {
                    Image::make($this->path . '/' . $name)->resize(122, 91)->save($this->path . '/thumbs/' . $name);
                }
            }
            return redirect(route('fastleo.filemanager') . '?' . $request->getQueryString());
        }
        return view('fastleo::filemanager/uploads');
    }

    /**
     * Создание директории
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $this->construct($request);
        if ($request->post('folder_name')) {
            $folder_name = Str::slug($request->post('folder_name'), '_');
            File::makeDirectory(base_path('storage/app/' . $this->folder . '/' . $folder_name), 0777);
            File::makeDirectory(base_path('storage/app/' . $this->folder . '/' . $folder_name . '/thumbs'), 0777);
            return redirect(route('fastleo.filemanager') . '?' . $request->getQueryString());
        }
        return view('fastleo::filemanager/create');
    }

    /**
     * Удаление файлов и папок
     * @param Request $request
     */
    public function trash(Request $request)
    {
        $files = $request->all()['files'];
        foreach ($files as $file) {
            if (Storage::exists($file)) {
                if (Storage::mimeType($file) == 'directory') {
                    Storage::deleteDirectory($file);
                } else {
                    Storage::delete($file);
                }
            }
        }
    }
}
