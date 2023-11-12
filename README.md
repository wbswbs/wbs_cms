# wbs_cms

New CMS, using wbsfw 

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