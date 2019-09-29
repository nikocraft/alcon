<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Artisan;
use Validator;

class BaseCommand extends Command
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
    protected $signature = 'laraone:base';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Base Command Class, should not be run directly from CLI';

    protected $themesPath = 'themes';

    protected $context;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->releasesData = $this->loadPhoenixReleasesData();
        $this->context = stream_context_create([], ['notification' => [$this, 'downloadProgress']]);
    }

    /**
     * Returns compatible theme release if one is found
     *
     * @return string
     */
    private function getCompatibleThemeRelease($releaseData, $phoenixRelease)
    {
        $phoenixRelease = (int) str_replace(".", "", $phoenixRelease);
        foreach($releaseData as $key => $value) {
            $minimumPhoenixVersion = (int) str_replace(".", "", $value->minimumPhoenixVersion);
            if ($minimumPhoenixVersion <= $phoenixRelease) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Downloads admin spa theme
     *
     */
    public function fetchAdminTheme($phoenixRelease)
    {
        $adminThemeFileName = config('laraone.admin_file_name');
        $adminSpaRelasesDataUrl = config('laraone.admin_releases_url');
        $adminSpaReleaseData = json_decode(file_get_contents($adminSpaRelasesDataUrl));
        // $last = end($adminSpaReleaseData->releasesData);
        $compatibleRelease = $this->getCompatibleThemeRelease($adminSpaReleaseData->releasesData, $phoenixRelease);

        $downloadUrl = config('laraone.admin_download_url');
        $adminThemeUrl = $downloadUrl . '/' . $compatibleRelease->version . '/' . $adminThemeFileName;

        if($this->urlExists($adminThemeUrl)) {
            $this->info('Downloading admin theme ' . $adminThemeUrl);
            $adminThemeDownload = fopen($adminThemeUrl, 'r', null, $this->context);
            $adminThemePath = storage_path($this->themesPath . DIRECTORY_SEPARATOR . $adminThemeFileName);
            file_put_contents($adminThemePath, $adminThemeDownload);
            fclose($adminThemeDownload);
            $this->progressBar->finish();
            $this->output->newLine(1);
        } else {
            $this->info('Downloading admin theme failed. Theme url is either not correct or file is no longer there.');
            $this->info('admin theme url: ' . $adminThemeUrl);
            exit();
        }
    }

    /**
     * Downloads default theme
     *
     */
    public function fetchDefaultTheme($phoenixRelease)
    {
        $defaultThemeFileName = config('laraone.default_theme_file_name');
        $themeRelasesUrl = config('laraone.default_theme_releases_url');
        $themeReleaseData = json_decode(file_get_contents($themeRelasesUrl));

        $compatibleRelease = $this->getCompatibleThemeRelease($themeReleaseData->releasesData, $phoenixRelease);

        $downloadUrl = config('laraone.default_theme_download_url');
        $defaultThemeUrl = $downloadUrl . '/' . $compatibleRelease->version . '/' . $defaultThemeFileName;
        

        if($this->urlExists($defaultThemeUrl)) {
            $this->info('Downloading default frontend theme ' . $defaultThemeUrl);
            $defaultThemeDownload = fopen($defaultThemeUrl, 'r', null, $this->context);
            $defaultThemePath = storage_path($this->themesPath . DIRECTORY_SEPARATOR . $defaultThemeFileName);
            file_put_contents($defaultThemePath, $defaultThemeDownload);
            fclose($defaultThemeDownload);
            $this->progressBar->finish();
            $this->output->newLine(1);
        } else {
            $this->info('Downloading default theme failed. Theme url is either not correct or file is no longer there.');
            $this->info('default theme url: ' . $defaultThemeUrl);
            exit();
        }
    }


    /**
     * Downloads specific theme
     *
     */
    public function fetchTheme($phoenixRelease, $themeRelasesUrl, $downloadUrl, $themeFileName)
    {
        $themeReleaseData = json_decode(file_get_contents($themeRelasesUrl));
        $compatibleRelease = $this->getCompatibleThemeRelease($themeReleaseData->releasesData, $phoenixRelease);
        $themeUrl = $downloadUrl . '/' . $compatibleRelease->version . '/' . $themeFileName;

        if($this->urlExists($themeUrl)) {
            $this->info('Downloading theme ' . $themeUrl);
            $themeDownload = fopen($themeUrl, 'r', null, $this->context);
            $themePath = storage_path($this->themesPath . DIRECTORY_SEPARATOR . $themeFileName);
            file_put_contents($themePath, $themeDownload);
            fclose($themeDownload);
            $this->progressBar->finish();
            $this->output->newLine(1);
        } else {
            $this->info('Downloading theme failed. Theme url is either not correct or file is no longer there.');
            $this->info('Theme url: ' . $themeUrl);
            exit();
        }
    }

    /**
     * Load release json object for processing, sort it in asc order by index key
     *
     * @return array
     */
    protected function loadPhoenixReleasesData()
    {
        $json = json_decode(file_get_contents(base_path() . DIRECTORY_SEPARATOR . 'releases.json'), true);
        $releaseList = $json['releasesData'];
        usort($releaseList, [$this, 'ascSort']);

        return $releaseList;
    }

    /**
     * Downloads latest Phoenix Release Data from github
     *
     * @return mixed
     */
    protected function fetchLatestReleaseData()
    {
        $releasesUrl = config('laraone.phoenix_releases_url');

        if($this->urlExists($releasesUrl)) {
            $this->info('Fetching latest release data.');
            $releasesDownload = fopen($releasesUrl, 'r', null, $this->context);
            $releasesPath = base_path('releases.json');
            file_put_contents($releasesPath, $releasesDownload);
            fclose($releasesDownload);
            $this->progressBar->finish();
            $this->output->newLine(1);
            $this->info('Downloaded latest release data.');
            $this->releasesData = $this->loadPhoenixReleasesData();
        }
    }

    /**
     * Downloads latest Phoenix Release from github
     *
     * @return mixed
     */
    protected function fetchRelease($version)
    {
        $releaseUrl = config('laraone.phoenix_download_url') . $version . '.zip';

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
    protected function getDefaultThemeFileName()
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

    /**
     * Prompt the user for input.
     *
     * @param  string $question
     * @param  string $default
     * @param  callable|null $validator
     * @return string
     */
    public function askWithValidation($question, $default = null, $validator = null)
    {
        return $this->output->ask($question, $default, $validator);
    }

    /**
     * Validates the user input
     *
     * @param string $attribute
     * @param string $validation
     * @param string $value
     * @throws Exception
     * @return string
     */
    protected function validateInput(string $attribute, string $validation, $value)
    {
        if (! is_array($value) && ! is_bool($value) && 0 === strlen($value)) {
            throw new \Exception('A value is required.');
        }

        $validator = Validator::make([
            $attribute => $value
        ], [
            $attribute => $validation
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first($attribute));
        }

        return $value;
    }
}
