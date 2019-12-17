<?php

namespace Tests\Browser\Traits;

trait EmailTrait {
    /** @var string URL of maildev web interface */
    protected $mailDevUrl = "http://localhost:1080";

    /**
     * Delete all emails in the mailbox
     *
     * @return void
     */
    public function deleteEmails()
    {
        $this->browse(function($browser) {
            $browser->visit($this->mailDevUrl)->click('a[ng-click="deleteAll()"]');
        });
    }

    /** @var int Number of seconds to wait for the email */
    protected $timeLimitSeconds = 5;

    /**
    * Get the email URL
    *
    * @return string The email URL
    */
    public function getEmail()
    {
        $urlEmail = null;

        $this->browse(function($browser) use (&$urlEmail) {
            $stopLoop = false;
            $currentLoop = 0;

            while (! $stopLoop) {
                $firstEmail = $browser->visit($this->mailDevUrl)->element('.email-item');
                if ($firstEmail === null) {
                    $currentLoop += 1;
                    if ($currentLoop >= $this->timeLimitSeconds) {
                        $this->assertTrue(false);
                    }
                    sleep(1);
                } else {
                    $stopLoop = true;
                    $urlEmail = $firstEmail->getAttribute('href');
                }
            }

        });

        $urlEmail = str_replace('#/', '', $urlEmail);
        $urlEmail .= '/html';

        return $urlEmail;
    }
}
