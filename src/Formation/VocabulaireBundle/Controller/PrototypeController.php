<?php

namespace Formation\VocabulaireBundle\Controller;

use Formation\VocabulaireBundle\Entity\Lexique;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PrototypeController extends Controller
{
    private function convert_utf8( $str ) {

        if (strpos($str, 'é') !== false) {
            return $str;
        }
        if (strpos($str, 'è') !== false) {
            return $str;
        }
        if (strpos($str, 'à') !== false) {
            return $str;
        }
        if (strpos($str, 'ç') !== false) {
            return $str;
        }
        $decoded = str_replace("â€™", "<<<<<<<<<<", $str);
        $decoded = str_replace("â€", "wwwwwwwwwwwwwwwwwww", $decoded);
        $decoded = str_replace("â€œ", "??", $decoded);
        $decoded = str_replace("â€¦â€", "++++", $decoded);
        $decoded = str_replace("â€¦", ">>>>>>>>>>>>>>>>>>>", $decoded);
        $decoded = str_replace("â€", "----", $decoded);
        $decoded = str_replace("Å“", "======", $decoded);
        $decoded = str_replace("Ã‰", "@@@@@@@@@@@@@@@@@@@@@@@@@@", $decoded);



        $decoded = mb_convert_encoding($decoded, 'ISO-8859-1', 'UTF-8');

        $decoded = str_replace("??", "'", $decoded);
        $decoded = str_replace("wwwwwwwwwwwwwwwwwww", "'", $decoded);
        $decoded = str_replace("<<<<<<<<<<", "'", $decoded);
        $decoded = str_replace("----", "“", $decoded);
        $decoded = str_replace("++++", "…”", $decoded);
        $decoded = str_replace(">>>>>>>>>>>>>>>>>>>", "…", $decoded);
        $decoded = str_replace("======", "œ", $decoded);
        $decoded = str_replace("@@@@@@@@@@@@@@@@@@@@@@@@@@", "É", $decoded);

        return $decoded;
        // return $str;
    }

    /**
     * @Route("/modif_prototype/{id}", name="modif_prototype")
     */
    public function modif_prototypeAction($id)
    {
        $nb_termes = 0;
        $id_societe = 0;
        $traducteur = '';
        $nom_societe = '';
        $prototypeAccess = array();
        $repositoryPrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess');
        $societes = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe')->findAll();
        $traducteurs = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Traducteur')->findAll();
        $formatEditions = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:FormatEdition')->findAll();
        $lexiques = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Lexique')->getAllLexiqueByPrototypeAccess($id);
        $lexiques1_array = array();
        $lexiques2_array = array();
        $lexiques3_array = array();

        $prototypeAccess = $repositoryPrototypeAccess->find($id);
        if ($prototypeAccess != null) {
            if($prototypeAccess->getTraducteur()->getId() != 0) 
            {   
                $traducteur = $prototypeAccess->getTraducteur()->getNom();
            }
            
            if($prototypeAccess->getSociete()->getId() != 0) 
            {   
                $nom_societe = $prototypeAccess->getSociete()->getDescription();
                $id_societe = $prototypeAccess->getSociete()->getId();
            }
        }
        $nb_termes_array = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess')->getTermesAssocies($id);
        $nb_termes = $nb_termes_array['nb_termes'];
        $nb_lxq1 = 0;
        $nb_lxq2 = 0;
        $nb_lxq3 = 0;
        foreach ($lexiques as $lx) {
            if ($lx->getSociete()->getId() == $prototypeAccess->getSociete()->getId()) {
                $nb_lxq1++;
                $lexique_obj = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Lexique')->getLexiqueBySocieteAndThemeAndPrototypeAccess($lx->getSociete()->getId(), $lx->getTheme()->getId(), $id);
                $lexique = new \Formation\VocabulaireBundle\Model\Lexique();
                $lexique->setNb_termes($this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire')->recupNbTermes($id, $lx->getSociete()->getId(), $lx->getTheme()->getId()));
                $lexique->setRang($lexique_obj[0]->getRang());
                $lexique->setLibelle_theme($this->convert_utf8($lexique_obj[0]->getTheme()->getLibelleTheme()));
                $lexique->setIdLexique($lexique_obj[0]->getId());
                $lexique->setNb_lxq($nb_lxq1);
                $lexique->setId_theme($lexique_obj[0]->getTheme()->getId());
                $lexiques1_array[] = $lexique;
            }elseif ($lx->getSociete()->getId() == 653){
                $nb_lxq2++;
                $lexique_obj = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Lexique')->getLexiqueBySocieteAndThemeAndPrototypeAccess($lx->getSociete()->getId(), $lx->getTheme()->getId(), $id);
                $lexique = new \Formation\VocabulaireBundle\Model\Lexique();
                $lexique->setNb_termes($this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire')->recupNbTermes($id, $lx->getSociete()->getId(), $lx->getTheme()->getId()));
                $lexique->setNom_societe($lexique_obj[0]->getSociete()->getDescription());
                $lexique->setRang($lexique_obj[0]->getRang());
                $lexique->setLibelle_theme($this->convert_utf8($lexique_obj[0]->getTheme()->getLibelleTheme()));
                $lexique->setIdLexique($lexique_obj[0]->getId());
                $lexique->setNb_lxq($nb_lxq2);
                $lexique->setId_theme($lexique_obj[0]->getTheme()->getId());
                $lexiques2_array[] = $lexique;
            }else{
                $nb_lxq3++;
                $lexique_obj = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Lexique')->getLexiqueBySocieteAndThemeAndPrototypeAccess($lx->getSociete()->getId(), $lx->getTheme()->getId(), $id);
                $lexique_3 = new \Formation\VocabulaireBundle\Model\Lexique();
                $lexique_3->setNb_termes($this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire')->recupNbTermes($id, $lx->getSociete()->getId(), $lx->getTheme()->getId()));
                $lexique_3->setNom_societe($lexique_obj[0]->getSociete()->getDescription());
                $lexique_3->setRang($lexique_obj[0]->getRang());
                $lexique_3->setLibelle_theme($this->convert_utf8($lexique_obj[0]->getTheme()->getLibelleTheme()));
                $lexique_3->setIdLexique($lexique_obj[0]->getId());
                $lexique_3->setId_theme($lexique_obj[0]->getTheme()->getId());
                $lexique_3->setNb_lxq($nb_lxq3);
                $lexiques3_array[] = $lexique_3;
            }
        }
        $prototypeAccess_type = "";
        if($prototypeAccess != null)
            $prototypeAccess_type = $this->convert_utf8($prototypeAccess->getType());
        return $this->render('FormationVocabulaireBundle:Prototype:modifPrototype.html.twig', array(
            'id' => $id,
            'nb_termes' => $nb_termes,
            'traducteur' => $this->convert_utf8($traducteur),
            'lexiques1' => $lexiques1_array,
            'lexiques2' => $lexiques2_array,
            'lexiques3' => $lexiques3_array,
            'id_societe' => $id_societe,
            'nom_societe' => $this->convert_utf8($nom_societe),
            'prototypeAccess' => $prototypeAccess,
            'prototypeAccess_type' => $prototypeAccess_type,
            'societes' => $societes,
            'traducteurs' => $traducteurs,
            'formatEditions' => $formatEditions,
            'nb_lxq1' => $nb_lxq1,
            'nb_lxq2' => $nb_lxq2,
            'nb_lxq3' => $nb_lxq3
        ));

    }

    /**
     * @Route("/update_prototype_action", name="update_prototype_action")
     */
    public function update_prototypeAction(Request $request)
    {
        $id = 0;

      if($request->get('id') && $request->get('type') && $request->get('id_societe') && $request->get('createur')
          && $request->get('format_edition') && $request->get('dateprototype') && $request->get('date_old')){
            $id = $request->get('id');
        }

        if($id > 0){
            $em = $this->getDoctrine()->getManager();
            $type = $request->get('type');
            $id_societe = intval($request->get('id_societe'));
            $societe = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe')->find($id_societe);
            $createur = $request->get('createur');
            $traducteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Traducteur')->find($createur);
            $id_format_edition = intval($request->get('format_edition'));
            $format_edition = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:FormatEdition')->find($id_format_edition);
            $id_prototype_access = intval($id);
            $prototype_access = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->find($id_prototype_access);
            $date = $request->get('dateprototype');
            $date_old =  $request->get('date_old');
            if($date_old == $date){
                $new_date = $date_old;
            }else{
                $array_date = explode("/", $date);
                $mois = $array_date[0];
                $jour = $array_date[1];
                $annee = $array_date[2];
                $new_date = $annee."-".$mois."-".$jour;
            }
            $prototype_access->setSociete($societe);
            $prototype_access->setTraducteur($traducteur);
            $prototype_access->setFormatEdition($format_edition);
            $prototype_access->setDate(new \DateTime($new_date));
            $prototype_access->setType($type);
            $em->merge($prototype_access);
            $em->flush();


            //gestion des rangs des LE
            $nb_lxq = intval($request->get('nb_lxq'));
            $nb_lxq1 = intval($request->get('nb_lxq1'));-
            $nb_lxq2 = intval($request->get('nb_lxq2'));
            for($i=1; $i <= $nb_lxq; $i++){
                $em = $this->getDoctrine()->getManager();
                $rangLE = $request->get("rangLE".$i);

                $id_lexique = $request->get("id_lexique".$i);
                $lexique = $em->getRepository('FormationVocabulaireBundle:Lexique')->find($id_lexique);
                $lexique->setRang($rangLE);
                $em->flush();

            }

            for($j=1; $j <= $nb_lxq1; $j++){
                $em = $this->getDoctrine()->getManager();
                $rang = $request->get("rang".$j);
                $id_lexique = $request->get("id_lxq".$j);
                $lexique = $em->getRepository('FormationVocabulaireBundle:Lexique')->find($id_lexique);
                $lexique->setRang($rang);
                $em->flush();

            }
            for($k=1; $k <= $nb_lxq2; $k++){
                $em = $this->getDoctrine()->getManager();
                $rangL = $request->get("rangL".$k);
                $id_lexique = $request->get("id_lexq".$k);
                $lexique = $em->getRepository('FormationVocabulaireBundle:Lexique')->find($id_lexique);
                $lexique->setRang($rangL);
                $em->flush();
            }
        }
        return $this->redirectToRoute('modif_prototype', array('id' => $id));

    }

    /**
     * @Route("/supprimer_prototype_le/{id_lexique}/{id_societe}/{id_theme}/{id_prototype_access}", name="supprimer_prototype_le")
     */
    public function supprimePrototypeLEAction($id_lexique,$id_societe, $id_theme, $id_prototype_access)
    {
        $vocabulaires = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire')->getVocabulaireByPrototypeAccessAndSocieteAndTheme($id_prototype_access, $id_societe, $id_theme);
        $em = $this->getDoctrine()->getManager();
        foreach ($vocabulaires as $voc)
        {
            $id_vocabulaire = $voc['id_vocabulaire'];

            $vocabulairePrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess')->getVocabulairePrototypeAccessByPrototypeAndVocabulaire($id_prototype_access, $id_vocabulaire);
            foreach ($vocabulairePrototypeAccess as $vpa)
            {

                $em->remove($vpa);
                $em->flush();
            }
         }

        $lexique = $em->getRepository('FormationVocabulaireBundle:Lexique')->find($id_lexique);

        if($lexique != null)
        {
            $em->remove($lexique);
            $em->flush();
        }

        return $this->redirectToRoute('modif_prototype', array('id' => $id_prototype_access));

     }


    /**
     * @Route("/voir_le/{id_societe}/{id_theme}/{id}/{dic}", name="voir_le")
     */
    public function voirLeAction($id_societe, $id_theme, $id, $dic)
    {
        $vocabulairePrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess')->getVocabulairePrototypeAccessByPrototypeAndSocieteAndTheme($id, $id_societe, $id_theme, $dic);
        $theme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Theme')->find($id_theme);
        $prototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->find($id);
        $libelle_theme = "";
        $nom_prototype = "";

        if($theme != null && $prototypeAccess != "")
        {
            $libelle_theme = $theme->getLibelleTheme();
            $nom_prototype = $prototypeAccess->getType();
        }

        foreach ($vocabulairePrototypeAccess  as $vpa)
        {

        }


        return $this->render('FormationVocabulaireBundle:Prototype:voirLE.html.twig', array(
            'id' => $id,
            'id_theme' => $id_theme,
            'id_societe' => $id_societe,
            'libelle_theme' => $this->convert_utf8($libelle_theme),
            'nom_prototype' => $this->convert_utf8($nom_prototype),
            'vocabulairePrototypeAccess' => $vocabulairePrototypeAccess
        ));

    }

    /**
     * @Route("/modif_le/{id_societe}/{id_theme}/{id}", name="modif_le")
     */
    public function modifLeAction($id_societe, $id_theme, $id)
    {
        $vocabulairePrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess')->getVocabulairePrototypeAccessByPrototypeAndSocieteAndTheme($id, $id_societe, $id_theme,'dic');
        $theme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Theme')->find($id_theme);
        $prototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->find($id);
        $libelle_theme = "";
        $nom_prototype = "";
        $url_add_new_termes = $this->generateUrl(
            'add_new_termes',
            array(),
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        if($theme != null && $prototypeAccess != "")
        {
            $libelle_theme = $theme->getLibelleTheme();
            $nom_prototype = $prototypeAccess->getType();
        }

        foreach ($vocabulairePrototypeAccess  as $vpa)
        {

        }
        $url = $this->generateUrl(
            'modif_contenu_le',
            array(),
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        return $this->render('FormationVocabulaireBundle:Prototype:modifLe.html.twig', array(
            'id' => $id,
            'id_theme' => $id_theme,
            'id_societe' => $id_societe,
            'libelle_theme' => $libelle_theme,
            'nom_prototype' => $nom_prototype,
            'url_add_new_termes' => $url_add_new_termes,
            'url' => $url
        ));

    }


/**
     * @Route("/modif_les", name="modif_les")
     */
    public function modifLesAction(Request $request)
    {
        $id = 0;
        $id_societe = 0;
        $id_theme = 0;
         if ($request->get('id')) {
            $id = intval($request->get('id'));
        }
        if ($request->get('id_theme')) {
            $id_theme = intval($request->get('id_theme'));
        }
        if ($request->get('id_societe')) {
            $id_societe = intval($request->get('id_societe'));
        }
        $vocabulairePrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess')->getVocabulairePrototypeAccessByPrototypeAndSocieteAndTheme($id, $id_societe, $id_theme,'dic');
        $theme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Theme')->find($id_theme);
        $prototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->find($id);
        $libelle_theme = "";
        $nom_prototype = "";
         if($prototypeAccess != null )
        $nom_prototype = $prototypeAccess->getType();
        $url = $this->generateUrl(
            'modif_contenu_le',
            array(),
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        if($theme != null && $prototypeAccess != "")
        {
            $libelle_theme = $theme->getLibelleTheme();
            $nom_prototype = $prototypeAccess->getType();
        }

        foreach ($vocabulairePrototypeAccess  as $vpa)
        {

        }

        $url_add_new_termes = $this->generateUrl(
            'add_new_termes',
            array(),
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $this->render('FormationVocabulaireBundle:Prototype:modifLe.html.twig', array(
            'id' => $id,
            'id_theme' => $id_theme,
            'id_societe' => $id_societe,
            'libelle_theme' => $libelle_theme,
            'nom_prototype' => $nom_prototype,
            'url' => $url,
            'url_add_new_termes' => $url_add_new_termes
        ));

    }


    /**
     * @Route("/suppr_vocab_le/{id_vocabulaire}/{id}/{id_societe}/{id_theme}", name="suppr_vocab_le")
     */
    public function supprVocabLEAction($id_vocabulaire, $id,$id_societe, $id_theme)
    {
        $em = $this->getDoctrine()->getManager();
         $date_today = date("Y-m-d");

        $sql_delete="DELETE FROM vocabulaire_prototype_access WHERE id_prototype_access = '$id' AND id_vocabulaire = '$id_vocabulaire'";
        $repositoryVocabulairePrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess');
        $datas = $repositoryVocabulairePrototypeAccess->getVocabulaireProtoByProtoAccessAndVocabulaire3($id, $id_vocabulaire);
        foreach($datas as $row)
            $em->remove($row);

        return $this->redirectToRoute('voir_le', array('id'=>$id,'dic'=> 'fr1','id_prototype_access' => $id, 'id_societe'=> $id_societe, 'id_theme'=> $id_theme));

    }
}
