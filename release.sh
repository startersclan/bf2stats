#!/bin/sh
# This script makes it easy to create a new release.
# It requires git, which is only used to detect the previous tag.

set -eu

TAG=${1:-}
if [ -z "$TAG" ]; then
    echo "Please specify a tag as a first argument."
    exit 1
fi
TAG_REGEX='^[0-9]+\.[0-9]+\.[0-9]+$'
if ! echo "$TAG" | grep -E "$TAG_REGEX" > /dev/null; then
    echo "Tag does not match regex: $TAG_REGEX"
    exit 1
fi
TAG_PREV=$( git --no-pager tag -l --sort=-version:refname | head -n1 )
if ! echo "$TAG_PREV" | grep -E "$TAG_REGEX" > /dev/null; then
    echo "Previous git tag is invalid. It does not match regex: $TAG_REGEX"
    exit 1
fi

# Update version in docs, .php, and .sql files
git ls-files | grep -E '(README.md|docker-compose.yml|BF2StatisticsConfig.*\.py|src/ASP/index\.php|src/ASP/bf2statistics\.php)' | while read -r l; do
    sed -i "s/$TAG_PREV/$TAG/g" "$l"
done

echo "Done bumping version to $TAG in all files."
