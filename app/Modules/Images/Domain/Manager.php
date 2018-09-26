<?php

namespace App\Modules\Images\Domain;

use Illuminate\Filesystem\Filesystem;

use App\Modules\Users\Domain\Manager as UserManager;

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
        // find for image path from DB else display no-image as default
        $path = $this->getPath ($imageType, $imageId) ?? 'no-img.jpg';
        
        // update path in file-structure format
        $path = storage_path ("app/{$path}");

        $file = new Filesystem;

        // check if file exists
        if ($file->exists ($path)) {
            return $file->get ($path);
        }
    }


    /**
     * Search for file path
     */
    protected function getPath (string $imageType, int $imageId) {

        switch (strtoupper (trim ($imageType))) {
            case 'AVATAR':
                // check if user id exists
                $user = (new UserManager)->findUserByAttr ('id', $imageId);

                // check if user exists the redirect it's image
                return $user ? $user->profile()->first()->image : null;
            default:
                return null;
        }
    }

}