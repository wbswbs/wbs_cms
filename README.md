# wbs cms

A very simple CMS, using PHP and the wbsfw - WBS Framework

All Webpages and Content are build with Smarty Templates

# Installation 

<code>
git clone https://github.com/wbswbs/wbs_cms.git
php composer.phar install
sudo cp config/nginx/wbs_cms.conf /etc/nginx/sites-available/your_project.conf
sudo edit /etc/nginx/sites-available/your_project.conf
# Symbolic Link to /sites-enabled
Add Domain to /etc/hosts
</code>

# Aufbau

## Smarty Template Engines

Das WbsCms benutzt Smarty
https://smarty-php.github.io/smarty/4.x/

Die Smarty Templates liegen in /var/data/cms

Der Ordner /template/{template_name/ enthält das übergeordnete HTML Gerüst

Der Ordner /seite/  differenziert für jede Webseite weiter,
dabei können aus dem Ordner

/inhalt weitere Bausteine geladen werden

## Symfony Routing
 
Zur Konfiguration, siehe /config/routes.yaml


# Ressourcen

## Bootstrap4.6

  * https://getbootstrap.com/docs/4.6/
  * https://getbootstrap.com/docs/4.6/examples/

## Smarty4.x

  * https://smarty-php.github.io/smarty/4.x/

## jQuery3.5


# Installationen in der Testphase

  - LOKAL wbs.cms:2100
  - TEST cms.blessen.de //  Netcup