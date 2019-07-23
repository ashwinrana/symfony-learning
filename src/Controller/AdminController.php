<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function index()
    {
        return $this->render('views/admin/pages/dashboard.html.twig');
    }
}
