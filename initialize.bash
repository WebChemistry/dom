#!/usr/bin/env bash

replace() {
	git grep -l "\\\$$1\\$" | xargs sed -i "s/\\\$$1\\$/$2/g"
}

## Repository name
echo -n "Repository name: "
read NAME

sed -i "s/BARE/$NAME/g" composer.json

## Namespace
echo -n "Namespace: "
read NAMESPACE

replace 'NAMESPACE' "$NAMESPACE"

rm src/.gitkeep
rm ./initialize.bash
