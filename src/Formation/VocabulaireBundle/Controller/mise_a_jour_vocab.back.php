      /**
     * @Route("/mise_a_jour_vocab_action1", name="mise_a_jour_vocab_action1")
     */
    public function mise_a_jour_vocabAction1(Request $request)
    {
        ini_set('max_execution_time', -1); //0=NOLIMIT
        ini_set('memory_limit', '2048M');

        $id_societe = intval($request->get('societe'));
        $id_language = intval($request->get('langue'));
        $id_traducteur = intval($request->get('id_traducteur'));

        $date = date("Y-m-d H:i:s");
        $date_today = date("Y-m-d");
        $colonneDroite = $request->get('colonneDroite');
        $colonneGauche = $request->get('colonneGauche');

        $colonneDroiteValue = explode(",", $colonneDroite);
        $colonneGaucheValue = explode(",", $colonneGauche);
        $index_theme = 0;
        $index_contexte_usage = 0;
        $index_langue_origine1 = 0;
        $index_langue_traduction1 = 0;
        $index_source_type = 0;
        $index_nom_stagiaire = 0;
        $index_nom_doc = 0;
        $index_lien = 0;
        $index_rang = 0;
        $index_secteur = 0;
        $index_departement = 0;
        $index_theme_eng = 0;
        $index_phrase_source = 0;
        $index_fonction = 0;
        $index_prototype = 0;
        $index_suffixe = 0;
        $index_millesime = 0;

        for ($index = 1; $index < count($colonneDroiteValue); $index++) {
            if ($colonneDroiteValue[$index] == 4) {
                $index_theme = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 5) {
                $index_contexte_usage = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 6) {
                $index_langue_origine1 = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 7) {
                $index_langue_traduction1 = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 8) {
                $index_source_type = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 9) {
                $index_nom_stagiaire = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 10) {
                $index_nom_doc = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 11) {
                $index_lien = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 12) {
                $index_rang = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 13) {
                $index_secteur = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 14) {
                $index_departement = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 15) {
                $index_theme_eng = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 16) {
                $index_phrase_source = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 17) {
                $index_fonction = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 18) {
                $index_prototype = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 19) {
                $index_suffixe = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 20) {
                $index_millesime = $colonneGaucheValue[$index];
            }
        }

        $fileType = pathinfo($_FILES['userfile2']['name'], PATHINFO_EXTENSION);
        //$uploadDir = 'C:/wamp/www' . $request->getBasePath() . '/uploads/'; # serveur dev
        $uploadDir = '/var/www/html' . $request->getBasePath() . '/uploads/'; # serveur prod
        $file = $request->files->get('userfile2');
        $fileName = $file->getClientOriginalName();
        $file->move($uploadDir, $fileName);

        if ($fileType == "xls" || $fileType == "xlsx" || $fileType == "ods") {

            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($uploadDir . $fileName);
            foreach ($phpExcelObject->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);

                for ($row = 2; $row <= $highestRow; ++$row) {
                    $tab = array();
                    for ($col = 0; $col < $highestColumnIndex; ++$col) {
                        $cell = $worksheet->getCellByColumnAndRow($col, $row);
                        $val = $cell->getValue();
                        $tab[$col] = $val;
                    }
                    $theme = "";
                    $environnement_usage = "";
                    $langue_origine1 = "";
                    $langue_traduction1 = "";
                    $source_type = "";
                    $nom_stagiaire = "";
                    $nom_doc = "";
                    $rang = "";
                    $lien = "";
                    $secteur = "";
                    $departement = "";
                    $prototype = "";
                    $theme_eng = "";
                    $fonction = "";
                    $suffixe = "";
                    $millesime = "";

                    if ($index_theme != 0) {
                        $theme = $this->quote($tab[$index_theme - 1]);
                        $theme = trim($theme);
                       
                    }
                    if ($index_contexte_usage != 0) {
                        $environnement_usage = $this->quote($tab[$index_contexte_usage - 1]);
                        $environnement_usage = trim($environnement_usage);
                    }
                    if ($index_langue_origine1 != 0) {
                        $langue_origine1 = $this->quote($tab[$index_langue_origine1 - 1]);
                        $langue_origine1 = trim($langue_origine1);
                    }
                    if ($index_langue_traduction1 != 0) {
                        $langue_traduction1 = $this->quote($tab[$index_langue_traduction1 - 1]);
                        $langue_traduction1 = trim($langue_traduction1);
                    }
                    if ($index_source_type != 0) {
                        $source_type = $this->quote($tab[$index_source_type - 1]);
                        $source_type = trim($source_type);
                    }
                    if ($index_nom_stagiaire != 0) {
                        $nom_stagiaire = $this->quote($tab[$index_nom_stagiaire - 1]);
                        $nom_stagiaire = trim($nom_stagiaire);
                    }
                    if ($index_nom_doc != 0) {
                        $nom_doc = $this->quote($tab[$index_nom_doc - 1]);
                        $nom_doc = trim($nom_doc);
                    }
                    if ($index_rang != 0) {
                        $rang = $this->quote($tab[$index_rang - 1]);
                        $rang = trim($rang);
                    }
                    if ($index_lien != 0) {
                        $lien = $this->quote($tab[$index_lien - 1]);
                        $lien = trim($lien);
                    }
                    if ($index_secteur != 0) {
                        $secteur = $this->quote($tab[$index_secteur - 1]);
                        $secteur = trim($secteur);
                    }
                    if ($index_departement != 0) {
                        $departement = $this->quote($tab[$index_departement - 1]);
                        $departement = trim($departement);
                    }
                    if ($index_theme_eng != 0) {
                        $theme_eng = $this->quote($tab[$index_theme_eng - 1]);
                        $theme_eng = trim($theme_eng);
                    }
                    if ($index_fonction != 0) {
                        $fonction = $this->quote($tab[$index_fonction - 1]);
                        $fonction = trim($fonction);
                    }
                    if ($index_prototype != 0) {
                        $prototype = $this->quote($tab[$index_prototype - 1]);
                        $prototype = trim($prototype);
                    }
                    if ($index_phrase_source != 0) {
                        $phrase_source = $this->quote($tab[$index_phrase_source - 1]);
                        $phrase_source = trim($phrase_source);
                    }
                    if ($index_suffixe != 0) {
                        $suffixe = $this->quote($tab[$index_suffixe - 1]);
                        $suffixe = trim($suffixe);
                    }
                    if ($index_millesime != 0) {
                        $millesime = $this->quote($tab[$index_millesime - 1]);
                        $millesime = trim($millesime);
                    }
                    $id_vocabulaire = 0;
                    $id_source = 0;
                    $em = $this->getDoctrine()->getManager();
                    if ($langue_origine1 != "" && $langue_traduction1 != "") {

                        if ($source_type != "" || $nom_stagiaire != "" || $nom_doc != "" || $lien != "") {
                            //insertion source
                            $source = new Source();
                            $source->setSourceType($source_type);
                            $source->setSourceNomStagiaire($nom_stagiaire);
                            $source->setLienNomDoc($nom_doc);
                            $source->setLien($lien);

                            $em->persist($source);
                            $em->flush();

                            $id_source = $source->getId();

                        }

                        //verification existence vocabulaire
                        $langue_origine_avec_maj = $this->trim_ucfirst_strtolower_utf8($langue_origine1);

                        $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                        $vocabulaire = $repositoryVocabulaire->findOneBy(array('langueOrigine' => $langue_origine_avec_maj, 'langueTraduction' => $langue_traduction1, 'language' => $id_language));

                        if ($vocabulaire != null) {

                            $vocabulaire->setDateModification(new \DateTime($date));
                            $em->merge($vocabulaire);
                            $em->flush();
                            $id_vocabulaire = $vocabulaire->getId();


                        } else {
                            $langue_origine_sans_modif = "";
                            $alphabet_min = range('a', 'z');
                            $alphabet_maj = range('A', 'Z');
                            $first_lettre = $langue_origine1[0];
                            if (!in_array($first_lettre, $alphabet_min) && !in_array($first_lettre, $alphabet_maj)) {
                                $langue_origine_sans_modif = $langue_origine1;
                            }
                            $nb_caract = strlen($langue_origine1);
                            $fixeNbreCaractreLigne = 47;

                            $langue_origine_clean = $this->cleanLangueOrigine($langue_origine1);
                            $nb_ligne = ($nb_caract / $fixeNbreCaractreLigne);
                            $partieEntiere = (int)($nb_caract / $fixeNbreCaractreLigne);
                            if ($partieEntiere < $nb_ligne) {
                                $partieEntiere = $partieEntiere + 1;
                            }

                            //insertion vocabulaire
                            $repositoryLanguage = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Language');
                            $language = $repositoryLanguage->find($id_language);

                            $repositorySource = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Source');
                            $source = $repositorySource->find($id_source);

                            if ($source != null && $language != null) {

                                $vocab = new Vocabulaire();
                                $vocab->setDateCreation(new \DateTime($date));
                                $vocab->setLangueOrigine($langue_origine_avec_maj);
                                $vocab->setLangueOrigineSansModif($langue_origine_sans_modif);
                                $vocab->setLangueTraduction($langue_traduction1);
                                $vocab->setRang($rang);
                                $vocab->setIsAffiche(1);
                                $vocab->setNbreCaractLo($nb_caract);
                                $vocab->setLanguage($language);
                                $vocab->setSource($source);
                                $vocab->setNbreLigneLo(0);
                                $em->persist($vocab);
                                $em->flush();
                                $id_vocabulaire = $vocab->getId();
                            }


                        }

                        if ($secteur != "") {
                            //verif secteur d'activité
                            $repositorySecteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Secteur');
                            $sect = $repositorySecteur->findOneBy(array('libelleSecteur' => $secteur));

                            if ($sect != null) {
                                $id_secteur = $sect->getId();
                            } else {
                                $sect = new Secteur();
                                $sect->setLibelleSecteur($secteur);
                                $em->persist($sect);
                                $em->flush();
                                $id_secteur = $sect->getId();

                            }

                            $repositoryVocabulaireSecteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireSecteur');
                            $vocabulaireSecteur = $repositoryVocabulaireSecteur->findOneBy(array('secteur' => $id_secteur, 'vocabulaire' => $id_vocabulaire));
                            if ($vocabulaireSecteur == null) {

                                $vocabulaireSecteur = new VocabulaireSecteur();
                                $repositorySecteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Secteur');
                                $sec = $repositorySecteur->find($id_secteur);
                                $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                                $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);
                                if ($vocabulaire != null && $sec != null) {
                                    $vocabulaireSecteur->setSecteur($sec);
                                    $vocabulaireSecteur->setVocabulaire($vocabulaire);
                                    $em->merge($vocabulaireSecteur);
                                    $em->flush();
                                }

                            }
                        }

                        if ($departement != "") {
                            //verif departement
                            $repositoryDepartement = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Departement');
                            $depart = $repositoryDepartement->findOneBy(array('libelleDepartement' => $departement));
                            $id_departement = 0;
                            if ($depart != null) {
                                $id_departement = $depart->getId();
                            } else {
                                $depart = new Departement();
                                $depart->setLibelleDepartement($departement);
                                $em->persist($depart);
                                $em->flush();
                                $id_departement = $depart->getId();
                            }
                            $repositoryVocabulaireDepartement = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireDepartement');
                            $vocabulaireDepartement = $repositoryVocabulaireDepartement->findOneBy(array('departement' => $id_departement, 'vocabulaire' => $id_vocabulaire));
                            if ($vocabulaireDepartement == null) {

                                $vocabulaireDepartement = new VocabulaireDepartement();
                                $repositoryDepartement = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Departement');
                                $depart = $repositoryDepartement->find($id_departement);

                                $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                                $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);
                                if ($vocabulaire != null && $depart != null) {
                                    $vocabulaireDepartement->setDepartement($depart);
                                    $vocabulaireDepartement->setVocabulaire($vocabulaire);
                                    $em->persist($vocabulaireDepartement);
                                    $em->flush();
                                }


                            }

                        }

                        $id_theme = 0;

                        if ($theme != "") {
                            //verif theme
                            $repositoryTheme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Theme');
                            $them = $repositoryTheme->findOneBy(array('libelleTheme' => $theme));
                            $id_theme = 0;
                            if ($them != null) {
                                $id_theme = $them->getId();
                            } else {
                                $them = new Theme();
                                $them->setLibelleTheme($theme);
                                $em->persist($them);
                                $em->flush();
                                $id_theme = $them->getId();
                            }
                            $repositoryVocabulaireTheme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireTheme');
                            $vocabulaireTheme = $repositoryVocabulaireTheme->findOneBy(array('theme' => $id_theme, 'vocabulaire' => $id_vocabulaire));
                            if ($vocabulaireTheme == null) {

                                $vocabulaireTheme = new VocabulaireTheme();
                                $repositoryTheme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Theme');
                                $them = $repositoryTheme->find($id_theme);

                                $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                                $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);

                                if ($vocabulaire != null && $them != null) {
                                    $vocabulaireTheme->setTheme($them);
                                    $vocabulaireTheme->setVocabulaire($vocabulaire);
                                    $em->persist($vocabulaireTheme);
                                    $em->flush();
                                }


                            }

                        }
                        if ($fonction != "") {
                            //verif fonction
                            $repositoryFonction = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Fonction');
                            $depart = $repositoryFonction->findOneBy(array('libelleFonction' => $fonction));
                            $id_fonction = 0;
                            if ($depart != null) {
                                $id_fonction = $depart->getId();
                            } else {
                                $fonct = new Fonction();
                                $fonct->setLibelleFonction($fonction);
                                $em->persist($fonct);
                                $em->flush();
                                $id_fonction = $fonct->getId();
                            }
                            $repositoryVocabulaireFonction = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireFonction');
                            $vocabulaireFonction = $repositoryVocabulaireFonction->findOneBy(array('fonction' => $id_fonction, 'vocabulaire' => $id_vocabulaire));
                            if ($vocabulaireFonction == null) {

                                $vocabulaireFonction = new VocabulaireFonction();
                                $repositoryFonction = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Fonction');
                                $fonct = $repositoryFonction->find($id_fonction);

                                $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                                $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);
                                if ($vocabulaire != null && $fonct != null) {
                                    $vocabulaireFonction->setFonction($fonct);
                                    $vocabulaireFonction->setVocabulaire($vocabulaire);
                                    $em->persist($vocabulaireFonction);
                                    $em->flush();
                                }


                            }

                        }
                        if ($environnement_usage != "") {
                            //verif environnement_usage
                            $repositoryEnvironnementUsage = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:EnvironnementUsage');
                            $depart = $repositoryEnvironnementUsage->findOneBy(array('libelleEnvironnementUsage' => $environnement_usage));
                            $id_environnement_usage = 0;
                            if ($depart != null) {
                                $id_environnement_usage = $depart->getId();
                            } else {
                                $envir_usage = new EnvironnementUsage();
                                $envir_usage->setLibelleEnvironnementUsage($environnement_usage);
                                $em->persist($envir_usage);
                                $em->flush();
                                $id_environnement_usage = $envir_usage->getId();
                            }
                            $repositoryVocabulaireEnvironnementUsage = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireEnvirUsage');
                            $vocabulaireEnvironnementUsage = $repositoryVocabulaireEnvironnementUsage->findOneBy(array('environnement_usage' => $id_environnement_usage, 'vocabulaire' => $id_vocabulaire));
                            if ($vocabulaireEnvironnementUsage == null) {

                                $vocabulaireEnvironnementUsage = new VocabulaireEnvirUsage();
                                $repositoryEnvironnementUsage = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:EnvironnementUsage');
                                $envir_usage = $repositoryEnvironnementUsage->find($id_environnement_usage);

                                $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                                $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);
                                if ($vocabulaire != null && $envir_usage != null) {
                                    $vocabulaireEnvironnementUsage->setEnvironnementUsage($envir_usage);
                                    $vocabulaireEnvironnementUsage->setVocabulaire($vocabulaire);
                                    $em->persist($vocabulaireEnvironnementUsage);
                                    $em->flush();
                                }


                            }

                        }

                        if ($id_traducteur != "" && $id_traducteur != 0) {
                            $vocabulaireTraducteur = new VocabulaireTraducteur();
                            $repositoryTraducteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Traducteur');
                            $trad = $repositoryTraducteur->find($id_traducteur);

                            $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                            $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);

                            if ($vocabulaire != null && $trad != null) {
                                $vocabulaireTraducteur->setTraducteur($trad);
                                $vocabulaireTraducteur->setVocabulaire($vocabulaire);
                                $em->persist($vocabulaireTraducteur);
                                $em->flush();
                            }
                        }

                        if ($prototype != "") {
                            //verif prototype
                            $repositoryPrototype = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Prototype');
                            $proto = $repositoryPrototype->findOneBy(array('libellePrototype' => $prototype));
                            $id_prototype = 0;
                            if ($proto != null) {
                                $id_prototype = $proto->getId();
                            } else {
                                $proto = new Prototype();
                                $proto->setLibellePrototype($prototype);
                                $em->persist($proto);
                                $em->flush();
                                $id_prototype = $proto->getId();

                                $date_today = date("Y-m-d");
                            }


                            $repositoryPrototype = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Prototype');
                            $proto_obj = $repositoryPrototype->find($id_prototype);

                            $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                            $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);

                            if ($vocabulaire != null && $proto_obj != null) {
                                $vocabulairePrototype = new VocabulairePrototype();
                                $vocabulairePrototype->setPrototype($proto_obj);
                                $vocabulairePrototype->setVocabulaire($vocabulaire);
                                $em->persist($vocabulairePrototype);
                                $em->flush();

                            }
                            $repositoryPrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess');
                            $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
                            $societe = $repositorySociete->find($id_societe);

                            $repositoryFormatEdition = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:FormatEdition');
                            $formatEdition = $repositoryFormatEdition->find(3);
                            $repositoryTraducteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Traducteur');
                            $trad = $repositoryTraducteur->find($id_traducteur);
                            $proto_access = $repositoryPrototypeAccess->findOneBy(array('type' => $prototype, 'societe' => $societe));

                            
                            $id_prot = 0;
                            if ($proto_access != null) {
                                $id_prot = $proto_access->getId();
                            } else if ($societe != null && $formatEdition != null && $trad != null) {

                                $date_today = date("Y-m-d H:i:s");
                                $proto_access = new PrototypeAccess();
                                $proto_access->setNumero(0);
                                $proto_access->setType($prototype);
                                $proto_access->setDate(new \DateTime($date_today));
                                $proto_access->setNbPage(0);
                                $proto_access->setStatut('');
                                $proto_access->setTraducteur($trad);
                                $proto_access->setMisAJour('');
                                $proto_access->setPhase('');
                                $proto_access->setPhaseEtat('');
                                $proto_access->setPrioriteType('');
                                $proto_access->setFormatEdition($formatEdition);
                                $proto_access->setSociete($societe);

                                $em->persist($proto_access);
                                $em->flush();
                                $id_prot = $proto_access->getId();

                            }

                            ////ajout dans la table lexique pour gerer les rangs des LE
                           if ($id_societe != 653) {
                                 $rang_lexique = $this->recupRangLE($id_societe, $id_theme, $id_prot);
                                 $rangLE = $this->getMaxRangLE($id_prot);
                                 $rangLE = $rangLE + 1;
                                 if ($rang_lexique == 0 || $rang_lexique == "") {

                                     $repositoryPrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess');
                                     $prototypeAccess = $repositoryPrototypeAccess->find($id_prot);

                                     $repositoryTheme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Theme');
                                     $theme = $repositoryTheme->find($id_theme);

                                     $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
                                     $societe = $repositorySociete->find($id_societe);

                                     if ($prototypeAccess != null && $theme != null && $societe != null) {
                                         $lexique = new Lexique();
                                         $lexique->setRang($rangLE);
                                         $lexique->setSociete($societe);
                                         $lexique->setPrototypeAccess($prototypeAccess);
                                         $lexique->setTheme($theme);
                                         $em->persist($lexique);
                                         $em->flush();
                                     }
                                 }
                             }

                            $prototype_access =  $repositoryPrototypeAccess->find($id_prot);
                            $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                            $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);

                            if($prototype_access  != null && $vocabulaire != null)
                            {
                                $repositoryVocabulairePrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess');
                                $vocabulairePrototypeAccess = $repositoryVocabulairePrototypeAccess->findOneBy(array('prototypeAccess' => $prototype_access,'vocabulaire' => $vocabulaire));
                                if($vocabulairePrototypeAccess == null && $vocabulaire != null && $prototype_access != null){
                                    $vocabulairePrototypeAccess = new VocabulairePrototypeAccess();
                                    $vocabulairePrototypeAccess->setVocabulaire($vocabulaire);
                                    $vocabulairePrototypeAccess->setPrototypeAccess($prototype_access);
                                    $em->persist($vocabulairePrototypeAccess);
                                    $em->flush();
                                }
                            }
                        }

                        $vocabulaireSociete = new VocabulaireSociete();
                        $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
                        $societe = $repositorySociete->find($id_societe);

                        $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                        $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);

                        if ($vocabulaire != null && $societe != null) {
                            $vocabulaireSociete->setSociete($societe);
                            $vocabulaireSociete->setVocabulaire($vocabulaire);
                            $em->persist($vocabulaireSociete);
                            $em->flush();
                        }

                        if ($suffixe != "") {
                            //verif suffixe
                            //$suffixe_verif = strtolower($suffixe);
                            $repositorySuffixe = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Suffixe');
                            $suffixe_obj = $repositorySuffixe->findOneBy(array('libelleSuffixe' => $suffixe, 'millesime' => $millesime));

                            if ($suffixe_obj != null) {
                                $id_suffixe = $suffixe_obj->getId();
                            } else {
                                $suffixe_obj = new Suffixe();
                                $suffixe_obj->setLibelleSuffixe($suffixe);
                                $suffixe_obj->setMillesime($millesime);
                                $em->persist($suffixe_obj);
                                $em->flush();
                                 $id_suffixe = $suffixe_obj->getId();
                            }
                            $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
                            $societe = $repositorySociete->find($id_societe);
                            $suffixe_obj = $repositorySuffixe->find($id_suffixe);
                            $repositorySuffixeSociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:SuffixeSociete');
                            if ($suffixe_obj != null && $societe) {
                                $suffixeSociete = $repositorySuffixeSociete->findOneBy(array('suffixe' => $suffixe_obj, 'societe' => $societe));
                                if ($suffixeSociete != null) {
                                    $suffixeSociete = new SuffixeSociete();
                                    $suffixeSociete->setSociete($societe);
                                    $suffixeSociete->setSuffixe($suffixe_obj);
                                }
                            }


                        }

                        //phrase source non vide
                        if ($index_phrase_source != null && $index_phrase_source != 0) {
                            $phrase_source = $this->quote($tab[$index_phrase_source]);
                            //verif phrase source
                            $repositoryPhraseSource = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PhraseSource');
                            $phrase_source_obj = $repositoryPhraseSource->findOneBy(array('libellePhraseSource' => $phrase_source));

                            if ($phrase_source_obj != 0) {
                                $id_phrase_source = $phrase_source_obj->getId();
                            } else {
                                $phrase_source_obj = new PhraseSource();
                                $phrase_source_obj->setLibellePhraseSource($phrase_source);
                                $em->persist($phrase_source_obj);
                                $em->flush();
                            }
                            $phrase_source_obj = $repositoryPhraseSource->find($id_phrase_source);
                            $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                            $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);
                            if ($phrase_source_obj != null && $vocabulaire != null) {
                                $repositoryVocabulairePhraseSource = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePhraseSource');
                                $vocabulairePhraseSource = $repositoryVocabulairePhraseSource->findOneBy(array('phrase_source' => $phrase_source_obj, 'vocabulaire' => $vocabulaire));
                                if ($vocabulairePhraseSource != null) {
                                    $vocabulairePhraseSource = new VocabulairePhraseSource();
                                    $vocabulairePhraseSource->setVocabulaire($vocabulaire);
                                    $vocabulairePhraseSource->setPhraseSource($phrase_source_obj);
                                    $em->persist($vocabulairePhraseSource);
                                    $em->flush();
                                }
                            }


                        }
                    }
                }

                if ($id_societe == 653) {
                    return $this->redirect($this->generateUrl('consulter_prototype'));
                } else {

                    return $this->redirectToRoute('modif_prototype', array('id' => $id_prot));
                }

            }
        } else {
            echo "<SCRIPT language=javascript> alert('Format de fichier non reconnu'); window.location = 'mise_a_jour_vocab';</SCRIPT>";
        }
        return $this->render('FormationVocabulaireBundle:Default:uploadExcel.html.twig', array());
		
    }