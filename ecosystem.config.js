module.exports = {
  apps : [{
    name: 'log-handler',
    script: 'bin/svlog',
    interpreter: "/usr/bin/php",
    // Options reference: https://pm2.io/doc/en/runtime/reference/ecosystem-file/
    args: ['server:start'],
    instances: 2,
    autorestart: true,
    watch: false,
    disable_logs: false,
    merge_logs: true,
    max_memory_restart: '256M',
    env: {
      APP_ENV: 'development',
      APP_DEBUG: 1
    },
    env_production: {
      APP_ENV: 'prod',
      APP_DEBUG: 0
    }
  }],

  deploy : {
    production : {
      user : 'node',
      host : '212.83.163.1',
      ref  : 'origin/master',
      repo : 'git@github.com:repo.git',
      path : '/var/www/production',
      'post-deploy' : 'npm install && pm2 reload ecosystem.config.js --env production'
    }
  }
};
