Création de la table : http://localhost:8888/phpMyAdmin/
    -> New
    -> Create database (entrer le nom)
    -> Créer la table : - id auto-increment (int(11), primary key)
                        - username varchar 50
                        - password varchar 255
                        - created_at datetime default:CURRENT_TIMESTAMP

Configuration pour la connexion à la base de données :
    - 'DB_SERVER', 'localhost:8889' (host local)
    - 'DB_USERNAME', 'root' (username phpmyadmin:root)
    - 'DB_PASSWORD', 'root' (password phpmyadmin:root)
    - 'DB_NAME', 'memberarea' (nom de la base de données)

Ouvrir MAMP, start servers
Les dossiers sont dans Applications/MAMP/htdocs
Accéder par exemple au fichier index.php du dossier test :
http://localhost:8888/test/index.php