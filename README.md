# NextJS + Drupal on Platform.sh

Adapting the steps in [NextJS for Drupal Quickstart](https://next-drupal.org/docs/quick-start).

Reference issues:

- https://github.com/chapter-three/next-drupal/issues/14 (closed)
- https://github.com/chapter-three/next-drupal/issues/15 (open)

1. Create the local repo

    ```bash
    mkdir next-drupal
    cd next-drupal 
    git init
    git branch -m main
    ```

1. Create the Drupal site

    ```bash
    composer create-project drupal/recommended-project drupal
    ```

1. Add shared Platform.sh configuration

    ```bash
    mkdir .platform
    curl -s https://raw.githubusercontent.com/platformsh-templates/drupal9/master/.platform/routes.yaml  >> .platform/routes.yaml
    curl -s https://raw.githubusercontent.com/platformsh-templates/drupal9/master/.platform/services.yaml  >> .platform/services.yaml
    ```

1. **Edit** `.platform/routes.yaml`

    *Include the `backend` subdomain for the `upstream` and `redirect` routes. Also, rename the `upstream` from `app` to `drupal`*.

    ```yaml
    "https://backend.{default}/":
        type: upstream
        upstream: "drupal:http"
        cache:
            enabled: true
        cookies: ['/^SS?ESS/', '/^Drupal.visitor/']

    "https://www.backend.{default}/":
        type: redirect
        to: "https://backend.{default}/"
    ```

1. Modify `composer.json` configuration

    *Add the following to `extra`:*

    ```bash
    "extra": {
        "patches": {
            "drupal/subrequests": {
                "Get same results on different request": "https://www.drupal.org/files/issues/2019-07-18/change_request_type-63049395-09.patch"
            },
            "drupal/decoupled_router": {
                "Unable to resolve path on node in other language than default": "https://www.drupal.org/files/issues/2021-05-05/3111456-34.patch"
            }
        },
    }
    ```

1. Install `drupal/next` and Platform.sh modules

    ```bash
    cd drupal
    composer require drupal/next platformsh/config-reader drush/drush drupal/redis cweagans/composer-patches
    ```

1. Get Platform.sh-specific files

    *Get application configuration*

    ```bash
    curl -s https://raw.githubusercontent.com/platformsh-templates/drupal9/master/.environment >> .environment
    curl -s https://raw.githubusercontent.com/platformsh-templates/drupal9/master/.platform.app.yaml >> .platform.app.yaml
    ```

    *Local dev & gitignore*
    ```bash
    curl -s https://raw.githubusercontent.com/platformsh-templates/drupal9/master/.gitignore >> .gitignore
    curl -s https://raw.githubusercontent.com/platformsh-templates/drupal9/master/.lando.upstream.yml >> .lando.upstream.yml
    ```
    
    *Drush*
    ```bash
    mkdir drush
    curl -s https://raw.githubusercontent.com/platformsh-templates/drupal9/master/drush/platformsh_generate_drush_yml.php >> drush/platformsh_generate_drush_yml.php
    ```

    *Drupal settings*
    ```bash
    curl -s https://raw.githubusercontent.com/platformsh-templates/drupal9/master/web/sites/default/settings.php >> web/sites/default/settings.php
    curl -s https://raw.githubusercontent.com/platformsh-templates/drupal9/master/web/sites/default/settings.platformsh.php >> web/sites/default/settings.platformsh.php
    ```

1. Update `.platform.app.yaml`

    *Remove `build.flavor` block, modify the `hooks.build` attribute, and bump the PHP version in `.platform.app.yaml`:

    ```yaml
    type: 'php:8.1'
    
    hooks:
        build: |
            set -e
            composer install --ignore-platform-req=ext-sodium
    ```

1. Update

    ```bash
    composer update
    ```

1. Create GitHub repo

    ```bash
    gh repo create next-drupal
    git add .
    git commit -m "Initial Drupal site."
    git push origin main
    ```

1. Create a project

    ```bash
    platform project:create --title next-drupal --region us-4.platform.sh --plan medium --environments 3 --storage 5 --default-branch main
    ```

1. Integrate project with GitHub

    ```bash
    platform integration:add --type=github --project=PLATFORMSH_PROJECT_ID --token=GITHUB-USER-TOKEN --repository=USER/REPOSITORY
    ```


