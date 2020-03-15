<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Artisan;
use Validator;
use App\Services\LaraOneService;

class BaseCommand extends Command
{

    /**
     * Release data, so we can install / update
     *
     * @var string
     */
    protected $releasesData;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraone:base';

    protected $updateService;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Base command class, should not be run directly from CLI, other commands extend this class.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->updateService = new LaraOneService;
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
