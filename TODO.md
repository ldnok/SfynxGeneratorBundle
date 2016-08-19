* strategy pour la command dans le swagger (si on prend la VO pere ou chaque champ)
* faire un objet parseur de swagger (avec d'autres strategies possibles (xml,json))
* x-database dans le swagger
* les VO sont générés meme s'ils existent dans dddbundle
* rename some "bundle" into "structure"
* move the handlers 
* ajouter le getSwaggerFile dans le Query controller comme dans le corps
* faire la génération du fichier config/swagger.yml (déjà commencé)
* ajouter les proceessor monolog (cf etienne)
* ajouter le EventListener/handlerException (cf etienne)
* ajouter les tenantId
* separate commands and queries files (manager, yml)
* move "Entity files" in Domain, not in Application
* dans l'entité, refaire le constructeur qui a été maj car on ne peut pas setter l'id

link for mutitenant strategy
- http://jakelitwicki.com/2015/05/19/multiple-dynamic-entitymanagers-in-symfony2/
- http://forum.symfony-project.org/forum/23/topic/70131.html
- http://stackoverflow.com/questions/17134855/programmatically-modify-tables-schema-name-in-doctrine2
- http://stackoverflow.com/questions/30871721/doctrine2-dynamic-table-name-for-entity
- http://stackoverflow.com/questions/16762686/dynamic-database-connection-symfony2
- http://stackoverflow.com/questions/15108732/symfony2-dynamic-db-connection-early-override-of-doctrine-service
