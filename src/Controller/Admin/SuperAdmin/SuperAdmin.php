<?php


namespace App\Controller\Admin\SuperAdmin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SuperAdmin
 * @package App\Controller\Admin\SuperAdmin
 * @Route("/admin")
 */
class SuperAdmin extends AbstractController
{

    /**
     * @Route("/su/users", name="users")
     */
    public function users()
    {
        return $this->render('admin/users.html.twig');
    }


    /**
     * @Route("/su/upload-video", name="upload_video")
     */
    public function uploadVideos()
    {
        return $this->render('admin/upload_video.html.twig');
    }


}
