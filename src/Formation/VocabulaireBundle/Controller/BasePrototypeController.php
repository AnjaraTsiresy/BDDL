<?php

namespace Formation\VocabulaireBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class BasePrototypeController extends Controller
{

    /**
     * @Route("/consulter_prototype", name="consulter_prototype")
     */
    public function consulter_prototypeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        $id_societe = 0;
        $societes = array();
        $prototype_accesss_array = array();
        if ($request->get('id_societe')) {
            $id_societe = $request->get('id_societe');
            $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
            $societe = $repositorySociete->find($id_societe);
            $prototype_accesss = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->findBy(array('societe' => $societe));
            $compteur = count($prototype_accesss);

        } else {

            $query = $em->createQuery(
                'SELECT p
                FROM FormationVocabulaireBundle:PrototypeAccess p
                ORDER BY p.id ASC'
            );
            $prototype_accesss = $query->getResult();
            #$prototype_accesss = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->findBy(1,50,0);
            $compteur = count($prototype_accesss);

        }
        $query_prototypes = $em->createQuery(
            'SELECT p
                FROM FormationVocabulaireBundle:PrototypeAccess p
                ORDER BY p.id ASC'
        );
        $prototypes = $query_prototypes->getResult();
        #$prototypes = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->findAll();

        foreach ($prototypes as $prototype_access) {
            $societes[] = $prototype_access->getSociete();
        }
        foreach ($prototype_accesss as $prototype_access) {
            $protoModel = new ProtoModel();
            $protoModel->setNbSoloc($this->getLESocAssocies($prototype_access->getSociete()->getId(), $prototype_access->getId()));
            $protoModel->setNbLeGen($this->getLEGenAssocies($prototype_access->getSociete()->getId(), $prototype_access->getId()));
            //$protoModel->setNbPage($this->getNbPagesAssocies($prototype_access->getSociete()->getId(), $prototype_access->getId()));

            $protoModel->setNbTermes($this->getTermesAssocies($prototype_access->getId()));

            $protoModel->setSociete($prototype_access->getSociete()->getDescription());
            $protoModel->setTraducteur($prototype_access->getTraducteur()->getNom());
            $protoModel->setType($prototype_access->getType());
            $protoModel->setDate($prototype_access->getDate());
            $prototype_accesss_array [] = $protoModel;
            /* $nb_le_gen = getLEGenAssocies($resp['id_societe'], $resp['id_prototype_access']);
             $nb_page = getNbPagesAssocies($resp['id_societe'], $resp['id_prototype_access']);
             $nb_termes = getTermesAssocies($resp['id_prototype_access']);
             $societe = getClient($resp['id_societe']);
             $traducteur = getTraducteur($resp['createur']); */
        }

        return $this->render('FormationVocabulaireBundle:Default:consulter_prototype.html.twig', array(
            'compteur' => $compteur,
            'id_societe' => $id_societe,
            'societes' => $societes,
            'prototype_accesss_array' => $prototype_accesss_array
        ));
    }

    /**
     * @Route("/export_prototype", name="export_prototype")
     */
    public function export_prototypeAction(Request $request)
    {
        if ($request->get('id_societe')) {
            $id_societe = $request->get('id_societe');
            $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
            $societe = $repositorySociete->find($id_societe);
            $prototype_accesss_array = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->findBy(array('societe' => $societe));


        } else {
            $prototype_accesss_array = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->findAll();


        }

        // ask the service for a Excel5
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("liuggio")
            ->setTitle('PROTOTYPE')
            ->setSubject('PROTOTYPE');

        $sheet = $phpExcelObject->setActiveSheetIndex(0);

        $sheet->setCellValue('A1', 'Société');
        $sheet->setCellValue('B1', 'Prototype');
        $sheet->setCellValue('C1', 'Date de création');

        $counter = 2;
        foreach ($prototype_accesss_array as $prototype_accesss) {
            $sheet->setCellValue('A' . $counter, $prototype_accesss->getSociete()->getDescription());
            $sheet->setCellValue('B' . $counter, $prototype_accesss->getType());
            $sheet->setCellValue('C' . $counter, $prototype_accesss->getDate()->format('d/m/Y'));
            $counter++;
        }

        $phpExcelObject->getActiveSheet()->setTitle('PROTOTYPE');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'liste_prototype.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;

    }


    private function getLESocAssocies($id_societe, $id_prototype_access)
    {

        $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
        $societe = $repositorySociete->find($id_societe);
        $prototype_access = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->findBy(array('id' => $id_prototype_access, 'societe' => $societe));

        return count($prototype_access);
    }

    private function getLEGenAssocies($id_societe, $id_prototype_access)
    {
        $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
        $societe = $repositorySociete->find(653);
        if($societe != null) {
            $prototype_access = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->findBy(array('id' => $id_prototype_access, 'societe' => $societe));
            return count($prototype_access);
        }
       return 0;
    }

    private function getNbPagesAssocies($id_societe, $id_prototype_access)
    {
        return 0;
    }

    private function getTermesAssocies($id_prototype_access)
    {
        $repositoryPrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess');
        $prototype_access = $repositoryPrototypeAccess->find($id_prototype_access);

        if ($prototype_access != null) {
            $vocabulaire_prototype_access = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess')->findBy(array('prototypeAccess' => $prototype_access));
            return count($vocabulaire_prototype_access);
        }
        return 0;
    }

    /**
     * @Route("/modif_prototype/{id}", name="modif_prototype")
     */
    public function mmodif_prototypeAction($id)
    {
        $nb_termes = 0;

        $traducteur = '';
        $nom_societe = '';
        $prototypeAccess = array();
        $repositoryPrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess');
        $societes = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe')->findAll();
        $traducteurs = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Traducteur')->findAll();
        $formatEditions = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:FormatEdition')->findAll();



            $prototypeAccess = $repositoryPrototypeAccess->find($id);
            if($prototypeAccess != null){
                $traducteur = $prototypeAccess->getTraducteur()->getNom();
                $nom_societe = $prototypeAccess->getSociete()->getDescription();
            }
            $nb_termes = $this->getTermesAssocies($id);

        return $this->render('FormationVocabulaireBundle:Default:modif_prototype.html.twig', array(
            'id' => $id,
            'nb_termes' => $nb_termes,
            'traducteur' => $traducteur,
            'nom_societe' => $nom_societe,
            'prototypeAccess' => $prototypeAccess,
            'societes' => $societes,
            'traducteurs' => $traducteurs,
            'formatEditions' => $formatEditions
        ));

    }

    /**
     * @Route("/update_prototype_action", name="update_prototype_action")
     */
    public function update_prototypeAction(Request $request)
    {
        $nb_termes = 0;
        $id = 0;
        $traducteur = '';
        $nom_societe = '';
        $prototypeAccess = array();
        $repositoryPrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess');
        $societes = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe')->findAll();
        $traducteurs = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Traducteur')->findAll();
        $formatEditions = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:FormatEdition')->findAll();


        if($request->get('id'))
        {
            $id = $request->get('id');
            $prototypeAccess = $repositoryPrototypeAccess->find($id);
            if($prototypeAccess != null){
                $traducteur = $prototypeAccess->getTraducteur()->getNom();
                $nom_societe = $prototypeAccess->getSociete()->getDescription();
            }
            $nb_termes = $this->getTermesAssocies($id);
        }

        return $this->render('FormationVocabulaireBundle:Default:modif_prototype.html.twig', array(
            'id' => $id,
            'nb_termes' => $nb_termes,
            'traducteur' => $traducteur,
            'nom_societe' => $nom_societe,
            'prototypeAccess' => $prototypeAccess,
            'societes' => $societes,
            'traducteurs' => $traducteurs,
            'formatEditions' => $formatEditions
        ));

    }
}

class ProtoModel
{
    private $nb_soloc;
    private $nb_le_gen;
    private $nb_page;
    private $nb_termes;
    private $societe;
    private $traducteur;
    private $type;
    private $date;

    public function type()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function date()
    {
        return $this->date;
    }

    public function setDate(\Datetime $date)
    {
        $this->date = $date;
    }

    public function nb_soloc()
    {
        return $this->nb_soloc;
    }

    public function setNbSoloc($nb_soloc)
    {
        $this->nb_soloc = $nb_soloc;
    }

    public function nb_le_gen()
    {
        return $this->nb_le_gen;
    }

    public function setNbLeGen($nb_le_gen)
    {
        $this->nb_le_gen = $nb_le_gen;
    }

    public function nb_page()
    {
        return $this->nb_page;
    }

    public function setNbPage($nb_page)
    {
        $this->nb_page = $nb_page;
    }

    public function nb_termes()
    {
        return $this->nb_termes;
    }

    public function setNbTermes($nb_termes)
    {
        $this->nb_termes = $nb_termes;
    }

    public function societe()
    {
        return $this->societe;
    }

    public function setSociete($societe)
    {
        $this->societe = $societe;
    }

    public function traducteur()
    {
        return $this->traducteur;
    }

    public function setTraducteur($traducteur)
    {
        $this->traducteur = $traducteur;
    }

}
