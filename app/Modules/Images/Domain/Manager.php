<?php

namespace App\Modules\Images\Domain;

use Illuminate\Filesystem\Filesystem;

class Manager {

    /**
     * Method to get image
     * 
     * @param string image-type
     * @param image-id
     * 
     * @return file
     */
    public function get (string $imageType, int $imageId) {
        $path = $this->getPath ($imageType, $imageId);

        $file = new Filesystem;

        // check if image path found
        if ($path) {
            $path = storage_path ("app/{$path}");

            // check if file exists
            if ($file->exists ($path)) {
                return $file->get ($path);
            }
        }
    }


    /**
     * Search for file path
     */
    protected function getPath (string $imageType, int $imageId) {
        $path = null;

        switch (strtoupper (trim ($imageType))) {
            case 'AVATAR':
                // check if user image exists
                $user = (new User)->find ($imageId);
                // check if user exists the redirect it's image
                $path = $user ? $user->image : null;
                break;
            default:
                break;
        }

        return $path;
    }

}