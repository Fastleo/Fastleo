<?php

namespace Fastleo\Fastleo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class FilemanagerController extends Controller
{
    public $images = ['png', 'jpg', 'jpeg', 'gif'];

    public $storage = 'public/uploads';

    public $upload = '/storage/uploads';

    public $folders;

    public $folder;

    public $files;

    public $path;

    /**
     * FilemanagerController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        // Основная директорая
        $this->path = base_path('storage/app/' . $this->storage);
        $this->folder = '';

        // Создание основной директории
        if (!is_dir($this->path)) {
            File::makeDirectory($this->full_path, $mode = 0777, true, true);
        }

        // Создание папки со зжатыми изображениями
        if (!is_dir($this->path . '/.thumbs')) {
            File::makeDirectory($this->path . '/.thumbs', $mode = 0777, true, true);
        }

        // Все папки в директории
        $this->folders = collect(Storage::directories('public/uploads'))->except($this->storage . '/thumbs');

        // Все файлы в директории
        $this->files = collect(Storage::files($this->storage));
    }

    /**
     * Данные о расширении файла
     * @param $filename
     * @return mixed
     */
    protected function getExtention($filename)
    {
        return pathinfo($this->dir . '/' . $filename, PATHINFO_EXTENSION);
    }

    /**
     * Проверка на изображение
     * @param $extention
     * @return bool
     */
    protected function checkImage($extention)
    {
        return (in_array(strtolower($extention), $this->images)) ? true : false;
    }

    /**
     * Falimanager index page
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('fastleo::filemanager/index', [
            'folders' => $this->folders,
            'images' => $this->images,
            'files' => $this->files,
        ]);
    }

    /**
     * Uploads files
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploads(Request $request)
    {
        $files = $request->file('files');
        if (isset($files) and count($files) > 0) {
            foreach ($files as $file) {
                $name = str_replace([' '], ['_'], $file->getClientOriginalName());
                $file->move($this->dir, $name);
                $ext = pathinfo($this->dir . '/' . $name, PATHINFO_EXTENSION);
                if (in_array(strtolower($ext), $this->images)) {
                    Image::make($this->dir . '/' . $name)->resize(120, 90)->insert($this->dir . '/.thumbs/' . $name);
                }
            }
            header('Location: /fastleo/filemanager?' . request()->getQueryString());
            die;
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
        if ($request->post('folder_name')) {
            $folder_name = str_replace(' ', '_', $request->post('folder_name'));
            File::makeDirectory(base_path('public/uploads/' . $request->get('folder') . '/' . $folder_name), 0777);
            header('Location: /fastleo/filemanager?' . request()->getQueryString());
            die;
        }
        return view('fastleo::filemanager/create');
    }
}
