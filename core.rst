
DDD Core Documentation 
==============================

This documentation will explains how the DDD core is build and how it works.



DDD structure
--------------

This chapter cover all functionalities of each bundle of a DDD context.


Application directory
````````````````````````

In application directory we will store all

Commands and queries objects, handlers, validators and specifications.


What is it ?

* Command   : An object which permits to do read/write operation on the database
* Query     : An object which permits to retrieve data form the database
* Validator : An object which permits to validate receveid data.
* Specification : An object which permits to applicate verification rules before execute the job.


CommandHandler
'''''''''''''''

For each command  a handler is passed to controller.
In this handler is injected a Workflow Handler which permits to call Validation and Specification classes.

They have two type of CommandHandler :


CommandHandler 
...............

This CommandHandler execute the Command workflow to execute the job.


CommandHandlerDecorator
........................

This CommandHandler permits to add Validation and Specification verification of the commland and execute the Command Workflow to execute the job.


QueryHandler
'''''''''''''''

For eadch Query a Query Handler is passed to controller.
The Query handler call the Manager and use Query object to execute the job.




Domain directory
`````````````````

In the Domain directory we found all business logic and data.



Infrastructure directory
````````````````````````


InfrastructureBundle directory
```````````````````````````````

Presentation directory
````````````````````````


PresentationBundle directory
``````````````````````````````

