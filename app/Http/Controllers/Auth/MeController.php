<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/8/23
 * Time: 下午3:04
 */

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Transformers\MeTransformer;
use Illuminate\Http\Request;
use League\Fractal\Resource\Item;

class MeController extends Controller
{
    protected $userRepository;
    protected $request;

    public function __construct(UserRepositoryInterface $userRepository,Request $request)
    {
        $this->userRepository = $userRepository;
        $this->request = $request;
    }

    public function index()
    {
        $hid = $this->request->get('g9zz_user_hid');
        $resource = new Item($this->userRepository->hidFind($hid),new MeTransformer());
        $this->setData($resource);
        return $this->response();
    }

    public function uploadAvatar(Request $request)
    {
        $photo = $request->file();
        $photo = $photo['file'];
        $original_name = $photo->getClientOriginalName();
        dd($photo,$original_name);
        $original_name_without_ext = substr($original_name, 0, strlen($original_name) - 4);
        $filename = $this->sanitize($original_name_without_ext);
        $allowed_filename = $this->createUniqueFilename( $filename );
        $filename_ext = $allowed_filename .'.jpg';
        $disk = \Storage::disk('qiniu');
        $disk->put($filename_ext,'22');
    }

    /**
     * 上传图片带的
     * @param $string
     * @param bool $force_lowercase
     * @param bool $anal
     * @return mixed|string
     */
    private function sanitize($string, $force_lowercase = true, $anal = false)
    {
        $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
            "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
            "â€”", "â€“", ",", "<", ".", ">", "/", "?");
        $clean = trim(str_replace($strip, "", strip_tags($string)));
        $clean = preg_replace('/\s+/', "-", $clean);
        $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
        return ($force_lowercase) ?
            (function_exists('mb_strtolower')) ?
                mb_strtolower($clean, 'UTF-8') :
                strtolower($clean) :
            $clean;
    }

    /**
     * 编辑器上传图片命名
     * @param $filename
     * @return string
     */
    private function createUniqueFilename( $filename )
    {
        $upload_path = env('UPLOAD_PATH');
        $full_image_path = $upload_path . $filename . '.jpg';
        if ( \File::exists( $full_image_path ) )
        {
            // Generate token for image
            $image_token = substr(sha1(mt_rand()), 0, 5);
            return $filename . '-' . $image_token;
        }
        return $filename;
    }
}