# skeleton #

Skeleton is a login and register system. Built using [lazy-boy], utilising [Silex], [Syringe]  

## What is this repository for? 

Skeleton will create a lazy-boy framework, with added utilities to allow for a user feature, logins and registrations.

Skeleton is configured to use lazy-boy route loader syringe, which defines services and routes in config files using YAML instead of PHP.

## How do I get set up?

### Requirements
* [lazy-boy] 2.0+

### Setup
* Clone the repo
* Change the DB connection, user and password located in the services.yml
* To create page controllers clone the ExampleController.php and change the name and add it to the services.yml
* To create pages clone the ExampleView.php and add page information
* Configure the route following the example route to locate to the controller
* Customise the header and footer files included in the template folders

## Contribution guidelines
* Changes must be clearly defined with either `<!-- START OF CODE CHANGE -->` or `/* START OF CODE CHANGE */`
* Changes must be clearly ended with either `<!-- END OF CODE CHANGE -->` or `/* END OF CODE CHANGE */`
* Code can be re-written, reviewed and commented accordingly
* All PHP must be strictly written in PSR-2 formatting and commneted accordingly
* All dependencies must be correctly namespaced and written accordingly to the PSR-4 standard

### Who do I talk to?
* Josh Carlisle