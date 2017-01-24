# Elasticsearch Workshop

This is the starting codebase for our Elasticsearch workshop.

## Prerequisites

You should have the following installed on your system:

- PHP and composer
- SQLite (test by running ```php --info | grep "SQLite Library"```, you should have at least version 3)
- A recent version of Java (Java 8 recommended)

## Setup

Clone this repository
```
git clone https://github.com/floretan/drupal_elasticsearch_workshop.git
```

Install composer dependencies. This will also call scripts to download Elasticsearch, Kibana and our sample dataset.
```
cd ddd_elasticsearch
composer install
```

Install Drupal using a simple sqlite database.
```
cd web
drush site-install config_installer
```

Run Drupal with the PHP built-in server, by default it will be accessible under http://127.0.0.1:8888
```
drush runserver
```

Run Elasticsearch locally, by default it will be accessible under http://127.0.0.1:9200
```
./elasticsearch/bin/elasticsearch
```

Run Kibana locally, by default it will be accessible under http://127.0.0.1:5600
```
./kibana/bin/kibana
```

Further instructions will be given during the workshop.
