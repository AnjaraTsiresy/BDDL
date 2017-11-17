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
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            
        ));
    }
	
	/**
     * @Route("/admin", name="adminpage")
     */
    public function adminAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/admin.html.twig', array(
            
        ));
    }
	
	/**
     * @Route("/mise_a_jour_vocab", name="mise_a_jour_vocab")
     */
    public function mise_a_jour_vocabAction(Request $request)
    {
        $repositoryLanguage = $this->getDoctrine()->getRepository('AppBundle:Language');
        $languages = $repositoryLanguage->findAll(); 
        // replace this example code with whatever you need
        return $this->render('default/mise_a_jour_vocab.html.twig', array(
            'languages' => $languages,
        ));
    }
	
	
	/**
     * @Route("/uploadExcel", name="uploadExcel")
     */
    public function uploadExcelAction(Request $request)
    {
		
		
        // replace this example code with whatever you need
        return $this->render('default/uploadExcel.html.twig', array(
           
        ));
    }
}
