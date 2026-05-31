const { defineConfig } = require('@playwright/test');

module.exports = defineConfig({
  testDir: '.',
  timeout: 60000,
  use: {
    baseURL: 'http://localhost/ujian/',
    trace: 'on',
    video: 'on',
    screenshot: 'only-on-failure',
    launchOptions: {
      args: ['--disable-web-security'],
    },
  },
  reporter: [['list'], ['html', { outputFolder: 'playwright-report' }]],
});
