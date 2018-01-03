<?php

namespace Formation\VocabulaireBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
        $url = $this->generateUrl(
            'modif_contenu_le',
            array(),
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        return $this->render('FormationVocabulaireBundle:Prototype:modif_prototype_le_soc.html.twig', array(
            'id' => $id,
            'id_societe' => $id_societe,
            'compteur' => $compteur,
            'lexiques' => $lexiques_array,
            'url' => $url
        ));

    }

    /**
     * @Route("/modif_contenu_le", name="modif_contenu_le")
     */
    public function modifContenuLeAction(Request $request) {
        $id_theme = 0;
        $id_societe = 0;
        if ($request->get('id_theme')) {
            $id_theme = $request->get('id_theme');
        }
        if ($request->get('id_societe')) {
            $id_societe = $request->get('id_societe');
        }

        return $this->render('FormationVocabulaireBundle:Prototype:modif_contenu_le.html.twig', array(
           'id_theme'
        ));
    }
}
