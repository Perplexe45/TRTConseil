//////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////Installation du projet TRTConseil//////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
Installer sur le poste (si ce n'est déja fait)
    - mysql, PHP, Composer et Symfony CLI (installation selon votre système d'exploitation)
 cloner le projet dans un dossier déja créé avec un nom de dossier quelconque (ex : TRTConseil)
    - Se placer dans le répertoire créé et l'ouvrir avec VSCode (ou un autre IDE) --> Ouvrir un terminal et exécuter la syntaxe suivante : 'git clone https://github.com/Perplexe45/TRTConseil.git'
Installer les dépendances du projet avec Composer du fichier 'composer.json'
    'composer install'
Créer la base de donnée trtconseil dans l'invite mysql. (avec linux mint : 'sudo su' et 'mysql')
    CREATE DATABASE trtconseil;
Installer Mailhog pour envoie de message à partir du local --> environnement Linux
    wget https://github.com/mailhog/MailHog/releases/download/v1.0.1/MailHog_linux_amd64
    chmod +x MailHog_linux_amd64
    sudo mv MailHog_linux_amd64 /usr/local/bin/mailhog
    mailhog     //Lancement du programme
    Ouvrir le navigateur et allez à l'adresse http://localhost:8025 pour voir les emails de MailHog lancé lors du test du programme TRTConseil
Configurer les variables d'environnement dans .env (paramètres selon votre configuration mysql) 
    Créer un fichier avec l'extension .env à la racine du projet et coller ces quelques lignes:
    APP_ENV=dev
    APP_DEBUG=true
    APP_SECRET=fa7cdabe8fd459a7bca00f030b81ad38
    DATABASE_URL="mysql://admin@127.0.0.1:3306/trtconseil?serverVersion=8.0.36-0ubuntu0.22.04.1"  #Créer un admin dans mysql qui a tous les droits.
    MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0
    MAILER_DSN=smtp://localhost:1025    #Port qu'utilise mailhog pour recevoir les mails en local

    Modifier les informations de cette ligne (selon votre configuration de mysql selon votre environnement) : 
        DATABASE_URL="mysql://admin@127.0.0.1:3306/trtconseil?serverVersion=8.0.36-0ubuntu0.22.04.1"
    
Ouvrir le terminal dans le dossier du projet et adapter cette ligne, selon votre configuration, pour avoir des données déja enregistrées de l'application.(il faut modifier la route du fichier trtconseil.sql de votre poste de travail en rapport avec l'emplacement de celui-ci sur votre disque dur) --> Le fichier trtconseil.sql est à la racine du projet.
    mysql -u root -p trtconseil < /media/alain/Formation/trtconseil.sql ( c'est ma configuration ).

lancer symfony 
    symfony serve -d        //Si pas d'erreur au lancement, la base de donnée a bien été configurée
    http://localhost:8000/accueil       //Point d'entrée du site


//////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////création d’un administrateur pour le back office//////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
Création du mot de passe avec un environnement linux pour l'administrateur avec bcrypt et cout de 13 (très sécurisant) : 
1- sudo apt install whois       //Installation de l'application 'whois' pour le cryptage du mot de passe.
2- mkpasswd -m bcrypt -R 13 alainStudi37        //Création du mot de passe 'alainStudi37'
	$2b$13$Z6vDT5i2W8teSdrb8rMpkepeAYVWmRFoPWNu.qMY6J.uLddolIhfe		
3- Dans l'invite mysql du terminal pour donner les instructions à la base de donnée 'trtconseil'
    USE trtconseil      //utilisation de la base de donnée 'trtconseil'
4- Exemple d'insertion pour la céation d'un administrateur dans la table 'user' en utilisant le mot de passe créé précédemment.
    INSERT INTO `user` (`id`, `email`, `password`, `consultant_id`, `candidat_id`, `recruteur_id`, `administrateur_id`, `en_service`, `connec_recruteur`, `connec_candidat`, `connec_consultant`, `connec_administrateur`, `roles`)
    VALUES (1, 'alain.asselin@laposte.net', '$2b$13$Z6vDT5i2W8teSdrb8rMpkepeAYVWmRFoPWNu.qMY6J.uLddolIhfe', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, '["ROLE_ADMIN"]');
5- On sort de l'invite de commande mysql
    exit
6- On peut maintenant se loguer en tant qu'administrateur en utilisant l'identifiant 'alain.asselin@laposte.net' et le mot de passe : 'alainStudi37' et créer un consultant si on le souhaite.
PS : Il faut s'y prendre à 2 fois pour rentrer dans le back office de l'administrateur, 1 fois pour s'identifier et une autre fois pour y rentrer (Bug ?). Il faudra donc cliquer 2 fois sur le bouton 'Connexion'.







    

