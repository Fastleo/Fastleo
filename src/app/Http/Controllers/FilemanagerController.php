<?php

namespace Fastleo\Fastleo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FilemanagerController extends Controller
{
    public $images = ['png', 'jpg', 'jpeg', 'gif'];

    public $upload = '/uploads';

    public $dir, $folders, $files;

    /**
     * FilemanagerController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        // upload folder name
        $this->dir = base_path('storage/app/public' . $this->upload);

        // Create uploads dir
        if (!is_dir($this->dir)) {
            File::makeDirectory($this->dir, $mode = 0777, true, true);
        }

        // Change dir
        if ($request->get('folder')) {
            if ($request->get('folder') == '/') {
                $request->merge(['folder' => '']);
            }
            $this->dir = base_path('storage/app/public' . $this->upload . '/' . $request->get('folder'));
            $this->upload = $this->upload . $request->get('folder');
        }

        // create thumbs folder
        if (!is_dir($this->dir . '/.thumbs')) {
            File::makeDirectory($this->dir . '/.thumbs', $mode = 0777, true, true);
        }
    }

    /**
     * @param $path
     * @param $image
     * @param int $desired_width
     * @return bool
     */
    private function resize($path, $image, $desired_width = 122)
    {
        $mime_type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $this->dir . '/' . $image);
        $mime_array = explode("/", $mime_type);
        $ext = end($mime_array);

        switch ($ext) {
            case 'png':
                $source_image = @imagecreatefrompng($path . '/' . $image);
                break;
            case 'jpg':
                $source_image = @imagecreatefromjpeg($path . '/' . $image);
                break;
            case 'jpeg':
                $source_image = @imagecreatefromjpeg($path . '/' . $image);
                break;
            case 'gif':
                $source_image = @imagecreatefromgif($path . '/' . $image);
                break;
        }

        if (!$source_image) {
            return false;
        }

        $width = imagesx($source_image);
        $height = imagesy($source_image);
        $desired_height = floor($height * ($desired_width / $width));
        $virtual_image = imagecreatetruecolor($desired_width, $desired_height);
        imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

        switch ($ext) {
            case 'png':
                imagepng($virtual_image, $path . '/.thumbs/' . $image);
                break;
            case 'jpg':
                imagejpeg($virtual_image, $path . '/.thumbs/' . $image);
                break;
            case 'jpeg':
                imagejpeg($virtual_image, $path . '/.thumbs/' . $image);
                break;
            case 'gif':
                imagegif($virtual_image, $path . '/.thumbs/' . $image);
                break;
        }

        return $this->upload . '/.thumbs/' . $image;
    }

    /**
     * Folders list
     * @param Request $request
     * @param array $folders
     * @return array
     */
    protected function getFolders($request, $folders = [])
    {

        // Redirect to folder
        if (!$request->has('folder')) {
            header('Location: /fastleo/filemanager?' . request()->getQueryString() . '&folder=' . $request->session()->get('folder'));
            die;
        }

        // save current folder
        $request->session()->put('folder', $request->get('folder'));
        $request->session()->save();

        // folders list
        $scan = scandir($this->dir);
        if (count($scan) > 0) {
            foreach ($scan as $e) {
                if (!in_array($e, ['.', '..', '.thumbs']) and is_dir($this->dir . '/' . $e)) {
                    $folders[] = $e;
                }
            }
        }
        return $folders;
    }

    /**
     * Files list
     * @param array $files
     * @return array
     */
    protected function getFiles($files = [])
    {
        $scan = scandir($this->dir);
        if (count($scan) > 0) {
            foreach ($scan as $e) {
                if (!in_array($e, ['.', '..', '.thumbs']) and is_file($this->dir . '/' . $e)) {
                    $ext = pathinfo($this->dir . '/' . $e, PATHINFO_EXTENSION);
                    if (!file_exists($this->dir . '/.thumbs' . $e) and in_array(strtolower($ext), $this->images)) {
                        $thumbs = self::resize($this->dir, $e);
                    } else {
                        $thumbs = (file_exists($this->dir . '/.thumbs' . $e)) ? $this->upload . '/.thumbs' . $e : '';
                    }
                    $files[] = [
                        'name' => $e,
                        'url' => $this->upload . '/' . $e,
                        'thumbs' => $thumbs,
                        'ext' => $ext,
                    ];
                }
            }
        }
        return $files;
    }

    /**
     * Falimanager index page
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('fastleo::filemanager/index', [
            'folders' => self::getFolders($request),
            'files' => self::getFiles(),
            'images' => $this->images,
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
                    self::resize($this->dir, $name);
                }
            }
            header('Location: /fastleo/filemanager?' . request()->getQueryString());
            die;
        }
        return view('fastleo::filemanager/uploads');
    }

    /**
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
