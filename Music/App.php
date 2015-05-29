<?php
namespace Music;

use Music\Lib\DatabaseProvider;
use Music\Lib\LocalImport;
use Music\Lib\Song;
use Music\Lib\SongFinder;
use Music\Lib\SongOverride;
use Music\Lib\Storage;
use Music\Lib\UploadManager;
use Music\Lib\ViewManager;
use Music\Util\Brainz;

class App
{
    /**
     * @var string
     */
    private $rootPath;
    /**
     * @var \Music\Lib\Storage\IStorage
     */
    private $storageManager;
    /**
     * @var \Music\Lib\UploadManager
     */
    private $uploadeManager;
    /**
     * @var \Music\Lib\ViewManager
     */
    private $viewManager;
    /**
     * @var \Music\Lib\DatabaseProvider
     */
    private $databaseInstance;
    /**
     * @var \Music\Util\Config
     */
    private $config;

    public function __construct(\Music\Util\Config $config = null)
    {
        $this->setupConfig($config);
    }

    /**
     * @return mixed
     */
    public function getStorageManager()
    {
        return $this->storageManager;
    }

    /**
     * @param mixed $storageManager
     */
    public function setStorageManager($storageManager)
    {
        $this->storageManager = $storageManager;
    }

    /**
     * @return mixed
     */
    public function getUploadeManager()
    {
        return $this->uploadeManager;
    }

    /**
     * @param mixed $uploadeManager
     */
    public function setUploadeManager($uploadeManager)
    {
        $this->uploadeManager = $uploadeManager;
    }

    /**
     * @return mixed
     */
    public function getDatabaseInstance()
    {
        return $this->databaseInstance;
    }

    /**
     * @param mixed $databaseInstance
     */
    public function setDatabaseInstance($databaseInstance)
    {
        $this->databaseInstance = $databaseInstance;
    }

    /**
     * @return mixed
     */
    public function getRootPath()
    {
        return $this->rootPath;
    }

    /**
     * @param mixed $rootPath
     */
    public function setRootPath($rootPath)
    {
        $this->rootPath = $rootPath;
    }

    /**
     * @return \Music\Lib\ViewManager
     */
    public function getViewManager()
    {
        return $this->viewManager;
    }

    /**
     * @param \Music\Lib\ViewManager $viewManager
     */
    public function setViewManager(\Music\Lib\ViewManager $viewManager)
    {
        $this->viewManager = $viewManager;
    }

    /**
     * @return \Music\Util\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param \Music\Util\Config $config
     */
    public function setConfig(\Music\Util\Config $config)
    {
        $this->config = $config;
    }

    public function setupConfig(\Music\Util\Config $config = null)
    {
        if (!empty($config)) {
            $this->setConfig($config);
        }

        $config = $this->getConfig();

        if (null === $config) {
            return;
        }

        $objDatabaseProvider = new DatabaseProvider($this->config);

        $this->setRootPath($config->getParentPath(__FILE__));
        $this->setViewManager(new ViewManager($this->config));
        $this->setDatabaseInstance($objDatabaseProvider->getInstance());

        $uploadPath = $this->config->getConfig('config', 'upload');
        $uploadPath = $this->rootPath . $uploadPath;

        if ($this->config->getConfig('config', 'useAWS') == true) {
            $storageManager = new Storage\S3();
            $storageManager->setAwsAccessKey($this->config->getConfig('AWS', 'access_key'));
            $storageManager->setAwsSecretKey($this->config->getConfig('AWS', 'secret_key'));
            $storageManager->setAwsBucketName($this->config->getConfig('AWS', 'bucket_name'));
        } else {
            $storagePath    = $this->config->getConfig('config', 'storage');
            $storagePath    = $this->rootPath . $storagePath;
            $storageManager = new Storage\Local($storagePath);
        }

        $uploadManager = new UploadManager(array('upload_dir' => $uploadPath, 'storage' => $storageManager));

        $this->setStorageManager($storageManager);
        $this->setUploadeManager($uploadManager);
    }

    public function getBasePath()
    {
        $file = $_SERVER['SCRIPT_FILENAME'];

        return substr($file, 0, strlen($file) - strlen(strrchr($file, "/")) + 1);
    }

    public function identifyParameters()
    {
        $root = $this->getBasePath();

        $part = $_SERVER['SCRIPT_NAME'];

        if (isset($_SERVER['REDIRECT_URL'])) {
            $part = $_SERVER['REDIRECT_URL'];
        }

        $request = $_SERVER['DOCUMENT_ROOT'] . $part;

        $param = trim(str_replace($root, '', $request), '/');
        //$param = preg_split("/[\/\?]/", $param);
        $param = explode("/", $param);

        return $param;
    }

    function handleRequest()
    {
        $param = $this->identifyParameters();
        $page  = $param[0];
        $args  = array_slice($param, 1);

        if (($pos = strpos($page, '/')) !== false) {
            $args = explode('/', $page);
            $page = array_shift($args);
        }

        $action = "handle" . ucfirst($page);
        if (method_exists($this, $action)) {
            $this->$action($args);
        } elseif ($page == "" || $page == "index.php") {
            $this->handleDefault();
        }

        echo $this->getViewManager();
    }

    function handleDefault()
    {
        /*$finder  = new SongFinder($this->getDatabaseInstance());
        $artists = $finder->getArtistsAlbums();*/

        $this->getViewManager()->setFile('main_template.php');
        /*$this->getViewManager()->setVar('artists', $artists);*/
    }

    function handleUpload()
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $this->uploadeManager->post();
                break;
            default:
                header('HTTP/1.0 405 Method Not Allowed');
        }
    }

    function handleSearch()
    {
        if (!isset($_GET['q'])) {
            return;
        }

        $q      = $_GET['q'];
        $finder = new SongFinder($this->getDatabaseInstance());
        $songs  = array();

        if (!empty($q)) {
            if ($q == 'all' || $q == 'everything') {
                $songs = $finder->all();
            } else {
                $songs = $finder->search($q);
            }
        }

        $this->getViewManager()->setType('JSON');
        $this->getViewManager()->setVar('songs', $songs);
    }

    function handleDownload($args)
    {
        $hash = array_shift($args);

        $info = $this->storageManager->getInfo($hash);

        $fullLength = $info['size'];
        $start      = 0;
        $end        = $fullLength - 1;
        $S3Headers  = array();

        $header    = array();
        $headers[] = 'Accept-Ranges: bytes';
        $headers[] = 'Content-type: audio/mpeg';
        $headers[] = 'Cache-Control: must-revalidate, post-check=0, pre-check=0';
        $headers[] = 'Pragma: public';

        if (isset($_SERVER['HTTP_RANGE'])) {
            if (!preg_match('/^bytes=\d*-\d*(,\d*-\d*)*$/', $_SERVER['HTTP_RANGE'], $matches)) {
                $headers[] = 'HTTP/1.1 416 Requested Range Not Satisfiable';
                $headers[] = 'Content-Range: bytes */0';
                $this->_outputHeaders($headers);
                exit;
            }
            list(, $range) = explode('=', $_SERVER['HTTP_RANGE']);
            list($start, $end) = explode('-', $range);

            $start = empty($start) ? 0 : $start;
            $end   = empty($end) ? $fullLength - 1 : $end;
            $end   = min($end, $fullLength - 1);

            if ($start > $end) {
                $headers[] = 'HTTP/1.1 416 Requested Range Not Satisfiable';
                $headers[] = 'Content-Range: bytes */0';
                $this->_outputHeaders($headers);
                exit;
            }
            $length             = $end - $start + 1;
            $S3Headers['Range'] = "bytes=$start-$end";
            $headers[]          = 'HTTP/1.1 206 Partial Content';
            $headers[]          = "Content-Length: $length";
            $headers[]          = "Content-Range: bytes $start-$end/$length";
        } else {
            $headers[] = "Content-Length: $fullLength";
            $headers[] = "Content-Range: bytes $start-$end/$fullLength";
            //$headers[] = 'Content-Disposition: attachment; filename="' . $hash. '"';
        }
        $output = $this->storageManager->getFile($hash, $S3Headers);
        if ($output === false) {
            die('Error loading file');
        }
        $this->_outputHeaders($headers);
        echo $output;
    }

    function _outputHeaders($headers)
    {
        foreach ($headers as $header) {
            header($header);
        }
    }

    function handleArt($args)
    {
        $hash = array_shift($args);

        $finder = new SongFinder($this->getDatabaseInstance());
        $song   = $finder->getByHash($hash);

        if ($song === false) {
            return;
        }

        $albumHash = md5($song->artist . $song->album);

        $info = $this->storageManager->getInfo($albumHash);

        if (!$info['size']) {
            $param = array(
                'v'   => '1.0',
                'key' => 'AIzaSyALp6qvMuW2uGx1qg_kvmE-UtFPyZvoVqA',
                'q'   => $song->title . ' ' . $song->album . ' ' . $song->artist
            );

            $url = 'https://ajax.googleapis.com/ajax/services/search/images?' . http_build_query($param);

            $json = file_get_contents($url);
            $json = json_decode($json);

            if ($json && $json->responseData && $json->responseData->results) {
                if (count($json->responseData->results) > 0) {
                    $img = $json->responseData->results[0]->tbUrl;
                    $this->storageManager->saveFile($img, $albumHash);
                }
            }
        }

        $this->handleDownload($albumHash);
    }

    function handleUpdate()
    {
        $q      = $_POST['q'];
        $folder = $_POST['folder'];
        $artist = $_POST['artist'];
        $album  = $_POST['album'];
        $track  = $_POST['track'];
        $title  = $_POST['title'];

        $finder = new SongFinder();
        $songs  = $finder->search($q);
        foreach ($songs as $song) {
            $override         = new SongOverride($this->getDatabaseInstance());
            $override->folder = empty($folder) ? $song->folder : $folder;
            $override->artist = empty($artist) ? $song->artist : $artist;
            $override->album  = empty($album) ? $song->album : $album;
            $override->track  = empty($track) ? $song->track : $track;
            $override->title  = empty($title) ? $song->title : $title;
            $override->hash   = $song->hash;
            $override->save();
        }
    }

    function handleInventory()
    {
        $finder  = new SongFinder($this->getDatabaseInstance());
        $artists = $finder->getArtistsAlbums();

        $this->getViewManager()->setType('JSON');
        $this->getViewManager()->setVar('artists', $artists);
    }

    function handleImport()
    {
        set_time_limit(0);
        error_reporting(E_ALL);

        if (file_exists('import.log')) {
            unlink('import.log');
        }

        $srcDirs = array('C:/Users/Kevin/Music');

        $import = new LocalImport();
        $dirs   = array();

        foreach ($srcDirs as $srcDir) {
            $dirs = array_merge($dirs, $import->listFolders($srcDir));
        }

        foreach ($dirs as $dir) {
            $filePaths = $import->findMusic($dir);
            $songs     = array();

            foreach ($filePaths as $filePath) {
                $info = Song::getFileParts($filePath);
                if ($info !== false) {
                    echo "$filePath\n";

                    if ($this->storageManager->saveFile($filePath)) {
                        $song         = new Song();
                        $song->folder = $info['artist'];
                        $song->artist = $info['artist'];
                        $song->album  = $info['album'];
                        $song->track  = $info['track'];
                        $song->title  = $info['title'];
                        $song->hash   = $info['hash'];
                        $songs[]      = $song;
                    } else {
                        echo "storage failure\n";
                    }
                } else {
                    echo "(skipping) couldn't get ID3 tags\n";
                }

                file_put_contents('import.log', ob_get_contents(), FILE_APPEND);
                ob_flush();
            }

            foreach ($songs as $song) {
                $song->save();
            }
        }
        exit;
    }

    function handleInfo()
    {
        $s3Instance = new Storage\S3();
        $s3         = $s3Instance->getS3Instance();
        $result     = $s3->getBucket($s3Instance->getAwsBucketName());
        $total      = 0;

        foreach ($result as $row) {
            $total += $row['size'];
        }

        $total = $total / 1024 / 1024;

        echo "Total size: $total MB\n";
    }

    function handleGetUrl()
    {
        echo @file_get_contents($_GET['url']);
    }

    function handleTest()
    {
    }

    function handleUpdateArtistName($args)
    {
        $artist        = $_GET['old'];
        $newArtistName = $_GET['new'];

        $arrSongs = array();

        if ($artist != $newArtistName) {
            $objSongFinder = new SongFinder($this->getDatabaseInstance());
            $arrSongs      = $objSongFinder->getByArtist($artist);

            foreach ($arrSongs as $objSong) {
                $objSong->folder = $newArtistName;
                $objSong->artist = $newArtistName;
                $objSong->save();
            }
        }

        $this->getViewManager()->setType('JSON');
        $this->getViewManager()->setVar('affected', count($arrSongs));
        $this->getViewManager()->setVar('old', $artist);
        $this->getViewManager()->setVar('new', $newArtistName);
    }

    function handleGetArtistByBrainz($args)
    {
        $artist = $_GET['artist'];

        $brainz = new Brainz();

        $artists = $brainz->searchArtist($artist);

        $this->getViewManager()->setType('JSON');
        $this->getViewManager()->setVar('artists', $artists);
    }

    public static function registerAutoloader()
    {
        spl_autoload_register(__NAMESPACE__ . "\\App::load");
    }

    public static function load($className)
    {
        $namespace = __NAMESPACE__;

        if (substr($className, 0, strlen($namespace)) == $namespace) {
            $className = substr($className, strlen($namespace));
        }

        $className = ltrim($className, '\\');
        $fileName  = '';

        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }

        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        $fileName = __DIR__ . DIRECTORY_SEPARATOR . $fileName;

        if (is_file($fileName)) {
            require $fileName;
        }
    }
}