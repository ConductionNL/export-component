<?php

// api/src/Controller/BookController.php

namespace App\Controller;

use App\Entity\Export;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    public function __invoke(Export $data): Export
    {
        //$this->bookPublishingHandler->handle($data);

        return $data;
    }
}
