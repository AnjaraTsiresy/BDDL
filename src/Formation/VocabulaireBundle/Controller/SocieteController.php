<?php

namespace Formation\VocabulaireBundle\Controller;

use Formation\VocabulaireBundle\Entity\Societe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SocieteController extends Controller {

    /**
     * @Route("/add_societe", name="add_societe")
     */
    public function addAction() {

        return $this->render('FormationVocabulaireBundle:Societe:add.html.twig', array(
        ));
    }

    /**
     * @Route("/save_societe", name="save_societe")
     */
    public function saveAction(Request $request) {
        $societe = "";
        if ($request->get('societe')) {
            $societe = $request->get('societe');
        }
        $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
        $verif_societe = $repositorySociete->verifSociete($societe);
        if ($societe == "") {
            echo "<SCRIPT language=javascript> alert('Veuillez remplir le nom de société');</SCRIPT>";
        } elseif ($verif_societe > 0) {
            echo "<SCRIPT language=javascript> alert('cette société éxiste déjà');</SCRIPT>";
        } else {
            $em = $this->getDoctrine()->getManager();
            $societe_obj = new Societe();
            $societe_obj->setDescription($societe);
            $societe_obj->setAddress1("");
            $societe_obj->setAddress2("");
            $societe_obj->setCodeTiers("");
            $societe_obj->setCompteur(0);
            $societe_obj->setCp(0);
            $societe_obj->setVille("");
            $societe_obj->setDirection("");
            $societe_obj->setTelStandard("");
            $societe_obj->setFaxStandard("");
            $societe_obj->setTypeTiers("");
            $em->persist($societe_obj);
            $em->flush();
            return $this->redirectToRoute('mise_a_jour_vocab');
        }

        return $this->render('FormationVocabulaireBundle:Societe:add.html.twig', array(
        ));
    }

    /**
     * @Route("/liste_societe", name="liste_societe")
     */
    public function listeAction(Request $request) {
        $societe = "";
        if ($request->get('societe')) {
            $societe = $request->get('societe');
        }
        $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
        $nb_soc = $repositorySociete->getNbSocietes($societe);
        $societes = $repositorySociete->findSociete($societe);
        $repositoryPrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess');
        $societes_array = array();
        foreach ($societes as $s) {
            $pro = new \Formation\VocabulaireBundle\Model\ProtoType();
            $pro->setNom_societe($s['description']);
            $pro->setIdSociete($s['id_societe']);
            $pro->setNbSoloc($repositoryPrototypeAccess->getNbProtoParSociete($s['id_societe']));
            $societes_array[] = $pro;
        }
        $url = $this->generateUrl(
                'edit_societe',
                array(),
                UrlGeneratorInterface::ABSOLUTE_URL
        );
        return $this->render('FormationVocabulaireBundle:Societe:liste.html.twig', array(
                    'societe' => $societe,
                    'societes' => $societes_array,
                    'nb_soc' => $nb_soc,
                    'url' => $url
        ));
    }

    /**
     * @Route("/update_societe", name="update_societe")
     */
    public function updateAction(Request $request) {
        $societe = "";
        $id_societe = 0;
        if ($request->get('societe')) {
            $societe = $request->get('societe');
        }
        if ($request->get('id_societe')) {
            $id_societe = $request->get('id_societe');
        }
        $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
        $societe_obj = $repositorySociete->find($id_societe);
        if ($societe_obj != null) {
            $em = $this->getDoctrine()->getManager();
            $societe_obj->setDescription($societe);
            $em->merge($societe_obj);
            $em->flush();
        }
        echo "<SCRIPT language=javascript> window.opener.location.reload(true); window.close();</SCRIPT>";
        return "";
    }

    /**
     * @Route("/delete_societe/{id_societe}", name="delete_societe")
     */
    public function deleteSocieteAction($id_societe) {

        $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
        $societe_obj = $repositorySociete->find($id_societe);
        if ($societe_obj != null) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($societe_obj);
            $em->flush();
        }
        echo "<SCRIPT language=javascript> alert('Société supprimée avec succès');</SCRIPT>";
        return $this->redirectToRoute('liste_societe');
    }

    /**
     * @Route("/edit_societe", name="edit_societe")
     */
    public function editAction(Request $request) {
        $id_societe = 0;
        $societe = "";
        if ($request->get('id')) {
            $id_societe = $request->get('id');
            $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
            $societe_obj = $repositorySociete->find($id_societe);
            if ($societe_obj != null)
                $societe = $societe_obj->getDescription();
        }
        return $this->render('FormationVocabulaireBundle:Societe:update.html.twig', array(
                    'societe' => $societe,
                    'id_societe' => $id_societe,
        ));
    }

    /**
     * @Route("/export_societe", name="export_societe")
     */
    public function export_societeAction(Request $request) {
        $societe = "";
        $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
        $societes = $repositorySociete->findSociete($societe);
        // ask the service for a Excel5
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("liuggio")
                ->setTitle('PROTOTYPE')
                ->setSubject('PROTOTYPE');

        $sheet = $phpExcelObject->setActiveSheetIndex(0);

        $sheet->setCellValue('A1', 'Identifiant en base');
        $sheet->setCellValue('B1', 'Nom de la société');
        $sheet->setCellValue('C1', 'code Tiers');

        $counter = 2;
        foreach ($societes as $s) {
            $sheet->setCellValue('A' . $counter, $s['id_societe']);
            $sheet->setCellValue('B' . $counter, $s['description']);
            $sheet->setCellValue('C' . $counter, $s['codeTiers']);
            $counter++;
        }

        $phpExcelObject->getActiveSheet()->setTitle('SOCIETES');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
                \Symfony\Component\HttpFoundation\ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'liste_societe.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }

}
