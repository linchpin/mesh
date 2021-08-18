#!/bin/bash

set -eo

TMP_DIR="$GITHUB_WORKSPACE/mesh"
mkdir "$TMP_DIR"

# If there's no .gitattributes file, write a default one into place
if [ ! -e "$GITHUB_WORKSPACE/.distignore" ]; then
	echo "ℹ︎ Creating .distignore file"

	cat > ".distignore" <<-EOL
	/.gitattributes
	/.gitignore
	/.github
	/README.md
	/.editorconfig
	/composer.json
	/index.php
	EOL
fi;

echo "➤ Copying files to $TMP_DIR"

# This will exclude everything in the .gitattributes file with the export-ignore flag
rsync -rc --exclude-from="$GITHUB_WORKSPACE/.distignore" "$GITHUB_WORKSPACE/" "$TMP_DIR/" --delete
