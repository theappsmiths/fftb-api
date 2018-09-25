<?php

namespace App\Jobs\User;

use App\Jobs\Job;

use App\Modules\Users\Domain\Manager;

class UpdateUserLog extends Job
{
    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data)
    { 
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        error_log (json_encode ($this->data));

        // find for user and insert it's log
        (new Manager)->findUserByAttr ('id', $this->data['userId'])->log()->create($this->data);
    }
}
