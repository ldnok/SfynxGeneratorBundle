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
        "sfynx-project/tool-ddd-bundle": "dev-2.8-dev",
        "doctrine/mongodb-odm-bundle":"@dev",
        "doctrine/couchdb": "@dev",
        "doctrine/couchdb-odm": "@dev",
        "stof/doctrine-extensions-bundle":"@dev",
        "sfynx-project/tool-generator-bundle": "dev-2.8-dev"
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