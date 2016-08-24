#!/usr/bin/env bash -e

if ! git diff-index --quiet HEAD --; then
    echo "> Seems like you have uncommitted changes"
    exit 1
fi

ORIGINAL_BRANCH_NAME=$(git branch --no-color 2> /dev/null | sed -e '/^[^*]/d' -e 's/* \(.*\)/\1/')

git checkout master

# Generate and commit apigen
apigen generate --php --title "Project Management" --source src --destination docs --exclude "*/Tests/*" --tree
git add docs
git commit -m 'Update Documentation' docs

# Save last commit so we can cherry-pick it later
LAST_COMMIT_HASH=$(git rev-parse HEAD)

# Switch branch and cherry pick
git checkout docs
git cherry-pick $LAST_COMMIT_HASH

# Go back to previous branch and pop stash
git checkout $ORIGINAL_BRANCH_NAME

echo "Update ApiGen in doc/ based on master branch. Also cherry-picked it to docs."
echo "You should properly push docs"
