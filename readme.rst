############################
How use generator
#############################



Create a project and a  composer.json file
---------------------------------------------------------------

    composer create-project symfony/framework-standard-edition Demo "2.8.*"
    cd Demo
    vi composer.json

replace your composer.json by this content :


    {
    "name": "symfony/framework-standard-edition",
    "license": "MIT",
    "type": "project",
    "description": "The \"Symfony Standard Edition\" distribution",
    "autoload": {
        "psr-4": { "": "src/" },
        "classmap": [ "app/AppKernel.php", "app/AppCache.php" ]
    },
    "require": {
        "php": ">=5.3.9",
        "symfony/symfony": "2.8.*",
        "doctrine/orm":"dev-master",
        "doctrine/doctrine-bundle": "~1.4",
        "symfony/swiftmailer-bundle": "~2.3",
        "symfony/monolog-bundle": "~2.4",
        "sensio/distribution-bundle": "~5.0",
        "sensio/framework-extra-bundle": "^3.0.2",
        "incenteev/composer-parameter-handler": "~2.0",
        "sfynx-project/tool-ddd-bundle": "@dev",
        "doctrine/mongodb-odm-bundle":"@dev",
        "doctrine/couchdb": "@dev",
        "doctrine/couchdb-odm": "@dev",
        "stof/doctrine-extensions-bundle":"@dev",
        "sfynx-project/tool-generator-bundle": "@dev",
        "stof/doctrine-extensions-bundle":"@dev"
    },
    "require-dev": {
        "sensio/generator-bundle": "~3.0",
        "symfony/phpunit-bridge": "~2.7"
    },
    "scripts": {
        "post-install-cmd": [
            "Incenteev\\ParameterHandler\\ScriptHandler::buildParameters",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::buildBootstrap",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::clearCache",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installAssets",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installRequirementsFile",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::prepareDeploymentTarget"
        ],
        "post-update-cmd": [
            "Incenteev\\ParameterHandler\\ScriptHandler::buildParameters",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::buildBootstrap",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::clearCache",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installAssets",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installRequirementsFile",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::prepareDeploymentTarget"
        ]
    },
    "config": {
        "bin-dir": "bin",
        "platform": {
            "php": "5.3.9"
        }
    },
    "extra": {
        "symfony-app-dir": "app",
        "symfony-web-dir": "web",
        "symfony-assets-install": "relative",
        "incenteev-parameters": {
            "file": "app/config/parameters.yml"
        },
        "branch-alias": {
            "dev-master": "2.8-dev"
        }
    }
}


Modify this line :
  "url":"/home/dev/generator"
By the path of generator repository on your file system.



Go in /home/dev/generator and go on develop branch

    git fetch
    git chekout develop




then run
    rm composer.lock
    composer install --ignore-platform-reqs



Add the generator in your AppKernel.php
-----------------------------------------
new Sfynx\DddGeneratorBundle\SfynxDddGeneratorBundle(),


Create a swagger file (or use swagger.yml in this folder for test)
--------------------------------------------------------------------

A swagger.yml file is present in the root of generator bundle

read write
----------------------
must be root user

Launch the generator
----------------------

SYMFONY_SFYNX_PATH_TO_SWAGGER_FILE=vendor/sfynx/generatorBundle/swagger_country.yml
export SYMFONY_SFYNX_PATH_TO_SWAGGER_FILE
SYMFONY_SFYNX_CONTEXT_NAME=DemoCountry
export SYMFONY_SFYNX_CONTEXT_NAME
php app/console sfynx:generate:ddd:api --create-all

SYMFONY_SFYNX_PATH_TO_SWAGGER_FILE=vendor/sfynx/generatorBundle/swagger_actor.yml
export SYMFONY_SFYNX_PATH_TO_SWAGGER_FILE
SYMFONY_SFYNX_CONTEXT_NAME=DemoActor
export SYMFONY_SFYNX_CONTEXT_NAME
php app/console sfynx:generate:ddd:api --create-all

SYMFONY_SFYNX_PATH_TO_SWAGGER_FILE=vendor/sfynx/generatorBundle/swagger_movie.yml
export SYMFONY_SFYNX_PATH_TO_SWAGGER_FILE
SYMFONY_SFYNX_CONTEXT_NAME=DemoMovie
export SYMFONY_SFYNX_CONTEXT_NAME
php app/console sfynx:generate:ddd:api --create-all



Add this Bundle in AppKernel.php
----------------------------------------------

    new \JMS\SerializerBundle\JMSSerializerBundle(),
    new \Sfynx\DddBundle\SfynxDddBundle(),
    new \DemoGenerator\InfrastructureBundle\DemoGeneratorInfrastructureBundle(),
    new \DemoGenerator\PresentationBundle\DemoGeneratorPresentationBundle(),
    new \Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),

Add a link to routes of your generated context
------------------------------------------------

  project_demogenerator_routing:
     resource: "@DemoGeneratorPresentationBundle/Resources/config/routes/routing_<entityName>.yml"
     prefix: /api


Indicate the Context Database Type
-------------------------------------

add this lines in app/config/config.yml

    DemoGenerator_infrastructure:
        database_type: orm

Indicate the database.driver variable in app/config/parameters.yml
--------------------------------------------------------------------

database.driver: orm

Add this config sections in your config file :
----------------------------------------------
    orm:
        auto_generate_proxy_classes: %kernel.debug%
        auto_mapping: true
        mappings:
            StofDoctrineExtensionsBundle: ~
            DemoGenerator:
                type: annotation
                alias: DemoGenerator
                prefix: DemoGenerator\Domain\Entity
                dir: "%kernel.root_dir%/../src/DemoGenerator/Domain/Entity"
            DemoGeneratorVO:
                type: annotation
                alias: DemoGeneratorVO
                prefix: DemoGenerator\Domain\ValueObject
                dir: "%kernel.root_dir%/../src/DemoGenerator/Domain/ValueObject"
            SfynxDddBundle:
                type: annotation
                alias: VO
                prefix: Sfynx\DddBundle\Layer\Domain\ValueObject
                dir: "%kernel.root_dir%/../vendor/sfynx-project/tool-ddd-bundle/Sfynx/DddBundle/Layer/Domain/ValueObject"
            translatable:
                type: annotation
                alias: Gedmo
                prefix: Gedmo\Translatable\Entity
                dir: "%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Translatable/Entity"
            loggable:
                type: annotation
                alias: Gedmo
                prefix: Gedmo\Loggable\Entity
                dir: "%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Loggable/Entity"
            tree:
                type: annotation
                alias: Gedmo
                prefix: Gedmo\Tree\Entity
                dir: "%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Tree/Entity"
        metadata_cache_driver: array # array|apc|memcache#ETC
        query_cache_driver: array # array|apc|memcache#ETC
        result_cache_driver: array # array|apc|memcache#ETC


Syntax of swagger file :
------------------------
You need to declare requests and responses with original swagger syntax and extends swagger file with this data :


    x-valueObjects:
      IdVO:  <--- Name of value object
        name: id <--- Name of value object
        type: Sfynx\DddBundle\Layer\Domain\ValueObject\IdVO <--- Namespace of value object ( {SYMFONY_SFYNX_CONTEXT_NAME]\Domain\ValueObject\{VONAME} )
        x-fields:                                           <--- Declaration of fields
          id:                                               <--- Name of field
            name: id                                        <--- Name of field
            type: IdVO                                      <--- type of field (string,number,valueObject)
      ProfileVO:
        name: ProfileVO
        type: \DemoActor\Domain\ValueObject\ProfileVO
        x-fields:
          lastname:
            name: lastname
            type: string
          firstname:
            name: firstname
            type: string
      SituationVO:
        name: SituationVO
        type: \DemoActor\Domain\ValueObject\SituationVO
        x-fields:
          sexVO:
            name: SexVO
            type: SexVO
          birthday:
            name: birthday
            type: string
      ContactVO:
        name: ContactVO
        type: \DemoActor\Domain\ValueObject\ContactVO
        x-fields:
          phoneNumber1:
            name: phoneNumber1
            type: string
          phoneNumber2:
            name: phoneNumber2
            type: string
          email:
            name: email
            type: string
      SalaryVO:
        name: SalaryVO
        type: \DemoActor\Domain\ValueObject\SalaryVO
        x-fields:
          value:
            name: value
            type: integer
          currency:
            name: currency
            type: string
      SexVO:
        name: SexVO
        type: \DemoActor\Domain\ValueObject\SexVO
        x-fields:
          gender:
            name: gender
            type: string

    x-entities:                                                <--- Permits to declare entities
      Actor:                                                   <--- Entity Name
        name: Actor                                            <--- Entity Name
        type: entity                                           <--- Entity Type
        x-fields:                                              <--- Entity fields
          entityId:                                            <--- Field name
            name: entityId                                     <--- Field name
            type: id                                           <--- Field type
            voName: IdVO                                       <--- Field VO name
          profile:
            name: profile
            type: valueObject
            voName: ProfileVO
          situation:
            name: situation
            type: valueObject
            voName: SituationVO
          contact:
            name: contact
            type: valueObject
            voName: ContactVO
          salary:
            name: salary
            type: valueObject
            voName: SalaryVO


Criteria syntax
-------------------------------------------
Simple :

{
	"criterias":
		{
			"field": "a.indicatif",
			"operator": "=",
			"value": "1150"
		}

}


Complex :

{
	"criterias": {
		"and": [{
			"or": [{
				"field": "a.ville",
				"operator": "=",
				"value": "'tour'"
			}, {
				"field": "a.ville",
				"operator": "=",
				"value": "'lyon'"
			}]
		}, {
			"field": "a.title",
			"operator": "=",
			"value": "'tour 2'"
		}]
	}
}

