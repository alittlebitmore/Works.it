<?php

namespace Works\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $jobs = $em->getRepository('WorksCommonBundle:Job')->getLast();
        return $this->render('WorksCommonBundle:Default:index.html.twig', array('jobs' => $jobs));
    }
}
