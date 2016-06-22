#!/bin/bash

if [ ! -d "elasticsearch" ]; then
  wget https://download.elastic.co/elasticsearch/release/org/elasticsearch/distribution/tar/elasticsearch/2.3.3/elasticsearch-2.3.3.tar.gz
  tar xf elasticsearch-2.3.3.tar.gz
  rm elasticsearch-2.3.3.tar.gz
  mv elasticsearch-2.3.3 elasticsearch

  # Add the kopf plugin, which will be available under http://localhost:9200/_plugin/kopf/
  ./elasticsearch/bin/plugin install lmenezes/elasticsearch-kopf/2.0
fi


if [ ! -d "kibana" ]; then
  # For other platforms, check out https://www.elastic.co/downloads/kibana
  wget https://download.elastic.co/kibana/kibana/kibana-4.5.1-darwin-x64.tar.gz
  tar xf kibana-4.5.1-darwin-x64.tar.gz
  rm kibana-4.5.1-darwin-x64.tar.gz
  mv kibana-4.5.1-darwin-x64 kibana
fi
