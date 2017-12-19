<?php

namespace Formation\VocabulaireBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PrototypeSocieteController extends Controller
{
     /**
     * @Route("/modif_prototype_le_soc/{id}/{id_societe}", name="modif_prototype_le_soc")
     */
    public function modifPrototypeLESocAction($id, $id_societe)
    {
        $repositoryLexique = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Lexique');
        $lexiques = $repositoryLexique->recherchePrototypeBySoc($id_societe);
        $compteur = count($lexiques);
     
        $lexiques_array = array();
        
        foreach ($lexiques as $l) {
            $le = new \Formation\VocabulaireBundle\Model\Lexique();
            $le->setIdLexique($repositoryLexique->getLexiqueBySocieteAndThemeAndPrototypeAccess($l['id_societe'], $l['id_theme'], $id));
            $le->setIdSociete($l['id_societe']);
            $le->setId_theme($l['id_theme']);
            $le->setLibelle_theme($l['libelle_theme']);
             $le->setTheme_eng($l['theme_eng']);
            $lexiques_array[] = $le;
        }
        
        return $this->render('FormationVocabulaireBundle:Prototype:modif_prototype_le_soc.html.twig', array(
            'id' => $id,
            'id_societe' => $id_societe,
            'compteur' => $compteur,
            'lexiques' => $lexiques_array
        ));

    }
}
