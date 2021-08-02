We hope that you find the information below helpful on how to create a pull request that will support the OpenCart project. We politely ask that you **only submit 1 fix per pull request**, instead of many fixes - this will help our code review team check it quickly and easily.

If you submit multiple commits in a single pull request and even a small part of the code is wrong we have to decline the whole pull request, sadly Git/Github does not allow us to cherry pick which commits we accept inside a pull request.

To create a pull request follow these simple steps. The examples will assume you are using Tortoise git on Windows but most Git UI applications will follow similar steps.

1. Login to your own [GitHub account](https://github.com/login) and [fork the OpenCart repository](https://github.com/opencart/opencart/fork). This creates a copy in your account that you can pull, commit and push to without directly affecting the main OpenCart project).

2. Clone your repository to your local machine where you do your development.

3. Now create a new branch in your local repository, call it something like ```patch-1```. Make sure that the new branch is based on master(HEAD) and choose the option to switch to the new branch.

4. Make the changes to any files required - always test your changes on a demo site (either locally or on a live test site on the web)

5. Commit your changes. Be very descriptive in the commit message. Include references to external documentation and links to screenshots to help explain your changes.

6. Now push your changes to GitHub. After your changes are uploaded you will now see your new ```patch-1``` branch in your GitHub repository. Make sure that you click on the new branch in the dropdown to switch to it in your browser.

7. Click the pull request link which will take you to the comparison page - you should see all of your changes here that are different to the main OpenCart repository. At the top you should see what the comparison is based on, it should look like ```opencart:master ... yourusername:patch-1```. This basically means you want to merge your ```patch-1``` branch into the OpenCart ```master``` branch. Finally click the **Create pull request** button!

### This is very important!
Do not make any more commits to your ```patch-1``` branch, if you do then they will also become part of the pull request you created and will very likely force us to decline your request.

### Creating another pull request
You need to separate your new fix from the changes you made before, right click on your local repository (on your computer) and choose _Switch/Checkout_ under Git. Under the _Switch To_ header, choose the _Branch_ radio button (it should already be selected). In the drop down choose the option ```master``` - now press OK. You are now back at the start, follow the steps again from step 3 but use a new branch name like ```patch-2```.

