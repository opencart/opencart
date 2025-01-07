-----------------------------------------------------------

--
-- Database: `opencart`
--

-----------------------------------------------------------

SET
sql_mode = '';

-----------------------------------------------------------

--
-- Dumping data for table `oc_attribute_description`
--

INSERT INTO `oc_attribute_description` (`attribute_id`, `language_id`, `name`)
VALUES (1, 1, 'Description'),
       (2, 1, 'Nombre de cœurs'),
       (3, 1, 'Fréquence d’horloge'),
       (4, 1, 'test 1'),
       (5, 1, 'test 2'),
       (6, 1, 'test 3'),
       (7, 1, 'test 4'),
       (8, 1, 'test 5'),
       (9, 1, 'test 6'),
       (10, 1, 'test 7'),
       (11, 1, 'test 8');

-----------------------------------------------------------

--
-- Dumping data for table `oc_attribute_group_description`
--

INSERT INTO `oc_attribute_group_description` (`attribute_group_id`, `language_id`, `name`)
VALUES (3, 1, 'Mémoire'),
       (4, 1, 'Technique'),
       (5, 1, 'Carte mère'),
       (6, 1, 'Processeur');

-----------------------------------------------------------

--
-- Dumping data for table `oc_banner_image`
--

INSERT INTO `oc_banner_image` (`banner_image_id`, `banner_id`, `language_id`, `title`, `link`, `image`, `sort_order`)
VALUES (99, 7, 1, 'iPhone 6', 'index.php?route=product/product&amp;path=57&amp;product_id=49', 'catalog/demo/banners/iPhone6.jpg', 0),
       (100, 6, 1, 'HP Banner', 'index.php?route=product/manufacturer.info&amp;manufacturer_id=7', 'catalog/demo/compaq_presario.jpg', 0),
       (101, 8, 1, 'NFL', '', 'catalog/demo/manufacturer/nfl.png', 0),
       (102, 8, 1, 'RedBull', '', 'catalog/demo/manufacturer/redbull.png', 0),
       (103, 8, 1, 'Sony', '', 'catalog/demo/manufacturer/sony.png', 0),
       (104, 8, 1, 'Coca Cola', '', 'catalog/demo/manufacturer/cocacola.png', 0),
       (105, 8, 1, 'Burger King', '', 'catalog/demo/manufacturer/burgerking.png', 0),
       (106, 8, 1, 'Canon', '', 'catalog/demo/manufacturer/canon.png', 0),
       (107, 8, 1, 'Harley Davidson', '', 'catalog/demo/manufacturer/harley.png', 0),
       (108, 8, 1, 'Dell', '', 'catalog/demo/manufacturer/dell.png', 0),
       (109, 8, 1, 'Disney', '', 'catalog/demo/manufacturer/disney.png', 0),
       (110, 7, 1, 'MacBookAir', '', 'catalog/demo/banners/MacBookAir.jpg', 0),
       (111, 8, 1, 'Starbucks', '', 'catalog/demo/manufacturer/starbucks.png', 0),
       (112, 8, 1, 'Nintendo', '', 'catalog/demo/manufacturer/nintendo.png', 0);

-----------------------------------------------------------

--
-- Dumping data for table `oc_category_description`
--

INSERT INTO `oc_category_description` (`category_id`, `language_id`, `name`, `description`, `meta_title`, `meta_description`, `meta_keyword`)
VALUES (28, 1, 'Moniteurs', '', 'Moniteurs', '', ''),
       (33, 1, 'Caméras', '', 'Caméras', '', ''),
       (32, 1, 'Webcams', '', 'Webcams', '', ''),
       (31, 1, 'Scanners', '', 'Scanners', '', ''),
       (30, 1, 'Imprimantes', '', 'Imprimantes', '', ''),
       (29, 1, 'Souris et Boules de Suivi', '', 'Souris et Boules de Suivi', '', ''),
       (27, 1, 'Mac', '', 'Mac', '', ''),
       (26, 1, 'PC', '', 'PC', '', ''),
       (17, 1, 'Logiciels', '', 'Logiciels', '', ''),
       (25, 1, 'Composants', '', 'Composants', '', ''),
       (24, 1, 'Téléphones &amp; PDA', '', 'Téléphones &amp; PDA', '', ''),
       (20, 1, 'Bureaux', '&lt;p&gt;\r\n	Exemple de texte de description de catégorie&lt;/p&gt;\r\n', 'Bureaux', 'Exemple de description de catégorie', ''),
       (35, 1, 'test 1', '', 'test 1', '', ''),
       (36, 1, 'test 2', '', 'test 2', '', ''),
       (37, 1, 'test 5', '', 'test 5', '', ''),
       (38, 1, 'test 4', '', 'test 4', '', ''),
       (39, 1, 'test 6', '', 'test 6', '', ''),
       (40, 1, 'test 7', '', 'test 7', '', ''),
       (41, 1, 'test 8', '', 'test 8', '', ''),
       (42, 1, 'test 9', '', 'test 9', '', ''),
       (43, 1, 'test 11', '', 'test 11', '', ''),
       (34, 1, 'Lecteurs MP3', '&lt;p&gt;\r\n	Shop Laptop propose uniquement les meilleures offres d''ordinateurs portables sur le marché. En comparant les offres de PC World, Comet, Dixons, The Link et Carphone Warehouse, Shop Laptop offre la sélection la plus complète d''ordinateurs portables sur Internet. Chez Shop Laptop, nous nous efforçons d''offrir à nos clients les meilleures offres possibles. Des ordinateurs portables reconditionnés aux netbooks, Shop Laptop garantit que chaque ordinateur - dans toutes les couleurs, styles, tailles et spécifications techniques - figure sur le site au prix le plus bas possible.&lt;/p&gt;\r\n', 'Lecteurs MP3', '', ''),
       (18, 1, 'Ordinateurs Portables &amp; Notebooks', '&lt;p&gt;\r\n	Shop Laptop propose uniquement les meilleures offres d''ordinateurs portables sur le marché. En comparant les offres de PC World, Comet, Dixons, The Link et Carphone Warehouse, Shop Laptop offre la sélection la plus complète d''ordinateurs portables sur Internet. Chez Shop Laptop, nous nous efforçons d''offrir à nos clients les meilleures offres possibles. Des ordinateurs portables reconditionnés aux netbooks, Shop Laptop garantit que chaque ordinateur - dans toutes les couleurs, styles, tailles et spécifications techniques - figure sur le site au prix le plus bas possible.&lt;/p&gt;\r\n', 'Ordinateurs Portables &amp; Notebooks', '', ''),
       (44, 1, 'test 12', '', 'test 12', '', ''),
       (45, 1, 'Windows', '', 'Windows', '', ''),
       (46, 1, 'Macs', '', 'Macs', '', ''),
       (47, 1, 'test 15', '', 'test 15', '', ''),
       (48, 1, 'test 16', '', 'test 16', '', ''),
       (49, 1, 'test 17', '', 'test 17', '', ''),
       (50, 1, 'test 18', '', 'test 18', '', ''),
       (51, 1, 'test 19', '', 'test 19', '', ''),
       (52, 1, 'test 20', '', 'test 20', '', ''),
       (53, 1, 'test 21', '', 'test 21', '', ''),
       (54, 1, 'test 22', '', 'test 22', '', ''),
       (55, 1, 'test 23', '', 'test 23', '', ''),
       (56, 1, 'test 24', '', 'test 24', '', ''),
       (57, 1, 'Tablettes', '', 'Tablettes', '', ''),
       (58, 1, 'test 25', '', 'test 25', '', '');

-----------------------------------------------------------

--
-- Dumping data for table `oc_customer_group_description`
--

INSERT INTO `oc_customer_group_description` (`customer_group_id`, `language_id`, `name`, `description`)
VALUES (1, 1, 'Par Défaut', 'Groupe de clients par défaut'),
       (2, 1, 'Détail', 'Clients au détail'),
       (3, 1, 'Grossiste', 'Clients grossistes');

-----------------------------------------------------------

--
-- Dumping data for table `oc_custom_field_description`
--

INSERT INTO `oc_custom_field_description` (`custom_field_id`, `language_id`, `name`)
VALUES (1, 1, 'Sélectionner'),
       (2, 1, 'Bouton Radio'),
       (3, 1, 'Case à Cocher'),
       (4, 1, 'Texte'),
       (5, 1, 'Zone de Texte'),
       (6, 1, 'Fichier'),
       (7, 1, 'Date'),
       (8, 1, 'Heure'),
       (9, 1, 'Date &amp; Heure'),
       (11, 1, 'Case à Cocher'),
       (12, 1, 'Heure'),
       (13, 1, 'Date'),
       (14, 1, 'Date &amp; Heure'),
       (15, 1, 'Fichier'),
       (16, 1, 'Bouton Radio'),
       (17, 1, 'Sélectionner'),
       (18, 1, 'Texte'),
       (19, 1, 'Zone de Texte'),
       (20, 1, 'Case à Cocher'),
       (21, 1, 'Date'),
       (22, 1, 'Date &amp; Heure'),
       (23, 1, 'Fichier'),
       (24, 1, 'Bouton Radio'),
       (25, 1, 'Sélectionner'),
       (26, 1, 'Texte'),
       (27, 1, 'Zone de Texte'),
       (28, 1, 'Heure');

-----------------------------------------------------------

--
-- Dumping data for table `oc_custom_field_value_description`
--

INSERT INTO `oc_custom_field_value_description` (`custom_field_value_id`, `language_id`, `custom_field_id`, `name`)
VALUES (1, 1, 1, 'Test 1'),
       (2, 1, 1, 'test 2'),
       (3, 1, 1, 'Test 3'),
       (4, 1, 2, 'Test 1'),
       (5, 1, 2, 'Test 2'),
       (6, 1, 2, 'Test 3'),
       (7, 1, 3, 'Test 1'),
       (8, 1, 3, 'Test 2'),
       (9, 1, 3, 'Test 3'),
       (20, 1, 11, 'Test 1'),
       (21, 1, 11, 'Test 2'),
       (22, 1, 11, 'Test 3'),
       (32, 1, 16, 'Test 1'),
       (33, 1, 16, 'Test 2'),
       (34, 1, 16, 'Test 3'),
       (35, 1, 17, 'Test 1'),
       (36, 1, 17, 'Test 2'),
       (37, 1, 17, 'Test 3'),
       (38, 1, 20, 'Test 1'),
       (39, 1, 20, 'Test 2'),
       (40, 1, 20, 'Test 3'),
       (41, 1, 24, 'Test 1'),
       (42, 1, 24, 'Test 2'),
       (43, 1, 24, 'Test 3'),
       (44, 1, 25, 'Test 1'),
       (45, 1, 25, 'Test 2'),
       (46, 1, 25, 'Test 3');

-----------------------------------------------------------

--
-- Dumping data for table `oc_information_description`
--

INSERT INTO `oc_information_description` (`information_id`, `language_id`, `title`, `description`, `meta_title`, `meta_description`, `meta_keyword`)
VALUES (1, 1, 'About Us', '&lt;p&gt;\r\n	About Us&lt;/p&gt;\r\n', 'About Us', '', ''),
       (2, 1, 'Terms &amp; Conditions', '&lt;p&gt;\r\n	Terms &amp;amp; Conditions&lt;/p&gt;\r\n', 'Terms &amp; Conditions', '', ''),
       (3, 1, 'Privacy Policy', '&lt;p&gt;\r\n	Privacy Policy&lt;/p&gt;\r\n', 'Privacy Policy', '', ''),
       (4, 1, 'Delivery Information', '&lt;p&gt;\r\n	Delivery Information&lt;/p&gt;\r\n', 'Delivery Information', '', '');

-----------------------------------------------------------

--
-- Dumping data for table `oc_language`
--

INSERT INTO `oc_language` (`language_id`, `name`, `code`, `locale`, `sort_order`, `status`)
VALUES (2, 'French', 'fr-fr', 'fr-fr,fr', 2, 1);

-----------------------------------------------------------

--
-- Dumping data for table `oc_length_class_description`
--

INSERT INTO `oc_length_class_description` (`length_class_id`, `language_id`, `title`, `unit`)
VALUES (1, 1, 'Centimètre', 'cm'),
       (2, 1, 'Millimètre', 'mm'),
       (3, 1, 'Pouce', 'po');

-----------------------------------------------------------

--
-- Dumping data for table `oc_option_description`
--

INSERT INTO `oc_option_description` (`option_id`, `language_id`, `name`)
VALUES (1, 1, 'Bouton Radio'),
       (2, 1, 'Case à Cocher'),
       (4, 1, 'Texte'),
       (6, 1, 'Zone de Texte'),
       (8, 1, 'Date'),
       (7, 1, 'Fichier'),
       (5, 1, 'Sélectionner'),
       (9, 1, 'Heure'),
       (10, 1, 'Date &amp; Heure'),
       (12, 1, 'Date de Livraison'),
       (11, 1, 'Taille');

-----------------------------------------------------------

--
-- Dumping data for table `oc_option_value_description`
--

INSERT INTO `oc_option_value_description` (`option_value_id`, `language_id`, `option_id`, `name`)
VALUES (43, 1, 1, 'Grand'),
       (32, 1, 1, 'Petit'),
       (45, 1, 2, 'Case à Cocher 4'),
       (44, 1, 2, 'Case à Cocher 3'),
       (31, 1, 1, 'Moyen'),
       (42, 1, 5, 'Jaune'),
       (41, 1, 5, 'Vert'),
       (39, 1, 5, 'Rouge'),
       (40, 1, 5, 'Bleu'),
       (23, 1, 2, 'Case à Cocher 1'),
       (24, 1, 2, 'Case à Cocher 2'),
       (48, 1, 11, 'Grand'),
       (47, 1, 11, 'Moyen'),
       (46, 1, 11, 'Petit');

-----------------------------------------------------------

--
-- Dumping data for table `oc_order_status`
--

INSERT INTO `oc_order_status` (`order_status_id`, `language_id`, `name`)
VALUES (2, 1, 'En Cours de Traitement'),
       (3, 1, 'Expédié'),
       (7, 1, 'Annulé'),
       (5, 1, 'Terminé'),
       (8, 1, 'Refusé'),
       (9, 1, 'Annulation de Remboursement'),
       (10, 1, 'Échoué'),
       (11, 1, 'Remboursé'),
       (12, 1, 'Reversé'),
       (13, 1, 'Rétrofacturation'),
       (1, 1, 'En Attente'),
       (16, 1, 'Annulé'),
       (15, 1, 'Traitée'),
       (14, 1, 'Expiré');

-----------------------------------------------------------

--
-- Dumping data for table `oc_product_attribute`
--

INSERT INTO `oc_product_attribute` (`product_id`, `attribute_id`, `language_id`, `text`)
VALUES (43, 2, 1, '1'),
       (47, 4, 1, '16GB'),
       (43, 4, 1, '8gb'),
       (42, 3, 1, '100mhz'),
       (47, 2, 1, '4');

-----------------------------------------------------------

--
-- Dumping data for table `oc_product_description`
--

INSERT INTO `oc_product_description` (`product_id`, `language_id`, `name`, `description`, `tag`, `meta_title`, `meta_description`, `meta_keyword`)
VALUES (35, 1, 'Produit 8', '&lt;p&gt;\r\n	Produit 8&lt;/p&gt;\r\n', '', 'Produit 8', '', ''),
       (48, 1, 'iPod Classic',
        '&lt;div class=&quot;cpt_product_description &quot;&gt;\r\n	&lt;div&gt;\r\n		&lt;p&gt;\r\n			&lt;strong&gt;Plus d''espace pour bouger.&lt;/strong&gt;&lt;/p&gt;\r\n		&lt;p&gt;\r\n			Avec 80 Go ou 160 Go de stockage et jusqu''à 40 heures d''autonomie, le nouvel iPod classic vous permet de profiter de jusqu''à 40 000 chansons ou jusqu''à 200 heures de vidéo ou toute combinaison où que vous soyez.&lt;/p&gt;\r\n		&lt;p&gt;\r\n			&lt;strong&gt;Cover Flow.&lt;/strong&gt;&lt;/p&gt;\r\n		&lt;p&gt;\r\n			Naviguez dans votre collection musicale en feuilletant les pochettes d''album. Sélectionnez un album pour le retourner et voir la liste des morceaux.&lt;/p&gt;\r\n		&lt;p&gt;\r\n			&lt;strong&gt;Interface améliorée.&lt;/strong&gt;&lt;/p&gt;\r\n		&lt;p&gt;\r\n			Profitez d''une toute nouvelle manière de parcourir et de visualiser votre musique et vos vidéos.&lt;/p&gt;\r\n		&lt;p&gt;\r\n			&lt;strong&gt;Design plus élégant.&lt;/strong&gt;&lt;/p&gt;\r\n		&lt;p&gt;\r\n			Beau, durable et plus élégant que jamais, l''iPod classic est désormais doté d''un boîtier en aluminium anodisé et en acier inoxydable poli aux bords arrondis.&lt;/p&gt;\r\n	&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;!-- cpt_container_end --&gt;',
        '', 'iPod Classic', '', ''),
       (40, 1, 'iPhone', '&lt;p class=&quot;intro&quot;&gt;\r\n	L''iPhone est un téléphone mobile révolutionnaire qui vous permet de passer un appel en tapant simplement un nom ou un numéro dans votre carnet d''adresses, une liste de favoris ou un journal d''appels. Il synchronise également automatiquement tous vos contacts depuis un PC, un Mac ou un service Internet. Et il vous permet de sélectionner et d''écouter vos messages vocaux dans l''ordre que vous souhaitez, comme un email.&lt;/p&gt;\r\n', '', 'iPhone', '', ''),

       (28, 1, 'HTC Touch HD',
        '&lt;p&gt;\r\n	HTC Touch - en Haute Définition. Regardez des vidéos musicales et du contenu en streaming avec une clarté haute définition impressionnante pour une expérience mobile que vous n''auriez jamais imaginée possible. Élégant et séduisant, le HTC Touch HD offre la prochaine génération de fonctionnalités mobiles, le tout à portée de main. Entièrement intégré à Windows Mobile Professional 6.1, ultrarapide 3.5G, GPS, appareil photo 5 MP, et bien plus - le tout livré sur un écran tactile WVGA de 3,8 pouces d''une netteté époustouflante - prenez le contrôle de votre monde mobile avec le HTC Touch HD.&lt;/p&gt;\r\n&lt;p&gt;\r\n	&lt;strong&gt;Caractéristiques&lt;/strong&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Processeur Qualcomm&amp;reg; MSM 7201A&amp;trade; 528 MHz&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Système d''exploitation Windows Mobile&amp;reg; 6.1 Professionnel&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Mémoire : 512 Mo ROM, 288 Mo RAM&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Dimensions : 115 mm x 62,8 mm x 12 mm / 146,4 grammes&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Écran tactile plat TFT-LCD de 3,8 pouces avec résolution WVGA de 480 x 800&lt;/li&gt;\r\n	&lt;li&gt;\r\n		HSDPA/WCDMA : Europe/Asie : 900/2100 MHz ; Jusqu''à 2 Mbps en liaison montante et 7,2 Mbps en liaison descendante&lt;/li&gt;\r\n	&lt;li&gt;\r\n		GSM/GPRS/EDGE quadribande : Europe/Asie : 850/900/1800/1900 MHz (Fréquence de bande, disponibilité HSUPA et vitesse des données dépendantes de l''opérateur.)&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Contrôle de l''appareil via HTC TouchFLO&amp;trade; 3D &amp;amp; Boutons tactiles en façade&lt;/li&gt;\r\n	&lt;li&gt;\r\n		GPS et A-GPS prêts&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Bluetooth&amp;reg; 2.0 avec Débit de Données Amélioré (EDR) et A2DP pour casques stéréo sans fil&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Wi-Fi&amp;reg; : IEEE 802.11 b/g&lt;/li&gt;\r\n	&lt;li&gt;\r\n		HTC ExtUSB&amp;trade; (mini-USB 11 broches 2.0)&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Appareil photo couleur 5 mégapixels avec autofocus&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Appareil photo couleur CMOS VGA&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Prise audio intégrée de 3,5 mm, microphone, haut-parleur et radio FM&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Formats de sonneries : AAC, AAC+, eAAC+, AMR-NB, AMR-WB, QCP, MP3, WMA, WAV&lt;/li&gt;\r\n	&lt;li&gt;\r\n		40 sonneries polyphoniques et format MIDI standard 0 et 1 (SMF)/SP MIDI&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Batterie rechargeable Lithium-ion ou Lithium-ion polymère de 1350 mAh&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Emplacement d''extension : carte mémoire microSD&amp;trade; (Compatible SD 2.0)&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Gamme de tension/fréquence de l''adaptateur secteur : 100 ~ 240V AC, 50/60 Hz Sortie DC : 5V et 1A&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Caractéristiques spéciales : Radio FM, Capteur G&lt;/li&gt;\r\n&lt;/ul&gt;\r\n',
        '', 'HTC Touch HD', '', ''),

       (44, 1, 'MacBook Air', '&lt;div&gt;\r\n	Le MacBook Air est ultra-fin, ultra-portable et totalement unique. Mais on ne perd pas des centimètres et des grammes du jour au lendemain. C&apos;est le résultat d&apos;une révision des conventions. D&apos;innovations sans fil multiples. Et d&apos;un design révolutionnaire. Avec le MacBook Air, l&apos;informatique mobile atteint soudainement une nouvelle norme.&lt;/div&gt;\r\n', '', 'MacBook Air', '', ''),
       (45, 1, 'MacBook Pro',
        '&lt;div class=&quot;cpt_product_description &quot;&gt;\r\n	&lt;div&gt;\r\n		&lt;p&gt;\r\n			&lt;b&gt;Dernière architecture mobile Intel&lt;/b&gt;&lt;/p&gt;\r\n		&lt;p&gt;\r\n			Équipé des processeurs mobiles les plus avancés d&apos;Intel, le nouveau MacBook Pro Core 2 Duo est plus de 50 % plus rapide que le MacBook Pro Core Duo original et prend désormais en charge jusqu&apos;à 4 Go de RAM.&lt;/p&gt;\r\n		&lt;p&gt;\r\n			&lt;b&gt;Graphismes de pointe&lt;/b&gt;&lt;/p&gt;\r\n		&lt;p&gt;\r\n			La NVIDIA GeForce 8600M GT offre une puissance de traitement graphique exceptionnelle. Pour une toile créative ultime, vous pouvez même configurer le modèle 17 pouces avec un écran de résolution 1920 x 1200.&lt;/p&gt;\r\n		&lt;p&gt;\r\n			&lt;b&gt;Conçu pour la vie en déplacement&lt;/b&gt;&lt;/p&gt;\r\n		&lt;p&gt;\r\n			Des innovations telles qu&apos;une connexion magnétique pour l&apos;alimentation et un clavier éclairé avec capteur de lumière ambiante placent le MacBook Pro dans une classe à part.&lt;/p&gt;\r\n		&lt;p&gt;\r\n			&lt;b&gt;Connectez. Créez. Communiquez.&lt;/b&gt;&lt;/p&gt;\r\n		&lt;p&gt;\r\n			Configurez rapidement une visioconférence avec la caméra iSight intégrée. Contrôlez vos présentations et vos médias à une distance de 9 mètres grâce à la télécommande Apple incluse. Connectez-vous à des périphériques haut débit avec FireWire 800 et DVI.&lt;/p&gt;\r\n		&lt;p&gt;\r\n			&lt;b&gt;Technologie sans fil de nouvelle génération&lt;/b&gt;&lt;/p&gt;\r\n		&lt;p&gt;\r\n			Grâce à la technologie sans fil 802.11n, le MacBook Pro offre jusqu&apos;à cinq fois la performance et jusqu&apos;à deux fois la portée des technologies de génération précédente.&lt;/p&gt;\r\n	&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;!-- cpt_container_end --&gt;',
        '', 'MacBook Pro', '', ''),

       (29, 1, 'Palm Treo Pro',
        '&lt;p&gt;\r\n	Redéfinissez votre journée de travail avec le smartphone Palm Treo Pro. Parfaitement équilibré, il vous permet de répondre aux emails professionnels et personnels, de rester à jour sur vos rendez-vous et contacts, et d&apos;utiliser le Wi-Fi ou le GPS lors de vos déplacements. Ensuite, regardez une vidéo sur YouTube, consultez les actualités et le sport sur le web, ou écoutez quelques chansons. Équilibrez travail et loisirs comme vous le souhaitez avec le Palm Treo Pro.&lt;/p&gt;\r\n&lt;p&gt;\r\n	&lt;strong&gt;Caractéristiques&lt;/strong&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Windows Mobile&amp;reg; 6.1 Édition Professionnelle&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Processeur Qualcomm&amp;reg; MSM7201 400 MHz&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Écran tactile TFT couleur transflectif 320x320&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Radio HSDPA/UMTS/EDGE/GPRS/GSM&lt;/li&gt;\r\n	&lt;li&gt;\r\n		UMTS tri-bande &amp;mdash; 850 MHz, 1900 MHz, 2100 MHz&lt;/li&gt;\r\n	&lt;li&gt;\r\n		GSM quadri-bande &amp;mdash; 850/900/1800/1900&lt;/li&gt;\r\n	&lt;li&gt;\r\n		802.11b/g avec authentification WPA, WPA2 et 801.1x&lt;/li&gt;\r\n	&lt;li&gt;\r\n		GPS intégré&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Bluetooth Version : 2.0 + Enhanced Data Rate&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Stockage : 256 Mo (100 Mo disponibles pour l&apos;utilisateur), 128 Mo de RAM&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Appareil photo 2.0 mégapixels, jusqu&apos;à 8x zoom numérique et capture vidéo&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Batterie lithium-ion amovible et rechargeable de 1500 mAh&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Jusqu&apos;à 5 heures d&apos;autonomie en conversation et jusqu&apos;à 250 heures en veille&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Extension via carte MicroSDHC (jusqu&apos;à 32 Go pris en charge)&lt;/li&gt;\r\n	&lt;li&gt;\r\n		MicroUSB 2.0 pour synchronisation et recharge&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Prise casque stéréo 3,5 mm&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Dimensions : 60 mm (L) x 114 mm (H) x 13,5 mm (P) / 133 g&lt;/li&gt;\r\n&lt;/ul&gt;\r\n',
        '', 'Palm Treo Pro', '', ''),

       (36, 1, 'iPod Nano',
        '&lt;div&gt;\r\n	&lt;p&gt;\r\n		&lt;strong&gt;Vidéo dans votre poche.&lt;/strong&gt;&lt;/p&gt;\r\n	&lt;p&gt;\r\n		C&apos;est le petit iPod avec une grande idée : la vidéo. Le lecteur de musique le plus populaire au monde vous permet désormais de profiter de films, d&apos;émissions de télévision et plus encore sur un écran de deux pouces, 65 % plus lumineux qu&apos;auparavant.&lt;/p&gt;\r\n	&lt;p&gt;\r\n		&lt;strong&gt;Cover Flow.&lt;/strong&gt;&lt;/p&gt;\r\n	&lt;p&gt;\r\n		Naviguez dans votre collection musicale en feuilletant les pochettes d&apos;album. Sélectionnez un album pour le retourner et voir la liste des morceaux.&lt;strong&gt;&amp;nbsp;&lt;/strong&gt;&lt;/p&gt;\r\n	&lt;p&gt;\r\n		&lt;strong&gt;Interface améliorée.&lt;/strong&gt;&lt;/p&gt;\r\n	&lt;p&gt;\r\n		Profitez d&apos;une toute nouvelle manière de parcourir et de visualiser votre musique et vos vidéos.&lt;/p&gt;\r\n	&lt;p&gt;\r\n		&lt;strong&gt;Élégant et coloré.&lt;/strong&gt;&lt;/p&gt;\r\n	&lt;p&gt;\r\n		Avec un boîtier en aluminium anodisé et en acier inoxydable poli et un choix de cinq couleurs, l&apos;iPod nano est conçu pour impressionner.&lt;/p&gt;\r\n	&lt;p&gt;\r\n		&lt;strong&gt;iTunes.&lt;/strong&gt;&lt;/p&gt;\r\n	&lt;p&gt;\r\n		Disponible en téléchargement gratuit, iTunes facilite la navigation et l&apos;achat de millions de chansons, films, émissions de télévision, livres audio et jeux, ainsi que le téléchargement de podcasts gratuits sur l&apos;iTunes Store. Vous pouvez également importer votre propre musique, gérer toute votre bibliothèque multimédia et synchroniser facilement votre iPod ou iPhone.&lt;/p&gt;\r\n&lt;/div&gt;\r\n',
        '', 'iPod Nano', '', ''),
       (46, 1, 'Sony VAIO', '&lt;div&gt;\r\n	Puissance sans précédent. La nouvelle génération de technologie de traitement est arrivée. Intégrée dans les derniers ordinateurs portables VAIO, elle repose sur la toute dernière innovation d&apos;Intel : la technologie de processeur Intel&amp;reg; Centrino&amp;reg; 2. Offrant une vitesse incroyable, une connectivité sans fil étendue, un support multimédia amélioré et une efficacité énergétique accrue, tous les éléments essentiels de haute performance sont parfaitement combinés en une seule puce.&lt;/div&gt;\r\n', '', 'Sony VAIO', '', ''),
       (47, 1, 'HP LP3065', '&lt;p&gt;\r\n	Impressionnez vos collègues avec le nouvel écran plat HP LP3065 de 30 pouces en diagonale. Cet écran phare offre des performances et des fonctionnalités de présentation de premier ordre sur un immense écran au format large, tout en vous permettant de travailler dans un confort optimal - vous pourriez même oublier que vous êtes au bureau.&lt;/p&gt;\r\n', '', 'HP LP3065', '', ''),

       (32, 1, 'iPod Touch',
        '&lt;p&gt;\r\n	&lt;strong&gt;Interface multi-touch révolutionnaire.&lt;/strong&gt;&lt;br /&gt;\r\n	L&apos;iPod touch est doté de la même technologie d&apos;écran multi-touch que l&apos;iPhone. Pincez pour zoomer sur une photo. Faites défiler vos chansons et vidéos d&apos;un simple geste. Parcourez votre bibliothèque par pochettes d&apos;album avec Cover Flow.&lt;/p&gt;\r\n&lt;p&gt;\r\n	&lt;strong&gt;Superbe écran large de 3,5 pouces.&lt;/strong&gt;&lt;br /&gt;\r\n	Voyez vos films, émissions de télévision et photos prendre vie avec des couleurs vives et éclatantes sur l&apos;écran de 320 x 480 pixels.&lt;/p&gt;\r\n&lt;p&gt;\r\n	&lt;strong&gt;Téléchargement de musique directement depuis iTunes.&lt;/strong&gt;&lt;br /&gt;\r\n	Faites vos achats sur l&apos;iTunes Wi-Fi Music Store depuis n&apos;importe où avec le Wi-Fi. Parcourez ou recherchez la musique que vous souhaitez, prévisualisez-la et achetez-la d&apos;un simple tapotement.&lt;/p&gt;\r\n&lt;p&gt;\r\n	&lt;strong&gt;Naviguez sur le web avec le Wi-Fi.&lt;/strong&gt;&lt;br /&gt;\r\n	Parcourez le web avec Safari et regardez des vidéos YouTube sur le premier iPod doté du Wi-Fi intégré.&lt;br /&gt;\r\n	&amp;nbsp;&lt;/p&gt;\r\n',
        '', 'iPod Touch', '', ''),
       (41, 1, 'iMac', '&lt;div&gt;\r\n	Alors que vous pensiez que l&apos;iMac avait tout, il y a maintenant encore plus. Des processeurs Intel Core 2 Duo plus puissants. Et plus de mémoire en standard. Combinez cela avec Mac OS X Leopard et iLife &apos;08, et l&apos;iMac est plus tout-en-un que jamais. L&apos;iMac offre des performances incroyables dans un espace incroyablement mince.&lt;/div&gt;\r\n', '', 'iMac', '', ''),
       (33, 1, 'Samsung SyncMaster 941BW', '&lt;div&gt;\r\n	Imaginez les avantages d&apos;opter pour un grand écran sans ralentir. Le grand écran 941BW de 19 pouces combine un format large avec un temps de réponse rapide des pixels, pour des images plus grandes, plus d&apos;espace de travail et des mouvements nets. De plus, les technologies exclusives MagicBright 2, MagicColor et MagicTune permettent d&apos;obtenir l&apos;image idéale dans chaque situation, tandis que les bords fins et élégants et les supports réglables apportent du style exactement comme vous le souhaitez. Avec le moniteur LCD analogique/numérique large Samsung 941BW, il est facile d&apos;imaginer ce qu&apos;il offre.&lt;/div&gt;\r\n', '', 'Samsung SyncMaster 941BW', '', ''),
       (34, 1, 'iPod Shuffle',
        '&lt;div&gt;\r\n	&lt;strong&gt;Conçu pour être porté.&lt;/strong&gt;\r\n	&lt;p&gt;\r\n		Accrochez le lecteur de musique le plus portable au monde et emportez jusqu&apos;à 240 chansons partout avec vous. Choisissez parmi cinq couleurs, dont quatre nouvelles teintes, pour affirmer votre style musical.&lt;/p&gt;\r\n	&lt;p&gt;\r\n		&lt;strong&gt;Le hasard rencontre le rythme.&lt;/strong&gt;&lt;/p&gt;\r\n	&lt;p&gt;\r\n		Avec la fonction de remplissage automatique d&apos;iTunes, l&apos;iPod shuffle peut offrir une nouvelle expérience musicale à chaque synchronisation. Pour encore plus de variété, vous pouvez mélanger les chansons pendant la lecture grâce à un interrupteur.&lt;/p&gt;\r\n	&lt;strong&gt;Tout est simple.&lt;/strong&gt;\r\n	&lt;p&gt;\r\n		Rechargez et synchronisez avec le dock USB inclus. Utilisez les commandes de l&apos;iPod shuffle d&apos;une seule main. Profitez de jusqu&apos;à 12 heures de musique ininterrompue sans saut.&lt;/p&gt;\r\n&lt;/div&gt;\r\n',
        '', 'iPod Shuffle', '', ''),

       (43, 1, 'MacBook',
        '&lt;div&gt;\r\n	&lt;p&gt;\r\n		&lt;b&gt;Processeur Intel Core 2 Duo&lt;/b&gt;&lt;/p&gt;\r\n	&lt;p&gt;\r\n		Propulsé par un processeur Intel Core 2 Duo atteignant des vitesses allant jusqu&apos;à 2,16 GHz, le nouveau MacBook est le plus rapide jamais conçu.&lt;/p&gt;\r\n	&lt;p&gt;\r\n		&lt;b&gt;1 Go de mémoire, disques durs plus grands&lt;/b&gt;&lt;/p&gt;\r\n	&lt;p&gt;\r\n		Le nouveau MacBook est désormais équipé de 1 Go de mémoire en standard et de disques durs plus grands sur toute la gamme, parfaits pour exécuter plus de vos applications préférées et stocker des collections multimédias croissantes.&lt;/p&gt;\r\n	&lt;p&gt;\r\n		&lt;b&gt;Design élégant de 1,08 pouce d&apos;épaisseur&lt;/b&gt;&lt;/p&gt;\r\n	&lt;p&gt;\r\n		Le MacBook facilite vos déplacements grâce à son boîtier en polycarbonate robuste, ses technologies sans fil intégrées et son adaptateur secteur MagSafe innovant qui se déconnecte automatiquement en cas de tension sur le câble.&lt;/p&gt;\r\n	&lt;p&gt;\r\n		&lt;b&gt;Caméra iSight intégrée&lt;/b&gt;&lt;/p&gt;\r\n	&lt;p&gt;\r\n		Dès la sortie de la boîte, vous pouvez discuter en visioconférence avec des amis ou des membres de votre famille, enregistrer une vidéo à votre bureau ou prendre des photos amusantes avec Photo Booth.&lt;/p&gt;\r\n&lt;/div&gt;\r\n',
        '', 'MacBook', '', ''),
       (31, 1, 'Nikon D300',
        '&lt;div class=&quot;cpt_product_description &quot;&gt;\r\n	&lt;div&gt;\r\n		Conçu avec des fonctionnalités et des performances de niveau professionnel, le D300 de 12,3 mégapixels effectifs combine des technologies totalement nouvelles avec des fonctionnalités avancées héritées du D3, le reflex numérique professionnel récemment annoncé par Nikon, pour offrir aux photographes sérieux des performances remarquables associées à une grande agilité.&lt;br /&gt;\r\n		&lt;br /&gt;\r\n		À l&apos;instar du D3, le D300 est doté du système exclusif de traitement d&apos;images EXPEED de Nikon, essentiel pour atteindre la vitesse et la puissance de traitement nécessaires à de nombreuses nouvelles fonctionnalités de l&apos;appareil. Le D300 propose un nouveau système de mise au point automatique à 51 points avec la fonction de suivi 3D de Nikon et deux nouveaux modes de prise de vue LiveView qui permettent aux utilisateurs de cadrer une photo à l&apos;aide de l&apos;écran LCD haute résolution de l&apos;appareil. Le D300 partage un système de reconnaissance de scène similaire à celui du D3 ; il promet d&apos;améliorer considérablement la précision de la mise au point automatique, de l&apos;exposition automatique et de la balance des blancs automatique en reconnaissant le sujet ou la scène photographiée et en appliquant ces informations aux calculs des trois fonctions.&lt;br /&gt;\r\n		&lt;br /&gt;\r\n		Le D300 réagit à une vitesse fulgurante, s&apos;allumant en seulement 0,13 seconde et prenant des photos avec un temps de réponse au déclenchement imperceptible de 45 millisecondes. Le D300 est capable de prendre des photos à une vitesse rapide de six images par seconde et peut atteindre huit images par seconde lorsqu&apos;il est utilisé avec le grip optionnel MB-D10 à alimentation multiple. En rafales continues, le D300 peut prendre jusqu&apos;à 100 photos à une résolution complète de 12,3 mégapixels. (Paramètre d&apos;image NORMAL-LARGE, utilisant une carte CompactFlash SanDisk Extreme IV 1 Go.)&lt;br /&gt;\r\n		&lt;br /&gt;\r\n		Le D300 intègre une gamme de technologies et de fonctionnalités innovantes qui amélioreront considérablement la précision, le contrôle et les performances que les photographes peuvent obtenir avec leur équipement. Son nouveau système de reconnaissance de scène améliore l&apos;utilisation du capteur 1 005 segments réputé de Nikon pour reconnaître les couleurs et les motifs lumineux, ce qui aide l&apos;appareil à déterminer le sujet et le type de scène photographiée avant qu&apos;une photo ne soit prise. Ces informations sont utilisées pour améliorer la précision des fonctions de mise au point automatique, d&apos;exposition automatique et de balance des blancs automatique du D300. Par exemple, l&apos;appareil peut mieux suivre les sujets en mouvement et, en les identifiant, il peut également sélectionner automatiquement les points de mise au point plus rapidement et avec une plus grande précision. Il peut également analyser les hautes lumières et déterminer plus précisément l&apos;exposition, ainsi qu&apos;interpréter les sources lumineuses pour offrir une détection de la balance des blancs plus précise.&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;!-- cpt_container_end --&gt;',
        '', 'Nikon D300', '', ''),

       (49, 1, 'Samsung Galaxy Tab 10.1',
        '&lt;p&gt;\r\n	La Samsung Galaxy Tab 10.1 est la tablette la plus fine au monde, avec une épaisseur de 8,6 mm, fonctionnant sous Android 3.0 Honeycomb OS avec un processeur Tegra 2 double cœur à 1 GHz, similaire à son petit frère, la Samsung Galaxy Tab 8.9.&lt;/p&gt;\r\n&lt;p&gt;\r\n	La Samsung Galaxy Tab 10.1 offre une expérience Android 3.0 pure, avec sa nouvelle interface TouchWiz UX ou TouchWiz 4.0 &amp;ndash; comprenant un panneau interactif vous permettant de personnaliser avec différents contenus, tels que vos photos, favoris et flux sociaux. Elle est équipée d&apos;un écran tactile capacitif WXGA de 10,1 pouces avec une résolution de 1280 x 800 pixels, d&apos;un appareil photo arrière de 3 mégapixels avec flash LED et d&apos;un appareil photo avant de 2 mégapixels. Elle prend en charge la connectivité HSPA+ jusqu&apos;à 21 Mbps, l&apos;enregistrement vidéo HD 720p, la lecture HD 1080p, la compatibilité DLNA, le Bluetooth 2.1, l&apos;USB 2.0, un gyroscope, le Wi-Fi 802.11 a/b/g/n, un emplacement micro-SD, une prise casque 3,5 mm et un emplacement pour carte SIM, incluant le Samsung Stick &amp;ndash; un microphone Bluetooth pouvant être porté dans une poche comme un stylo et une station d&apos;accueil audio avec caisson de basses intégré.&lt;/p&gt;\r\n&lt;p&gt;\r\n	La Samsung Galaxy Tab 10.1 sera disponible en versions 16 Go, 32 Go et 64 Go, préchargée avec Social Hub, Reader&apos;s Hub, Music Hub et Samsung Mini Apps Tray &amp;ndash; qui vous donne accès à des applications fréquemment utilisées pour faciliter le multitâche. Elle est également compatible avec Adobe Flash Player 10.2 et alimentée par une batterie de 6860 mAh offrant jusqu&apos;à 10 heures de lecture vidéo.&amp;nbsp;&amp;auml;&amp;ouml;&lt;/p&gt;\r\n',
        '', 'Samsung Galaxy Tab 10.1', '', ''),

       (42, 1, 'Apple Cinema 30&quot;', '&lt;p&gt;\r\n	&lt;font face=&quot;helvetica,geneva,arial&quot; size=&quot;2&quot;&gt;&lt;font face=&quot;Helvetica&quot; size=&quot;2&quot;&gt;L''écran Apple Cinema HD 30 pouces offre une résolution impressionnante de 2560 x 1600 pixels. Conçu spécifiquement pour les professionnels de la création, cet écran fournit plus d''espace pour un accès simplifié à tous les outils et palettes nécessaires pour éditer, formater et composer votre travail. Associez cet écran à un Mac Pro, MacBook Pro ou PowerMac G5, et il n''y a aucune limite à ce que vous pouvez accomplir. &lt;br&gt;\r\n	&lt;br&gt;\r\n	&lt;/font&gt;&lt;font face=&quot;Helvetica&quot; size=&quot;2&quot;&gt;Le Cinema HD est équipé d''un écran à cristaux liquides à matrice active produisant des images sans scintillement, offrant deux fois la luminosité, deux fois la netteté et deux fois le rapport de contraste d''un écran CRT classique. Contrairement à d''autres écrans plats, il est conçu avec une interface numérique pure pour fournir des images sans distorsion qui n''ont jamais besoin d''être ajustées. Avec plus de 4 millions de pixels numériques, cet écran est particulièrement adapté aux applications scientifiques et techniques telles que la visualisation de structures moléculaires ou l''analyse de données géologiques. &lt;br&gt;\r\n	&lt;br&gt;\r\n	&lt;/font&gt;&lt;font face=&quot;Helvetica&quot; size=&quot;2&quot;&gt;Offrant une performance couleur précise et brillante, le Cinema HD restitue jusqu''à 16,7 millions de couleurs sur une large gamme, vous permettant de voir des nuances subtiles entre les couleurs, des pastels doux aux tons riches et précieux. Un large angle de vision garantit une couleur uniforme d''un bord à l''autre. La technologie ColorSync d''Apple vous permet de créer des profils personnalisés pour maintenir une cohérence des couleurs à l''écran et à l''impression. Résultat : vous pouvez utiliser cet écran en toute confiance dans toutes vos applications nécessitant une précision des couleurs.
&lt;br&gt;\r\n	&lt;br&gt;\r\n	&lt;/font&gt;&lt;font face=&quot;Helvetica&quot; size=&quot;2&quot;&gt;Présenté dans un nouveau design en aluminium, l''écran dispose d''un cadre très fin qui améliore la précision visuelle. Chaque écran est équipé de deux ports FireWire 400 et de deux ports USB 2.0, rendant l''attachement des périphériques de bureau, tels que iSight, iPod, appareils photo numériques et fixes, disques durs, imprimantes et scanners, encore plus accessible et pratique. Profitant de l''empreinte beaucoup plus fine et légère d''un écran LCD, les nouveaux écrans prennent en charge la norme de montage VESA (Video Electronics Standards Association). Les clients disposant du kit adaptateur de montage VESA Cinema Display en option bénéficient de la flexibilité de monter leur écran à l''endroit le plus approprié pour leur environnement de travail. &lt;br&gt;\r\n	&lt;br&gt;\r\n	&lt;/font&gt;&lt;font face=&quot;Helvetica&quot; size=&quot;2&quot;&gt;Le Cinema HD dispose d''un design à câble unique avec une connexion élégante pour USB 2.0, FireWire 400 et une connexion purement numérique utilisant l''interface standard de l''industrie Digital Video Interface (DVI). La connexion DVI permet une connexion purement numérique directe.&lt;br&gt;\r\n	&lt;/font&gt;&lt;/font&gt;&lt;/p&gt;\r\n&lt;h3&gt;\r\n	Caractéristiques :&lt;/h3&gt;\r\n&lt;p&gt;\r\n	Performance d''affichage inégalée&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Écran à cristaux liquides à matrice active de 30 pouces (visibles) offrant une qualité d''image époustouflante et des couleurs vives et saturées.&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Prise en charge d''une résolution de 2560 par 1600 pixels pour l''affichage d''images fixes et vidéo en haute définition.&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Design au format large permettant l''affichage simultané de deux pages complètes de texte et de graphiques.&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Connecteur DVI standard pour une connexion directe aux ordinateurs Mac et Windows.&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Angle de vision extrêmement large (170 degrés) horizontal et vertical pour une visibilité et des performances colorimétriques maximales.&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Réponse ultra-rapide des pixels pour une lecture fluide des vidéos numériques.&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Prise en charge de 16,7 millions de couleurs saturées, adaptées à toutes les applications graphiques intensives.&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	Installation et utilisation simples&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Câble unique avec une connexion élégante pour les ports DVI, USB et FireWire.&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Hub USB 2.0 intégré à deux ports pour une connexion facile des périphériques de bureau.&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Deux ports FireWire 400 pour prendre en charge iSight et d''autres périphériques de bureau.&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	Design élégant et raffiné&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Immense espace de travail virtuel, empreinte très réduite.&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Design à cadre étroit pour minimiser l''impact visuel de l''utilisation de deux écrans.&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Design unique de charnière pour un ajustement sans effort.&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Prise en charge des solutions de montage VESA (adaptateur de montage VESA pour Cinema Display vendu séparément).&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;h3&gt;\r\n	Spécifications techniques&lt;/h3&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Taille de l''écran (taille de l''image visible en diagonale)&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Apple Cinema HD Display : 30 pouces (29,7 pouces visibles).&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Type d''écran&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Écran à cristaux liquides à matrice active TFT (Thin Film Transistor).&lt;/li&gt;
(AMLCD)&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Résolutions&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		2560 x 1600 pixels (résolution optimale)&lt;/li&gt;\r\n	&lt;li&gt;\r\n		2048 x 1280&lt;/li&gt;\r\n	&lt;li&gt;\r\n		1920 x 1200&lt;/li&gt;\r\n	&lt;li&gt;\r\n		1280 x 800&lt;/li&gt;\r\n	&lt;li&gt;\r\n		1024 x 640&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Couleurs d''affichage (maximum)&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		16,7 millions&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Angle de vision (typique)&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		170° horizontal ; 170° vertical&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Luminosité (typique)&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Écran Cinema HD 30 pouces : 400 cd/m²&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Rapport de contraste (typique)&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		700:1&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Temps de réponse (typique)&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		16 ms&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Pas de pixel&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Écran Cinema HD 30 pouces : 0,250 mm&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Traitement de l''écran&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Revêtement antireflet&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Commandes utilisateur (matériel et logiciel)&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Alimentation de l''écran,&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Mise en veille et réveil du système&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Luminosité&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Inclinaison de l''écran&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Connecteurs et câbles&lt;/b&gt;&lt;br&gt;\r\n	Câble&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		DVI (Digital Visual Interface)&lt;/li&gt;\r\n	&lt;li&gt;\r\n		FireWire 400&lt;/li&gt;\r\n	&lt;li&gt;\r\n		USB 2.0&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Alimentation DC (24 V)&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	Connecteurs&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Hub USB 2.0 à deux ports, auto-alimenté&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Deux ports FireWire 400&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Port de sécurité Kensington&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Adaptateur de montage VESA&lt;/b&gt;&lt;br&gt;\r\n	Nécessite l''adaptateur de montage VESA Cinema Display en option (M9649G/A)&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Compatible avec les solutions de montage conformes à la norme VESA FDMI (MIS-D, 100, C)&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Exigences électriques&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Tension d''entrée : 100-240 VCA 50-60 Hz&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Puissance maximale en fonctionnement : 150 W&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Mode économie d''énergie : 3 W ou moins&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Exigences environnementales&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Température de fonctionnement : 10° à 35° C (50° à 95° F)&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Température de stockage : -40° à 47° C (-40° à 116° F)&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Humidité de fonctionnement : 20 % à 80 % sans condensation&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Altitude maximale de fonctionnement : 10 000 pieds&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Approvals d''agence&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		FCC Part 15 Class B&lt;/li&gt;\r\n	&lt;li&gt;\r\n		EN55022 Class B&lt;/li&gt;\r\n	&lt;li&gt;\r\n		EN55024&lt;/li&gt;\r\n	&lt;li&gt;\r\n		VCCI Class B&lt;/li&gt;\r\n	&lt;li&gt;\r\n		AS/NZS 3548 Class B&lt;/li&gt;\r\n	&lt;li&gt;\r\n		CNS 13438 Class B&lt;/li&gt;\r\n	&lt;li&gt;\r\n		ICES-003 Class B&lt;/li&gt;\r\n	&lt;li&gt;\r\n		ISO 13406 partie 2&lt;/li&gt;\r\n	&lt;li&gt;\r\n		MPR II&lt;/li&gt;\r\n	&lt;li&gt;\r\n		IEC 60950&lt;/li&gt;\r\n	&lt;li&gt;\r\n		UL 60950&lt;/li&gt;\r\n	&lt;li&gt;\r\n		CSA 60950&lt;/li&gt;\r\n	&lt;li&gt;\r\n		EN60950&lt;/li&gt;\r\n	&lt;li&gt;\r\n		ENERGY STAR&lt;/li&gt;\r\n	&lt;li&gt;\r\n		TCO ''03&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Dimensions et poids&lt;/b&gt;&lt;br&gt;\r\n	Écran Apple Cinema HD 30 pouces&lt;/p&gt;
Display&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Hauteur : 21,3 pouces (54,3 cm)&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Largeur : 27,2 pouces (68,8 cm)&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Profondeur : 8,46 pouces (21,5 cm)&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Poids : 27,5 livres (12,5 kg)&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Exigences système&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Mac Pro, toutes options graphiques&lt;/li&gt;\r\n	&lt;li&gt;\r\n		MacBook Pro&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Power Mac G5 (PCI-X) avec ATI Radeon 9650 ou mieux ou NVIDIA GeForce 6800 GT DDL ou mieux&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Power Mac G5 (PCI Express), toutes options graphiques&lt;/li&gt;\r\n	&lt;li&gt;\r\n		PowerBook G4 avec support DVI double lien&lt;/li&gt;\r\n	&lt;li&gt;\r\n		PC Windows et carte graphique prenant en charge les ports DVI avec une bande passante numérique double lien et norme VESA DDC pour une configuration plug-and-play&lt;/li&gt;\r\n&lt;/ul&gt;\r\n',
        '', 'Apple Cinema 30', '', ''),
       (30, 1, 'Canon EOS 5D',
        '&lt;p&gt;\r\n	Le matériel de presse de Canon pour l''EOS 5D déclare qu''il ''définit une nouvelle catégorie de reflex numériques'', et bien que nous ne soyons généralement pas trop préoccupés par le discours marketing, cette déclaration est clairement assez précise. L''EOS 5D est différent de tout reflex numérique précédent en ce qu''il combine un capteur plein format (taille 35 mm) de haute résolution (12,8 mégapixels) avec un boîtier relativement compact (légèrement plus grand que l''EOS 20D, bien qu''en main, il semble nettement plus ''massif''). L''EOS 5D est destiné à se positionner entre l''EOS 20D et les reflex numériques professionnels EOS-1D. Une différence importante par rapport à ces derniers est que l''EOS 5D n''a pas de joints d''étanchéité environnementaux. Bien que Canon ne fasse pas spécifiquement référence à l''EOS 5D comme à un reflex numérique ''professionnel'', il séduira évidemment les professionnels qui souhaitent un reflex numérique de haute qualité dans un boîtier plus léger que l''EOS-1D. Il attirera sans aucun doute également les propriétaires actuels de l''EOS 20D (espérons simplement qu''ils n''ont pas acheté trop d''objectifs EF-S...). äë&lt;/p&gt;\r\n',
        '', 'sdf', '', '');

-----------------------------------------------------------

--
-- Dumping data for table `oc_return_action`
--

INSERT INTO `oc_return_action` (`return_action_id`, `language_id`, `name`)
VALUES (1, 1, 'Refunded'),
       (2, 1, 'Credit Issued'),
       (3, 1, 'Replacement Sent');
-----------------------------------------------------------

--
-- Dumping data for table `oc_return_reason`
--

INSERT INTO `oc_return_reason` (`return_reason_id`, `language_id`, `name`)
VALUES (1, 1, 'Dead On Arrival'),
       (2, 1, 'Received Wrong Item'),
       (3, 1, 'Order Error'),
       (4, 1, 'Faulty, please supply details'),
       (5, 1, 'Other, please supply details');

-----------------------------------------------------------

--
-- Dumping data for table `oc_return_status`
--

INSERT INTO `oc_return_status` (`return_status_id`, `language_id`, `name`)
VALUES (1, 1, 'Pending'),
       (3, 1, 'Complete'),
       (2, 1, 'Awaiting Products');

-----------------------------------------------------------

--
-- Dumping data for table `oc_seo_url`
--

INSERT INTO `oc_seo_url` (`store_id`, `language_id`, `key`, `value`, `keyword`, `sort_order`)
VALUES (0, 1, 'product_id', '47', 'hp-lp3065-fr', 1),
       (0, 1, 'product_id', '48', 'ipod-classic-fr', 1),
       (0, 1, 'product_id', '28', 'htc-touch-hd-fr', 1),
       (0, 1, 'product_id', '43', 'macbook-fr', 1),
       (0, 1, 'product_id', '44', 'macbook-air-fr', 1),
       (0, 1, 'product_id', '45', 'macbook-pro-fr', 1),
       (0, 1, 'product_id', '30', 'canon-eos-5d-fr', 1),
       (0, 1, 'product_id', '31', 'nikon-d300-fr', 1),
       (0, 1, 'product_id', '29', 'palm-treo-pro-fr', 1),
       (0, 1, 'product_id', '35', 'product-8-fr', 1),
       (0, 1, 'product_id', '49', 'samsung-galaxy-tab-10-1-fr', 1),
       (0, 1, 'product_id', '33', 'samsung-syncmaster-941bw-fr', 1),
       (0, 1, 'product_id', '46', 'sony-vaio-fr', 1),
       (0, 1, 'product_id', '41', 'imac-fr', 1),
       (0, 1, 'product_id', '40', 'iphone-fr', 1),
       (0, 1, 'product_id', '36', 'ipod-nano-fr', 1),
       (0, 1, 'product_id', '34', 'ipod-shuffle-fr', 1),
       (0, 1, 'product_id', '32', 'ipod-touch-fr', 1),
       (0, 1, 'product_id', '50', 'apple-4-fr', 1),
       (0, 1, 'product_id', '42', 'apple-cinema-fr', 1),
       (0, 1, 'manufacturer_id', '5', 'htc-fr', 0),
       (0, 1, 'manufacturer_id', '7', 'hewlett-packard-fr', 0),
       (0, 1, 'manufacturer_id', '6', 'palm-fr', 0),
       (0, 1, 'manufacturer_id', '10', 'sony-fr', 0),
       (0, 1, 'manufacturer_id', '9', 'canon-fr', 0),
       (0, 1, 'manufacturer_id', '8', 'apple-fr', 0),
       (0, 1, 'path', '30', 'printer-fr', 0),
       (0, 1, 'path', '20_27', 'desktops/mac-fr', 0),
       (0, 1, 'path', '20_26', 'desktops/pc-fr', 0),
       (0, 1, 'path', '25', 'component-fr', 0),
       (0, 1, 'path', '25_29', 'component/mouse-fr', 0),
       (0, 1, 'path', '33', 'cameras-fr', 0),
       (0, 1, 'path', '25_28', 'component/monitor-fr', 0),
       (0, 1, 'path', '25_28_35', 'component/monitor/test-1-fr', 0),
       (0, 1, 'path', '25_28_36', 'component/monitor/test-2-fr', 0),
       (0, 1, 'path', '25_30', 'component/printers-fr', 0),
       (0, 1, 'path', '25_31', 'component/scanner-fr', 0),
       (0, 1, 'path', '25_32', 'component/web-camera-fr', 0),
       (0, 1, 'path', '20', 'desktops-fr', 0),
       (0, 1, 'path', '18', 'laptop-notebook-fr', 0),
       (0, 1, 'path', '18_46', 'laptop-notebook/macs-fr', 0),
       (0, 1, 'path', '18_45', 'laptop-notebook/windows-fr', 0),
       (0, 1, 'path', '34', 'mp3-players-fr', 0),
       (0, 1, 'path', '34_43', 'mp3-players/test-11-fr', 0),
       (0, 1, 'path', '34_44', 'mp3-players/test-12-fr', 0),
       (0, 1, 'path', '34_47', 'mp3-players/test-15-fr', 0),
       (0, 1, 'path', '34_48', 'mp3-players/test-16-fr', 0),
       (0, 1, 'path', '34_49', 'mp3-players/test-17-fr', 0),
       (0, 1, 'path', '34_50', 'mp3-players/test-18-fr', 0),
       (0, 1, 'path', '34_51', 'mp3-players/test-19-fr', 0),
       (0, 1, 'path', '34_52', 'mp3-players/test-20-fr', 0),
       (0, 1, 'path', '34_52_58', 'mp3-players/test-20/test-25-fr', 0),
       (0, 1, 'path', '34_53', 'mp3-players/test-21-fr', 0),
       (0, 1, 'path', '34_54', 'mp3-players/test-22-fr', 0),
       (0, 1, 'path', '34_55', 'mp3-players/test-23-fr', 0),
       (0, 1, 'path', '34_56', 'mp3-players/test-24-fr', 0),
       (0, 1, 'path', '34_38', 'mp3-players/test-4-fr', 0),
       (0, 1, 'path', '34_37', 'mp3-players/test-5-fr', 0),
       (0, 1, 'path', '34_39', 'mp3-players/test-6-fr', 0),
       (0, 1, 'path', '34_40', 'mp3-players/test-7-fr', 0),
       (0, 1, 'path', '34_41', 'mp3-players/test-8-fr', 0),
       (0, 1, 'path', '34_42', 'mp3-players/test-9-fr', 0),
       (0, 1, 'path', '24', 'smartphone-fr', 0),
       (0, 1, 'path', '17', 'software-fr', 0),
       (0, 1, 'path', '57', 'tablet-fr', 0),
       (0, 1, 'information_id', '1', 'about-us-fr', 0),
       (0, 1, 'information_id', '2', 'terms-fr', 0),
       (0, 1, 'information_id', '4', 'delivery-fr', 0),
       (0, 1, 'information_id', '3', 'privacy-fr', 0),
       (0, 1, 'language', 'fr-fr', 'fr-fr', -2),
       (0, 1, 'route', 'information/information.info', 'info-fr', 0),
       (0, 1, 'route', 'information/information', 'information-fr', -1),
       (0, 1, 'route', 'product/product', 'product-fr', -1),
       (0, 1, 'route', 'product/category', 'catalog-fr', -1),
       (0, 1, 'route', 'product/manufacturer', 'brands-fr', -1);

-----------------------------------------------------------

--
-- Dumping data for table `oc_stock_status`
--

INSERT INTO `oc_stock_status` (`stock_status_id`, `language_id`, `name`)
VALUES (7, 1, 'In Stock'),
       (8, 1, 'Pre-Order'),
       (5, 1, 'Out Of Stock'),
       (6, 1, '2-3 Days');

-----------------------------------------------------------

--
-- Dumping data for table `oc_subscription_plan_description`
--

INSERT INTO `oc_subscription_plan_description` (`subscription_plan_id`, `language_id`, `name`)
VALUES (1, 1, 'Daily'),
       (2, 1, 'Weekly'),
       (3, 1, 'Monthly');

-----------------------------------------------------------

--
-- Dumping data for table `oc_subscription_status`
--

INSERT INTO `oc_subscription_status` (`subscription_status_id`, `language_id`, `name`)
VALUES (1, 1, 'En attente'),
       (2, 1, 'Actif'),
       (3, 1, 'Expiré'),
       (4, 1, 'Suspendu'),
       (5, 1, 'Annulé'),
       (6, 1, 'Échoué'),
       (7, 1, 'Refusé');

-----------------------------------------------------------

--
-- Dumping data for table `oc_weight_class_description`
--

INSERT INTO `oc_weight_class_description` (`weight_class_id`, `language_id`, `title`, `unit`)
VALUES (1, 1, 'Kilogramme', 'kg'),
       (2, 1, 'Gramme', 'g'),
       (3, 1, 'Livre', 'lb'),
       (4, 1, 'Once', 'oz');
