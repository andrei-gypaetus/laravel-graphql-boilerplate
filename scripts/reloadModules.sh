#!/bin/bash

if test -f "./scripts/reloadModules.sh"; then
  # Delete modules and composer lock file
  echo "#################################################"
  echo "Deleting vendor modules and composer lock file..."
  echo "#################################################"

  rm -rf ./vendor
  rm -rf ./composer.lock

  echo "#################"
  echo "Modules cleared !"
  echo "#################"

  sleep 3
  echo "~~~~~~~~~~~~~~~~~~~~~~~"

  # Install modules
  echo "#################"
  echo "Installing composer modules..."
  echo "#################"

  composer install

  echo "#################"
  echo "Modules installed !"
  echo "#################"
else
  echo "Please run this script from the root of the project (e.g. [ sh ./scripts/reloadModules.sh ])"
fi