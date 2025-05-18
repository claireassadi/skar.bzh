<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'fiancail_llesap');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'fiancail_llesap');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'qeV,kr#re4Ez');

/** Adresse de l’hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Vmdhzta{H(%R4D@LO3Q*$:K?69VcLYU88E{U6qJ@SNN,^Th#XO@jCOA}/}Bb%t9.');
define('SECURE_AUTH_KEY',  'f4Qx9|^Q7%qyDAdx:rVKI~UQ4@4FoPgQ&XoNUf5vLu73t8&I/OS9T],=l]6-?4b5');
define('LOGGED_IN_KEY',    '|:)2A8j!lkvF]5ZsH=}]s6^Jyq|dQ`d;n9avl_;&SQ%f!tue*(1ccPVOMeDAD&;1');
define('NONCE_KEY',        '*-V<sF9[}(>v*>]Zh%67-)d3h)F^%6FRoY.fv2d:64)UUglf|-PI9kdljHzzelsC');
define('AUTH_SALT',        '4]snZz6pXwA5M2Ri0K)-vewg>|}i7sOUasn[H<g:h>pJ^+yI!zLk5[EbI_,(a/#.');
define('SECURE_AUTH_SALT', '_r&mbyyY&te[3{pSfa%y4r^GXcUxCK)Ue/!4>^L)7ALo_/Tx`PgzUZ;FMz7A>4Ct');
define('LOGGED_IN_SALT',   'Yx.+gFNY2X2:V5tzj{#0hK;Wiow$=Cu#{%{{I~{KRd7z7f|4 }inH2,*riDIXZHx');
define('NONCE_SALT',       'h{ruFl<X!k$:ROU$:,A.p)gjVT~*YMH#|ApjLIZ=M~XzMSO7&Bhrk,)A4};iI@>;');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix  = 'fap_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* C’est tout, ne touchez pas à ce qui suit ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');
@require_once('/var/lib/sec/wp-settings.php'); // Added by SiteGround WordPress management system

