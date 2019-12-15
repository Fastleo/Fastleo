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

    public $setting;

    public function __construct()
    {
        $this->setting = FastleoSetting::get()->keyBy('key')->map(function ($item) {
            return $item->value;
        });
    }

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
        return collect(Storage::directories($this->folder))
            ->flip()
            ->except($this->folder . '/thumbs')
            ->flip();
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
            $result[$k]['preview'] = 'storage/fastleo/ico/' . strtolower($result[$k]['extension']) . '.jpg';

            $tmp_filename = str_replace($this->folder, $this->folder . '/thumbs', str_replace(' ', '_', $file));
            if (file_exists(base_path('storage/app/' . strtolower($tmp_filename)))) {
                $result[$k]['preview'] = 'storage' . substr(strtolower($tmp_filename), 6);
            }
        }
        return collect($result ?? []);
    }

    /**
     * @return string
     */
    public function getFolderUp()
    {
        return collect(explode("/", $this->folder))
            ->slice(0, -1)
            ->implode('/');
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
        return view('fastleo::filemanager-index', [
            'folders' => $this->getFolders(),
            'files' => $this->getFiles(),
            'up' => $this->getFolderUp(),
        ]);
    }

    /**
     * Uploads files
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function uploads(Request $request)
    {
        $this->construct($request);
        $files = $request->file('files');
        if (isset($files) and count($files) > 0) {
            foreach ($files as $file) {
                $name = str_replace(' ', '_', $file->getClientOriginalName());
                $file->move($this->path, strtolower($name));
                if (in_array(strtolower($file->getClientOriginalExtension()), config('fastleo.images'))) {
                    $image = Image::make($this->path . '/' . strtolower($name));
                    if ($request->has('watermark') and isset($this->setting['watermark']) and $this->setting['watermark'] != '') {
                        $watermark = str_replace('/storage/uploads', 'storage/app/public/uploads', $this->setting['watermark']);
                        if (is_file(base_path($watermark))) {
                            $image->insert(base_path($watermark), 'center');
                        } else {
                            $image->text($this->setting['watermark'], $image->getWidth() / 2, $image->getHeight() / 2, function ($font) {
                                $font->file(base_path('storage/app/public/fastleo/font/roboto.ttf'));
                                $font->color(array(255, 255, 255, 0.5));
                                $font->size(30);
                                $font->align('center');
                                $font->valign('middle');
                            });
                        }
                    }
                    $image->save();
                    $image->resize(122, 91);
                    $image->save($this->path . '/thumbs/' . strtolower($name));
                }
            }
        }
        return redirect(route('fastleo.filemanager') . '?' . $request->getQueryString());
    }

    /**
     * Создание директории
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create(Request $request)
    {
        $this->construct($request);
        if ($request->post('folder_name')) {
            $folder_name = Str::slug($request->post('folder_name'), '_');
            File::makeDirectory(base_path('storage/app/' . $this->folder . '/' . $folder_name), 0777);
            File::makeDirectory(base_path('storage/app/' . $this->folder . '/' . $folder_name . '/thumbs'), 0777);
        }
        return redirect(route('fastleo.filemanager') . '?' . $request->getQueryString());
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

    /**
     * Обновляет превью для изображений
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function preview(Request $request)
    {
        $this->construct($request);
        Storage::deleteDirectory($this->folder . '/thumbs');
        File::makeDirectory(base_path('storage/app/' . $this->folder . '/thumbs'), 0777);
        foreach ($this->getFiles() as $file) {
            if (in_array($file['extension'], config('fastleo.images'))) {
                $tmp_filename = str_replace($this->folder, $this->folder . '/thumbs', strtolower($file['filename']));
                if (!file_exists(base_path('storage/app/' . $tmp_filename))) {
                    $image = Image::make(base_path('storage/app/' . $file['filename']));
                    $image->resize(122, 91);
                    $image->save(base_path('storage/app/' . str_replace(' ', '_', $tmp_filename)));
                }
            }
        }
        return redirect(route('fastleo.filemanager') . '?' . $request->getQueryString());
    }
}
