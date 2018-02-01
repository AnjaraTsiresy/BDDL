<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
       
        return $this->render('default/index.html.twig', array(
            
        ));
    }
	
    /**
     * @Route("/statistique", name="statistique")
     */
    public function statistiqueAction(Request $request)
    {
       
        return $this->render('default/statistique.html.twig', array(
            
        ));
    }
    

	/**
     * @Route("/admin", name="adminpage")
     */
    public function adminAction(Request $request)
    {

        return $this->render('default/admin.html.twig', array(

        ));
    }

    /**
     * @Route("/producteur", name="producteurpage")
     */
    public function producteurAction(Request $request)
    {

        return $this->render('default/producteur.html.twig', array(

        ));
    }

}
