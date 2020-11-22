<?php

namespace App\Services;

class LaraOneService
{
    protected $releasesData;
    protected $themesPath = 'themes';
    protected $output;

    public function __construct()
	{
        $this->releasesData = $this->loadCMSReleasesData();
    }

    /**
     * Return release json
     *
     * @return array
     */
    public function getCMSReleasesData() {
        return $this->releasesData;
    }

    /**
     * Load release json object for processing, sort it in asc order by index key
     *
     * @return array
     */
    private function loadCMSReleasesData()
    {
        $json = json_decode(file_get_contents(base_path() . DIRECTORY_SEPARATOR . 'releases.json'), true);
        $releaseList = $json['releasesData'];
        usort($releaseList, [$this, 'ascSort']);

        return $releaseList;
    }

    /**
     * Returns compatible theme release if one is found
     *
     * @return string
     */
    private function getCompatibleThemeRelease($releaseData, $cmsVersion)
    {
        $cmsVersion = (int) str_replace(".", "", $cmsVersion);
        foreach($releaseData as $key => $value) {
            $minimumCmsVersion = (int) str_replace(".", "", optional($value->dependency)->laraone);
            if ($minimumCmsVersion && $minimumCmsVersion <= $cmsVersion) {
                return $value;
            }
        }

        return false;
    }

    public function fetchAndUpdateActiveTheme($cmsVersion)
    {
        $activeThemeId = get_website_setting('website.general.activeTheme');
        return $this->fetchAndUpdateTheme($cmsVersion, $activeThemeId);
    }

    public function fetchAndUpdateTheme($cmsVersion, $themeId)
    {
        $themeService = new ThemeService;
        $theme = $themeService->getTheme($themeId);
        $themeFileName = $theme->folder . '.zip';
        $fetch = $this->fetchTheme($cmsVersion, $theme->releases_url, $theme->download_url, $theme->folder);
        if($fetch) {
            $themePath = storage_path('themes'. DIRECTORY_SEPARATOR . $themeFileName);
            $return = $themeService->updateTheme($themePath);
            $this->info($return->message);
        }

        return $fetch;
    }

    /**
     * Downloads default theme
     *
     */
    public function fetchDefaultTheme($cmsVersion)
    {
        $themeName = config('laraone.default_theme.name');
        $relasesUrl = config('laraone.default_theme.releases_url');
        $downloadUrl = config('laraone.default_theme.download_url');
        return $this->fetchTheme($cmsVersion, $relasesUrl, $downloadUrl, $themeName);
    }

    /**
     * Downloads specific theme
     *
     */
    protected function fetchTheme($cmsVersion, $themeRelasesUrl, $downloadUrl, $themeName)
    {
        $themeReleaseData = json_decode(file_get_contents($themeRelasesUrl));
        $compatibleRelease = $this->getCompatibleThemeRelease($themeReleaseData->releasesData, $cmsVersion);
        
        if($compatibleRelease) {
            $themeFileName = $themeName . '.zip';
            $themeUrl = $downloadUrl . '/' . $compatibleRelease->version . '/' . $themeFileName;

            if($this->urlExists($themeUrl)) {
                $this->info('Downloading ' . ucfirst($themeName) . ' theme v.' . $compatibleRelease->version);
                $themeDownload = fopen($themeUrl, 'r', null);
                $themePath = storage_path($this->themesPath . DIRECTORY_SEPARATOR . $themeFileName);
                file_put_contents($themePath, $themeDownload);
                fclose($themeDownload);
                return true;
            } else {
                $this->info('Downloading theme failed. Theme url is either not correct or file is no longer there.');
                $this->info('Theme name: ' . $themeFileName);
                return false;
            }
        } else {
            $this->info(ucfirst($themeName) .' already up to date!');
            return true;
        }

        return false;
    }

    public function info($text)
    {
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $output->writeln("<info>$text</info>");
    }

    /**
     * Find version index in releases array
     *
     * @return mixed
     */
    public function getReleaseIndex($version)
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
     * Get last Index
     *
     * @return mixed
     */
    public function getLastIndex()
    {
        $last = end($this->releasesData);
        return $last['index'];
    }

    /**
     * Get current version
     *
     * @return mixed
     */
    public function getLastVersion()
    {
        $last = end($this->releasesData);
        return $last['version'];
    }

    /**
     * Get current release
     *
     * @return string
     */
    public function getLastRelease()
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

}
