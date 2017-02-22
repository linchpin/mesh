# Contributing

All code contributions are welcome! These contribution guidelines will help you
jump right into writing code for this repository.

## Submitting Code

This repository follows a pull request/peer review workflow. All code submitted
into `develop` and `master` must be done through a pull request.

The `master` branch can be considered stable, production ready code. A list of
stable releases is maintained as we go and can be used by anyone concerned by
ongoing development.

All ongoing development takes place in the primary branch, `develop`.

Every effort should be made to make a pull request as stable as possible before
merging it in.

### `develop` Pull Request Process

When the code in your feature branch is done and ready to be merged, a pull
request to `develop` should be created.

1. Ensure your local checkout of the repository is up to date.
1. Check out the `develop` branch.
1. Create a new branch for your work.
1. Make as many changes and commits as necessary within your branch.
1. When your code is finished and ready, submit a pull request to merge your
branch into `develop`.
1. After your pull request receives approval from at least one other team
member, merge your code into `develop` and ensure the merge went smoothly.
1. After verifying your merge, delete your feature branch.

### Branch Names

Branches can be named anything you want. However, it is very helpful to include
useful information in branch names, such as:

* GitHub issue numbers.
* Short name of the problem (Ex. adding-thumbnail-support-to-pages).
* Other identifying information.

### Writing Good Code

All pull requests should be peer reviewed by another contributor. Please use
good coding style & best practices.

Except where noted this repository follows the [WordPress Coding Standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/)
to the best of our abilities. This helps ensure that everyone's coding style
and methodologies follow the same conventions, allowing our review efforts to
be focused on the code's effectiveness, performance, and functionality.

Each commit and pull request will automatically be evaluated by [Code Climate](https://codeclimate.com/).
Code Climate will use these coding standards and best practices to
automatically evaluate each commit. A Code Climate build will "fail" when new
issues are introduced within a pull request. While it is not required for your
pull request to pass the Code Climate build, it is highly encouraged. Our
codebase should be consistently improving, not regressing.

### Unit Tests

If this repository possesses any unit tests, they are required to pass in order
for a pull request to be merged.

If you are introducing new functions in your pull request, please do your best
to include unit tests validating the functionality of newly added functions.

### Creating Pull Requests

Pull requests should have a meaningful titles and descriptions of the changes
being merged in. This could mean one sentence, or 4 paragraphs. Someone
reviewing your pull request should be able to easily understand what was added
or changed, why, and how you fixed it. Use your best judgement.

When submitting your pull request to `develop`, add a line to `CHANGELOG.md`
explaining your change. This should be placed under the "Unreleased" heading.
There should be at least one changelog entry per pull request.

Any necessary changes to any `README.md` files should also be made before
making a pull request. For anything that need more explanation or context,
link out to a blog post documenting them in more detail.

If one exists, the pull request should link to the GitHub issue (typing # will
bring up an autocomplete dialogue to search through issues). Also, consider
linking the pull request to a Trello card with the GitHub Power-Up.

### Deleting Branches

After successfully merging a branch into the `develop` or `master`, the pulled
branch should be deleted. Only branches with active development, or unmerged
code should remain in the repository. The person merging the branch and
closing out the pull request is responsible for doing this.

## Creating A New Release

### `master` Pull Request Process

When the code in `develop` is ready to be released into the wild, a pull
request to `master` should be created.

1. Ensure your local checkout of the repository is up to date.
1. Check out the `develop` branch.
1. Create a new branch for preparing your release.
1. Increment version number strings.
1. Update the "Unreleased heading in the `CHANGELOG.md` to reflect the version
being released.
1. Perform any necessary build tasks through Grunt.
1. Submit a pull request to merge your branch into `master`.
1. After your pull request receives approval from at least one other team
member, merge your code into `master` and ensure the merge went smoothly.
1. After verifying your merge, tag the master branch with the version number
released. Ex. `1.5.1`, or `1.6`.
1. Delete your release branch.

## Creating A Hot Fix

Sometimes, a fix needs to be deployed to production ASAP. When this happens,
there is a third flow that should be followed. Because `develop` will have
code that has not yet been released into the wild (and may not be ready), hot
fixes should be performed in a branch off of `master`.

### Creating Hot Fix Pull Requests

1. Ensure your local checkout of the repository is up to date.
1. Check out the `master` branch.
1. Create a new branch for your work.
1. Make necessary code changes and commit.
1. Because a hot fix is deployed to production, version numbers should also be
incremented.
1. Update the `CHANGELOG.md` to reflect the new version being released, and
list the changes being made.
1. Perform any necessary build tasks through Grunt.
1. Submit a pull request to merge your branch into `master`.
1. Because this code will bypass `develop`, it is important to get at least one
code review from another team member.
1. After your pull request receives approval, merge your code into `master` and
ensure the merge went smoothly.
1. After verifying your merge, tag the master branch with the version number
released.
1. Because this pull request went directly into `master`, we need to open a
second pull request into `develop`. Follow the [instructions above for develop
                                                pull requests](#develop-pull-request-process). Unless there are merge
conflicts, you don't need to get this pull request peer reviewed because it
was already reviewed when being merged into `master`.
