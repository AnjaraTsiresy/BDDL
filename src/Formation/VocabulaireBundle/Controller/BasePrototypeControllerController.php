<?php

namespace Formation\VocabulaireBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BasePrototypeControllerController extends Controller
{

    /**
     * @Route("/consulter_prototype", name="consulter_prototype")
     */
    public function consulter_prototypeAction(Request $request)
    {
        $id_societe = 0;
        $societes = [];
        $prototype_accesss_array = [];
        if($request->get('id_societe')){
            $id_societe = $request->get('id_societe');
            $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
            $societe = $repositorySociete->find($id_societe);
            $prototype_accesss = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->findBy(array('societe' => $societe));
            $compteur = count($prototype_accesss);

        }
        else {
            $prototype_accesss = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->findAll();
            $compteur = count($prototype_accesss);

        }

        $prototypes = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->findAll();

        foreach ($prototypes as $prototype_access)
        {
            $societes[] = $prototype_access->getSociete();
        }

        foreach ($prototype_accesss as $prototype_access)
        {
            $protoModel = new ProtoModel();
            $protoModel->setNbSoloc($this->getLESocAssocies($prototype_access->getSociete()->getId(), $prototype_access->getId()));
            $protoModel->setNbLeGen($this->getLEGenAssocies($prototype_access->getSociete()->getId(), $prototype_access->getId()));
            $protoModel->setNbPage($this->getNbPagesAssocies($prototype_access->getSociete()->getId(), $prototype_access->getId()));
            $protoModel->setNbTermes($this->getTermesAssocies($prototype_access->getId()));
            $protoModel->setSociete($prototype_access->getSociete()->getDescription());
            $protoModel->setTraducteur($prototype_access->getTraducteur()->getNom());
            $protoModel->setType($prototype_access->getType());
            $protoModel->setDate($prototype_access->getDate());
            $prototype_accesss_array [] = $protoModel;
            /*$nb_le_gen = getLEGenAssocies($resp['id_societe'], $resp['id_prototype_access']);
            $nb_page = getNbPagesAssocies($resp['id_societe'], $resp['id_prototype_access']);
            $nb_termes = getTermesAssocies($resp['id_prototype_access']);
            $societe = getClient($resp['id_societe']);
            $traducteur = getTraducteur($resp['createur']);*/
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
        if($request->get('id_societe')){
            $id_societe = $request->get('id_societe');
            $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
            $societe = $repositorySociete->find($id_societe);
            $prototype_accesss_array = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->findBy(array('societe' => $societe));


        }
        else {
            $prototype_accesss_array = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->findAll();


        }
        return $this->render('FormationVocabulaireBundle:Default:exportPrototype.html.twig', array(
            'prototype_accesss_array' => $prototype_accesss_array
        ));
    }


    private function getLESocAssocies($id_societe, $id_prototype_access){

        $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
        $societe = $repositorySociete->find($id_societe);
        $prototype_access = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->findBy(array('id'=>$id_prototype_access,'societe' => $societe));

        return count($prototype_access);
    }

    private function getLEGenAssocies($id_societe, $id_prototype_access){
        $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
        $societe = $repositorySociete->find(653);
        $prototype_access = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->findBy(array('id'=>$id_prototype_access,'societe' => $societe));

        return count($prototype_access);
    }

    private function getNbPagesAssocies($id_societe, $id_prototype_access){
	    return 0;
    }

    private function getTermesAssocies($id_prototype_access){
        $repositoryPrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess');
        $prototype_access = $repositoryPrototypeAccess->find($id_prototype_access);
        $vocabulaire_prototype_access = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess')->findBy(array('prototypeAccess' => $prototype_access));

        return count($vocabulaire_prototype_access);
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

    public function type(){
        return $this->type;
    }
    public function setType($type){
        $this->type = $type;
    }
    public function date(){
        return $this->date;
    }
    public function setDate(\Datetime $date){
        $this->date = $date;
    }
    public function nb_soloc(){
        return $this->nb_soloc;
    }
    public function setNbSoloc($nb_soloc){
        $this->nb_soloc = $nb_soloc;
    }

    public function nb_le_gen(){
        return $this->nb_le_gen;
    }
    public function setNbLeGen($nb_le_gen){
        $this->nb_le_gen = $nb_le_gen;
    }

    public function nb_page(){
        return $this->nb_page;
    }
    public function setNbPage($nb_page){
        $this->nb_page = $nb_page;
    }

    public function nb_termes(){
        return $this->nb_termes;
    }
    public function setNbTermes($nb_termes){
        $this->nb_termes = $nb_termes;
    }

    public function societe(){
        return $this->societe;
    }
    public function setSociete($societe){
        $this->societe = $societe;
    }

    public function traducteur(){
        return $this->traducteur;
    }
    public function setTraducteur($traducteur){
        $this->traducteur = $traducteur;
    }

}
