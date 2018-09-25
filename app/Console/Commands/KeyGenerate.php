<?php
namespace App\Console\Commands;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;


class KeyGenerate extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'key:generate';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Set the application key";
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $key = $this->getRandomKey();
        if ($this->option('show')) {
            return $this->line('<comment>'.$key.'</comment>');
        }
        $path = base_path('.env');
        if (file_exists($path)) {
            if (file_put_contents(
                $path,
                str_replace(env('APP_KEY'), $key, file_get_contents($path))
            )) {
                // display key after successfully generated/updated
                $this->info("Application key [$key] set successfully on path: [$path].");
            }
        }
    }
    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    protected function getRandomKey()
    {
        return Str::random(32);
    }
    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('show', null, InputOption::VALUE_NONE, 'Simply display the key instead of modifying files.'),
        );
    }
}