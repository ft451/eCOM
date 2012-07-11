>>> logger.sql
This file contains sql script responsible for creating necessary database schema, tables and default user with permission to access them. Created tables are shown in file logger.png

>>> logger.png
This file contains ERD diagram of tables created in database by script logger.sql

>>> PhonegapLogger
Eclipse project containing Java 6 EE application responsible for gathering data about use of mobile application. For project and dependencies management we use Maven. Application is written using Spring MVC with Hibernate framework as persistence provider.

Configuration files:
- /src/main/resources/properties/database.properties
This file contains database connection related data - username, password and database address.

- /src/main/resources/hibernate/Log.hbm.xml and /src/main/resources/hibernate/User.hbm.xml
These files describe mapping between database tables and Java Classes.