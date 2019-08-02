<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Releases extends Command
{

    /**
     * Release data, so we can install / update
     *
     * @var string
     */
    private $releasesData;

    /**
     * Progressbar to show while downloading files
     *
     * @var object
     */
    protected $progressBar;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraone:relases';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->releasesData = $this->getReleasesData();
    }

    /**
     * Load release json object for processing, sort it in asc order by index key
     *
     * @return array
     */
    protected function getReleasesData()
    {
        $json = json_decode(file_get_contents(base_path() . DIRECTORY_SEPARATOR . 'laraone.json'), true);

        $releaseList = $json['releasesData'];

        usort($releaseList, [$this, 'ascSort']);
        return $releaseList;
    }

    /**
     * Find current version index in releases array
     *
     * @return mixed
     */
    protected function getCurrentIndex($version)
    {
        $currentIndex = 0;
        foreach($this->releasesData as $key => $value) {
            if ($value['version'] == $version) {
                $currentIndex = $value['index'];
                break;
            }
        }

        return $currentIndex;
    }

    /**
     * Get last version
     *
     * @return mixed
     */
    protected function getLastIndex()
    {
        $last = end($this->releasesData);
        return $last['index'];
    }

    /**
     * Get last version
     *
     * @return mixed
     */
    protected function getLastVersion()
    {
        $last = end($this->releasesData);
        return $last['version'];
    }

    /**
     * Get last version
     *
     * @return string
     */
    protected function getDefaultTheme()
    {
        $last = end($this->releasesData);
        return $last['default_theme'];
    }

    /**
     * Get last version
     *
     * @return string
     */
    protected function getLastRelease()
    {
        $last = end($this->releasesData);
        return $last['version'];
    }

    /**
     * Sort index array in asc order
     * 
     * @param $first
     * @param $second
     * 
     * @return mixed
     */
    protected function ascSort($first, $second)
    {
      return ($first["index"] <= $second["index"]) ? -1 : 1;
    }

    /**
     * Check if hosted file exists
     *
     * @param int $urlPath
     * @return boolean
     */
    protected function urlExists($urlPath)
    {
        $file_headers = @get_headers($urlPath);
        if (stripos($file_headers[0], "404 Not Found") > 0 || (stripos($file_headers[0], "302 Found") > 0 && stripos($file_headers[7], "404 Not Found") > 0)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $notificationCode
     * @param int $severity
     * @param string $message
     * @param int $messageCode
     * @param int $bytesTransferred
     * @param int $bytesMax
     */
    protected function downloadProgress($notificationCode, $severity, $message, $messageCode, $bytesTransferred, $bytesMax)
    {
        switch ($notificationCode) {
            case STREAM_NOTIFY_FILE_SIZE_IS:
                if ($this->progressBar) {
                    $this->progressBar->clear();
                }
                $this->progressBar = $this->output->createProgressBar($bytesMax);
                break;
            case STREAM_NOTIFY_PROGRESS:
                if (is_null($this->progressBar)) {
                    $this->progressBar = $this->output->createProgressBar($this->output);
                }
                $this->progressBar->setProgress($bytesTransferred);
                break;
            case STREAM_NOTIFY_COMPLETED:
                $this->finish($bytesTransferred);
                break;
        }
    }

}
