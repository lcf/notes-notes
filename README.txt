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