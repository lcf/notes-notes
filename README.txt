For installation the application on your own web server:

1. Set public directory as your DOCUMENT ROOT, add Zend Framework to your include path

2. Create /application/configs/application.local.ini configuration file and add database configurations for development section. Like this:

[development : production]
resources.db.params.host = "localhost"
resources.db.params.dbname = "notes"
resources.db.params.username = "root"
resources.db.params.password = ""
resources.db.params.profiler.enabled = true
3. Create tables in the database using dump located in the /data directory.

4. Probably you will need to perform some additional web server configurations

Running with Docker
===================

There is no image distributed for the project. Clone the repository and build the image with `docker build .`.
Run the application with `docker run -d -p 80:80 ceb93a463e2d`. User your new image id. Access the website via http://127.0.0.1/

The container comes with the web server and the database. In order to overwrite some of the configuration parameters, add `application.local.ini` to /var/www/application/configs/

