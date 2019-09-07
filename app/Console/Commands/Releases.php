<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Artisan;

class Releases extends Command
{

    /**
     * Release data, so we can install / update
     *
     * @var string
     */
    protected $releasesData;

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

    protected $themesPath = 'themes';

    protected $context;

    protected $attachmentsUrl;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->attachmentsUrl = config('laraone.release_attachments_url');
        $this->releasesData = $this->loadReleasesData();
        $this->context = stream_context_create([], ['notification' => [$this, 'downloadProgress']]);
    }

    public function fetchAdminTheme($release)
    {
        $adminThemeFileName = 'admin.zip';
        $adminThemeUrl = $this->attachmentsUrl . '/' . $release . '/' . $adminThemeFileName;
        $this->info('admin theme: ' . $adminThemeUrl);

        if($this->urlExists($adminThemeUrl)) {
            $this->info('Downloading admin theme. This may take few moments.');
            $adminThemeDownload = fopen($adminThemeUrl, 'r', null, $this->context);
            $adminThemePath = storage_path($this->themesPath . DIRECTORY_SEPARATOR . $adminThemeFileName);
            file_put_contents($adminThemePath, $adminThemeDownload);
            fclose($adminThemeDownload);
            $this->progressBar->finish();
            $this->output->newLine(1);
        } else {
            $this->info('Downloading admin theme failed. Theme url is either not correct or file is no longer there.');
            exit();
        }
    }

    public function fetchDefaultTheme($release)
    {
        $defaultThemeFileName = $this->getDefaultTheme();
        $defaultThemeUrl = $this->attachmentsUrl . '/' . $release . '/' . $defaultThemeFileName;
        $this->info('default theme: ' . $defaultThemeUrl);

        if($this->urlExists($defaultThemeUrl)) {
            $this->info('Downloading default frontend theme. This may take few moments.');
            $defaultThemeDownload = fopen($defaultThemeUrl, 'r', null, $this->context);
            $defaultThemePath = storage_path($this->themesPath . DIRECTORY_SEPARATOR . $defaultThemeFileName);
            file_put_contents($defaultThemePath, $defaultThemeDownload);
            fclose($defaultThemeDownload);
            $this->progressBar->finish();
            $this->output->newLine(1);
        } else {
            $this->info('Downloading default theme failed. Theme url is either not correct or file is no longer there.');
            exit();
        }
    }

    /**
     * Load release json object for processing, sort it in asc order by index key
     *
     * @return array
     */
    protected function loadReleasesData()
    {
        $json = json_decode(file_get_contents(base_path() . DIRECTORY_SEPARATOR . 'releases.json'), true);
        $releaseList = $json['releasesData'];
        usort($releaseList, [$this, 'ascSort']);

        return $releaseList;
    }

    protected function fetchLatestReleaseData()
    {
        $releasesUrl = config('laraone.releases_data_url');

        if($this->urlExists($releasesUrl)) {
            $this->info('Fetching latest release data.');
            $releasesDownload = fopen($releasesUrl, 'r', null, $this->context);
            $releasesPath = base_path('releases.json');
            file_put_contents($releasesPath, $releasesDownload);
            fclose($releasesDownload);
            $this->progressBar->finish();
            $this->output->newLine(1);
            $this->info('Downloaded latest release data.');
            $this->releasesData = $this->loadReleasesData();
        }
    }

    protected function fetchRelease($version)
    {
        $releaseUrl = config('laraone.releases_archive_url') . $version . '.zip';

        if($this->urlExists($releaseUrl)) {
            $this->info('Fetching release: ' . $releaseUrl);
            $releaseDownload = fopen($releaseUrl, 'r', null);
            $releasesPath = storage_path('releases' . DIRECTORY_SEPARATOR . $version . '.zip');
            file_put_contents($releasesPath, $releaseDownload);
            fclose($releaseDownload);
            // $this->progressBar->finish();
            $this->output->newLine(1);
            $this->info('Downloaded release ' . $version);
        }
    }

    /**
     * Find current version index in releases array
     *
     * @return mixed
     */
    protected function getReleaseIndex($version)
    {
        $index = 0;
        foreach($this->releasesData as $key => $value) {
            if ($value['version'] == $version) {
                $index = $value['index'];
                break;
            }
        }

        return $index;
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
        return $last;
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
