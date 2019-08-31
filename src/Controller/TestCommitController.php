<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestCommitController extends AbstractController
{
    /**
     * @Route("/test/commit", name="test_commit")
     */
    public function index()
    {
        return $this->render('test_commit/index.html.twig', [
            'controller_name' => 'TestCommitController',
        ]);
    }
}
