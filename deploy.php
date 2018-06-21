<?php
/**
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @author Adegoke Obasa <goke@cottacush.com>
 */

namespace Deployer;

require 'deploy/vendor/autoload.php';
require 'deploy/vendor/deployer/deployer/recipe/symfony.php';

set('ssh_type', 'native');

serverList('deploy/servers.yml');


set('writable_dirs', ['app/runtime', 'app/logs', 'app/web/assets']);
set('shared', ['app/runtime', 'app/logs', 'vendor']);

set('repository', '{{REPO_URL}}');

set('composer_options', 'install --verbose --prefer-dist --optimize-autoloader --no-progress --no-interaction');

set('local_path', __DIR__);

/**
 * Installing node dependencies.
 */
task('deploy:install_node_dependencies', function () {
    run('cd {{release_path}} && npm install');
    run('cd {{release_path}} && ./node_modules/.bin/bower install');
    run('cd {{release_path}} && ./node_modules/.bin/gulp build --env {{APPLICATION_ENV}}');
})->desc('Installing Node Dependencies');


/**
 * Run migrations
 */
task('deploy:run_migrations', function () {
    run('cd {{release_path}}/vendor/cottacush/spar-db-migrations && DB_HOST={{DB_HOST}} DB_NAME={{DB_NAME}} DB_USERNAME={{DB_USERNAME}} DB_PASSWORD={{DB_PASSWORD}} REDIS_HOST={{REDIS_HOST}} REDIS_PORT={{REDIS_PORT}} REDIS_DATABASE={{REDIS_DATABASE}} DEFAULT_ADMIN_EMAIL={{DEFAULT_ADMIN_EMAIL}} DEFAULT_ADMIN_PASSWORD={{DEFAULT_ADMIN_PASSWORD}} APPLICATION_ENV={{APPLICATION_ENV}} ant update-migrations -DPHP_EXECUTABLE={{PHP_EXECUTABLE}}');
})->desc('Run migrations');

/** Yii2 composer setup */
task('deploy:yii2_composer_config', function () {
    run('composer config -g github-oauth.github.com ' . get('GITHUB_TOKEN'));
    run('composer global require "fxp/composer-asset-plugin"');
});


task('deploy:update_staging', function () {
    runLocally('cd ' . get('local_path'));
    runLocally('git stash');
    runLocally('git fetch');

    runLocally('git checkout develop');
    runLocally('git pull origin develop');
    runLocally('git checkout staging');
    runLocally('git pull origin staging');
    runLocally('git merge develop');
    runLocally('git push origin staging');
})->onlyForStage('staging');

/**
 * Cleanup old releases.
 */
task('cleanup', function () {
    $releases = get('releases_list');

    $keep = get('keep_releases');

    while ($keep > 0) {
        array_shift($releases);
        --$keep;
    }

    foreach ($releases as $release) {
        run("sudo rm -rf {{deploy_path}}/releases/$release");
    }

    run("cd {{deploy_path}} && if [ -e release ]; then rm release; fi");
    run("cd {{deploy_path}} && if [ -h release ]; then rm release; fi");

})->desc('Cleaning up old releases');

/**
 * Tag a new release on production.
 */
task('release:tag_release', function () {
    writeln('Tagging Release... ');
    runLocally('cd ' . get('local_path'));
    runLocally('git stash');
    runLocally('git checkout production');

    $releaseVersion = get('RELEASE_VERSION');
    $releaseMessage = get('RELEASE_MESSAGE');

    runLocally('git tag -a ' . $releaseVersion . ' -m "' . $releaseMessage . '"');
    runLocally('git push --tags');
    runLocally('git checkout develop');
    writeln('Release Tagged Successfully');
})->onlyForStage('production');

/**
 * Upload env file
 */
task('deploy:upload_environments_file', function () {
    run('mv {{deploy_path}}/.env.{{APPLICATION_ENV}} {{release_path}}/app/env/.env');
});

/**
 * Upload programs.conf
 */
task('deploy:upload_programs_config', function () {
    run('sudo mv {{deploy_path}}/programs.conf.{{APPLICATION_ENV}} /etc/supervisor/conf.d/crm-queue-worker.conf');
});

task('webserver:restart', function () {
    run('sudo service php7.2-fpm restart');
    run('sudo service nginx restart');
});

/**
 * Start the yii beanstalkd queue
 */
task('restart_tasks', function () {
    run('sudo supervisorctl reread');
    run('sudo supervisorctl update');
    run('sudo supervisorctl restart all');
});

/**
 * Main task
 */
task('deploy', [
    'deploy:update_staging',
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:yii2_composer_config',
    'deploy:vendors',
    'deploy:install_node_dependencies',
    'deploy:upload_programs_config',
    'deploy:upload_environments_file',
    'deploy:run_migrations',
    'deploy:symlink',
    'deploy:writable',
    'cleanup',
    'webserver:restart',
    'restart_tasks',
    'release:tag_release'
])->desc('Deploy Project');

/** Slack Tasks Begin */
task('slack:before_deploy', function () {
    postToSlack('Starting deploy on ' . get('server.name') . '...');
});

task('slack:after_deploy', function () {
    postToSlack('Deploy to ' . get('server.name') . ' done');
});

task('slack:after_migrate', function () {
    postToSlack('Migrations done on ' . get('server.name'));
});

task('slack:before_migrate', function () {
    postToSlack('Running migrations on ' . get('server.name') . '...');
});
/** Slack Tasks End */

task('deploy:first_run', function () {
    $firstRun = run('if [ ! -d {{deploy_path}} ]; then echo true; fi');
    set('first_run', ($firstRun == 'true'));
});

function postToSlack($message)
{
    $slackHookUrl = get('SLACK_HOOK_URL');
    if (!empty($slackHookUrl)) {
        runLocally('curl -s -S -X POST --data-urlencode payload="{\"channel\": \"#' . get('SLACK_CHANNEL_NAME') .
            '\", \"username\": \"{{RELEASE_BOT_LABEL}}\", \"text\": \"' . $message . '\"}" ' . get('SLACK_HOOK_URL'));
    } else {
        write('Configure the SLACK_HOOK_URL to post to slack');
    }
}

/**
 * Post to slack if the slack hook URL is not empty
 */
before('deploy:run_migrations', 'slack:before_migrate');
after('deploy:run_migrations', 'slack:after_migrate');
before('deploy', 'slack:before_deploy');
after('deploy', 'slack:after_deploy');
