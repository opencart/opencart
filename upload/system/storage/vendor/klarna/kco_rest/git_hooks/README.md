install
-------

    git remote add -f hooks ssh://git@stash.internal.machines:7999/mint/git-hooks.git
    git merge -s ours --no-commit hooks/master
    git read-tree --prefix=git_hooks -u hooks/master
    git commit -m "imported git hooks"


updating
--------

    git fetch hooks
    git merge -s subtree hooks/master
